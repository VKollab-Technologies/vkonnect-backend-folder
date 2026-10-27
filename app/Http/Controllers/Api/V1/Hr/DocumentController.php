<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreDocumentRequest;
use App\Http\Resources\HrDocumentResource;
use App\Models\Alert;
use App\Models\AlumniMaster;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\Documents\DocumentStorage;
use App\Services\NotificationService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * HR-facing document manager. HR uploads/replaces/deletes documents in an
 * alumnus's private vault. All actions are gated by granular `documents.*`
 * permissions and audited. Files live on the PRIVATE `local` disk.
 */
class DocumentController extends Controller
{
    private const SIGNED_TTL_SECONDS = 60;

    /** How long a per-alumnus document ticker alert stays live. */
    private const TARGETED_ALERT_DAYS = 5;

    /** Eager-loads every response in this controller returns a document from. */
    private const WITH_RELATIONS = ['category', 'owner.alumniProfile'];

    public function __construct(
        private readonly NotificationService $notifications,
        private readonly DocumentStorage $storage,
    ) {}

    /** Paginated list across all alumni vaults, filterable by alumnus/category/search. */
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $documents = Document::query()
            ->with(self::WITH_RELATIONS)
            ->when($request->filled('alumnusId'), fn ($q) => $q->where('user_id', $request->string('alumnusId')))
            ->when($request->filled('categoryId'), fn ($q) => $q->where('category_id', $request->string('categoryId')))
            /*
             * Search covers BOTH kinds of document owner.
             *
             * It used to look only at `owner`, the registered user. A document
             * filed against an unclaimed roster record has user_id = NULL and no
             * owner at all, so HR could upload a document for someone who had
             * not registered yet and then never find it again by their name —
             * the one case where search matters most, because the person is not
             * in the alumni list either.
             *
             * Employee ID is searchable too: it is what HR has in front of them
             * on a spreadsheet, and matching it only when it happened to appear
             * in the generated filename was an accident, not a feature.
             */
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = (string) $request->string('search');

                $q->where(function ($s) use ($term) {
                    $s->where('title', 'like', "%{$term}%")
                        ->orWhereHas('owner', fn ($o) => $o
                            ->where('name', 'like', "%{$term}%")
                            ->orWhere('vkollab_id', 'like', "%{$term}%"))
                        ->orWhereHas('rosterRecord', fn ($r) => $r
                            ->where('name', 'like', "%{$term}%")
                            ->orWhere('vkollab_id', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($documents, HrDocumentResource::class));
    }

    public function categories(): JsonResponse
    {
        // filename_code is included so the upload form can render the exact
        // generated filename (EMPID_DOCNAME_YEAR) before the file is sent.
        return response()->json([
            // Filtered by realm: the upload form must not offer HR a category
            // they are not allowed to file into, or every upload attempt into
            // it becomes a 403 they cannot explain.
            'data' => DocumentCategory::visibleTo(request()->user()?->role)
                ->orderBy('sort_order')->get(['id', 'name', 'slug', 'filename_code'])
                ->map(fn (DocumentCategory $c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'filenameCode' => $c->filename_code,
                ]),
        ]);
    }

    /**
     * Recipients for the upload picker: activated alumni AND unclaimed roster
     * records.
     *
     * HR needs to prepare an exiting employee's vault before they register —
     * previously those people simply were not listed, so the documents could
     * not be filed at all. Roster entries are marked `registered: false` so
     * the UI can label them; uploading against one files the document against
     * the roster record until activation hands it over.
     */
    public function recipients(Request $request): JsonResponse
    {
        $term = $request->string('search')->value();
        $matches = fn ($q, array $columns) => $q->when($term !== '', function ($sub) use ($term, $columns) {
            $sub->where(function ($s) use ($term, $columns) {
                foreach ($columns as $i => $column) {
                    $i === 0 ? $s->where($column, 'like', "%{$term}%") : $s->orWhere($column, 'like', "%{$term}%");
                }
            });
        });

        $users = $matches(
            User::query()->where('role', 'alumni')->where('status', 'active')->with('alumniProfile'),
            ['name', 'email'],
        )->orderBy('name')->limit(50)->get();

        // Only unclaimed rows: a claimed one already has an account above, and
        // listing both would offer the same person twice.
        $roster = $matches(
            AlumniMaster::query()->whereNull('claimed_by'),
            ['name', 'email', 'vkollab_id'],
        )->orderBy('name')->limit(50)->get();

        $data = $users->map(fn (User $u) => [
            'id' => $u->id,
            'kind' => 'user',
            'registered' => true,
            'name' => $u->name,
            'vkollabId' => $u->alumniProfile?->vkollab_id ?? $u->vkollab_id,
            // Drives the upload dialog's auto-detected Financial Year range —
            // the years this alumnus could actually have a period-based
            // document for.
            'joinedOn' => $u->alumniProfile?->joined_on?->toDateString(),
            'exitedOn' => $u->alumniProfile?->exited_on?->toDateString(),
        ])->concat(
            $roster->map(fn (AlumniMaster $m) => [
                'id' => $m->id,
                'kind' => 'roster',
                'registered' => false,
                'name' => $m->name,
                'vkollabId' => $m->vkollab_id,
                'joinedOn' => $m->joined_on?->toDateString(),
                'exitedOn' => $m->exited_on?->toDateString(),
            ])
        )->sortBy('name')->values();

        return response()->json(['data' => $data]);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $file = $request->file('file');

        $category = DocumentCategory::findOrFail($data['categoryId']);
        [$owner, $roster] = $this->resolveRecipient($data['userId']);

        // documentName drives the DOCNAME segment for catch-all categories
        // (Personal Documents), so PAN / Aadhaar / etc. filed under one stay
        // distinguishable rather than all becoming EMPID_PERSONAL_DOCS.
        $stored = $this->storage->put(
            $file,
            // Files are keyed by owner so a hand-off does not have to move them.
            $owner?->id ?? "roster/{$roster->id}",
            $this->vkollabIdFor($owner, $roster),
            $category,
            $data['financialYear'] ?? null,
            documentName: $data['documentName'] ?? null,
            periodMonth: $data['periodMonth'] ?? null,
        );

        $document = Document::create([
            'user_id' => $owner?->id,
            'alumni_master_id' => $roster?->id,
            'category_id' => $data['categoryId'],
            'uploaded_by' => $request->user()->id,
            'title' => $data['title'],
            'financial_year' => $data['financialYear'] ?? null,
            'period_month' => $data['periodMonth'] ?? null,
            ...$stored->toAttributes(),
        ]);

        $this->notifyOwner($document, 'New document available', "{$document->title} was added to your vault.");

        AuditLogger::log('document.uploaded', 'documents', $document->id, [
            'title' => $document->title,
            'owner' => $document->owner?->name,
        ]);

        return response()->json(['data' => $this->present($document)], 201);
    }

    /**
     * Resolve the upload target: an activated account, or an unclaimed roster
     * record for someone who has not registered yet (activation hands the
     * documents over — see AuthService::activateFromMaster).
     *
     * `role = alumni` is load-bearing, not cosmetic. Without it any user id
     * resolves here, so a document — and its notification — could be filed into
     * an HR admin's or Super Admin's vault, accounts with no document vault UI.
     * That is a silent write into the wrong place.
     *
     * @return array{0: ?User, 1: ?AlumniMaster} exactly one is non-null
     */
    private function resolveRecipient(string $userId): array
    {
        $owner = User::with('alumniProfile')->where('role', 'alumni')->find($userId);
        $roster = $owner ? null : AlumniMaster::whereNull('claimed_by')->find($userId);

        abort_unless($owner || $roster, 422, 'Select a valid alumnus or roster record.');

        return [$owner, $roster];
    }

    /**
     * The Employee ID to stamp into the filename.
     *
     * Every step is null-safe by necessity: `$roster` is null whenever `$owner`
     * was found, and an alumnus may have no EMP ID on either the profile or the
     * user row. A non-null-safe read here fatals instead of falling through to
     * the filename builder's UNASSIGNED default.
     */
    private function vkollabIdFor(?User $owner, ?AlumniMaster $roster): ?string
    {
        return $owner?->alumniProfile?->vkollab_id
            ?? $owner?->vkollab_id
            ?? $roster?->vkollab_id;
    }

    /** One eager-load list for every document this controller returns. */
    private function present(Document $document): HrDocumentResource
    {
        return new HrDocumentResource($document->load(self::WITH_RELATIONS));
    }

    /** Replace the file on an existing document record (keeps owner/category/title). */
    public function replace(Request $request, Document $document): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', new \App\Rules\PdfFile(10240)], // documents are PDF only
        ]);

        $document->loadMissing(['owner.alumniProfile', 'rosterRecord', 'category']);

        $stored = $this->storage->put(
            $request->file('file'),
            $document->user_id ?? "roster/{$document->alumni_master_id}",
            $document->ownerVkollabId(),
            $document->category,
            $document->financial_year,
            ignorePath: $document->disk_path,
        );

        $oldPath = $document->disk_path;

        $document->update([
            ...$stored->toAttributes(),
            'uploaded_by' => $request->user()->id,
        ]);

        $this->storage->deleteReplaced($oldPath, $stored);

        $this->notifyOwner($document, 'Document updated', "{$document->title} was updated in your vault.");

        AuditLogger::log('document.replaced', 'documents', $document->id, ['title' => $document->title]);

        return response()->json(['data' => $this->present($document)]);
    }

    public function destroy(Request $request, Document $document): JsonResponse
    {
        $path = $document->disk_path;
        $owner = $document->owner?->name;
        $title = $document->title;
        $document->delete();
        $this->storage->delete($path);

        AuditLogger::log('document.deleted', 'documents', $document->id, ['title' => $title]);

        $this->notifications->notifyRoles(
            ['super_admin'],
            'document',
            'Document Deleted',
            "{$request->user()->name} deleted \"{$title}\" belonging to {$owner}.",
            '/super-admin/dashboard',
        );

        return response()->json(['message' => 'Document removed.']);
    }

    /** Issue a short-lived signed URL (reuses the public signed stream route). */
    public function download(Document $document): JsonResponse
    {
        $signedUrl = URL::temporarySignedRoute(
            'alumni.documents.stream',
            now()->addSeconds(self::SIGNED_TTL_SECONDS),
            ['document' => $document->id],
            absolute: false, // see Alumni\DocumentController::requestDownload
        );

        AuditLogger::log('document.downloaded', 'documents', $document->id, [
            'title' => $document->title,
            'by' => 'hr',
        ]);

        return response()->json(['signedDownloadUrl' => $signedUrl]);
    }

    /**
     * Notify the document's owner — and only the owner — in both channels:
     * the notification inbox and, via a user-targeted Alert row, their own
     * ticker. The Alert carries `user_id` and no audience meaning, so it can
     * never surface on another alumnus's ticker (see Alert::scopeLiveFor).
     */
    private function notifyOwner(Document $document, string $title, string $body): void
    {
        // Nobody to notify yet for a roster-filed document — the alumnus has
        // no account. They see the document waiting when they activate.
        if ($document->user_id === null) {
            return;
        }

        $this->notifications->notify($document->user_id, 'document', $title, $body, '/alumni/documents');

        // Respect the same per-alumnus ticker kill switch the feed endpoint
        // honours — no point writing a row the owner can never see.
        if (! $document->owner?->canSeeTicker()) {
            return;
        }

        Alert::create([
            'message' => Str::limit($body, 180),
            'level' => 'info',
            'audience' => 'alumni', // ignored while user_id is set; kept non-null for consistency
            'user_id' => $document->user_id,
            'link' => '/alumni/documents',
            'is_active' => true,
            // Short-lived: a document notice is only interesting for a few days.
            'ends_at' => now()->addDays(self::TARGETED_ALERT_DAYS),
        ]);
    }

}
