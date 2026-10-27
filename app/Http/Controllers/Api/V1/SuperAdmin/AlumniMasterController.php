<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Support\PanHash;

use App\Exports\AlumniImportFailedRowsExport;
use App\Exports\AlumniMasterExport;
use App\Exports\AlumniMasterTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\AlumniImportBatchResource;
use App\Http\Resources\AlumniMasterResource;
use App\Jobs\ProcessAlumniMasterImport;
use App\Models\AlumniImportBatch;
use App\Models\AlumniMaster;
use App\Services\AlumniMasterImporter;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Support\ApiResponse;
use App\Support\Disks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Alumni Master Data Management — the authoritative alumni roster + the
 * enterprise bulk-import pipeline (validate → queued import → history → failed
 * report) and single-record CRUD. Shared by the Super Admin and HR panels.
 */
class AlumniMasterController extends Controller
{
    public function __construct(
        private readonly AlumniMasterImporter $importer,
        private readonly NotificationService $notifications,
    ) {}

    /** Keep Super Admin aware of HR-authored roster changes (not their own). */
    private function notifySuperAdminIfHr(Request $request, string $title, string $body): void
    {
        if ($request->user()?->role === 'hr_admin') {
            $this->notifications->notifyRoles(['super_admin'], 'alumni', $title, $body, '/super-admin/alumni-master');
        }
    }

    /** Paginated, searchable, filterable roster list + headline counts. */
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $records = AlumniMaster::query()
            // Eager-loaded so AlumniMasterResource::isClaimed() reads the
            // relation instead of running an EXISTS per row.
            ->with('claimant')
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->string('search');
                $q->where(fn ($sub) => $sub
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('vkollab_id', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    // PAN is encrypted, so LIKE cannot reach it. A whole PAN
                    // still finds its record through the blind index; a
                    // partial one no longer can, which the placeholder says.
                    ->when(
                        preg_match('/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/', (string) $term),
                        fn ($p) => $p->orWhere('pan_hash', PanHash::make((string) $term)),
                    ));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('department'), fn ($q) => $q->where('department', $request->string('department')))
            // Scopes, not raw claimed_at: they encode the same "a LIVE account
            // holds this" rule the resource and destroy() use, so a row
            // orphaned by a deleted account no longer shows as unclaimed while
            // being counted as claimed and excluded from the unclaimed filter.
            ->when($request->string('claimed') == 'yes', fn ($q) => $q->claimed())
            ->when($request->string('claimed') == 'no', fn ($q) => $q->unclaimed())
            ->orderBy('vkollab_id')
            ->paginate($perPage);

        $payload = ApiResponse::paginate($records, AlumniMasterResource::class);
        $payload['summary'] = [
            'total' => AlumniMaster::count(),
            'claimed' => AlumniMaster::claimed()->count(),
            'unclaimed' => AlumniMaster::unclaimed()->count(),
            'active' => AlumniMaster::where('status', 'active')->count(),
        ];

        return response()->json($payload);
    }

    /** Dry-run validation of an uploaded file. Stages the file and previews the result. */
    public function validateUpload(Request $request): JsonResponse
    {
        $request->validate([
            // Size lives on the rule itself (SpreadsheetFile::$maxKb), not
            // here: it used to be passed at each call site and the two copies
            // had already drifted to 20 MB and 10 MB.
            'file' => ['required', 'file', new \App\Rules\SpreadsheetFile()],
            'mode' => ['nullable', Rule::in(['skip', 'update', 'create'])],
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension() ?: 'xlsx');
        $stagingId = 'imports/'.Str::uuid()->toString().'.'.$ext;
        Disks::imports()->put($stagingId, file_get_contents($file->getRealPath()));

        try {
            $rows = $this->importer->readRows(Disks::imports()->path($stagingId));
            $result = $this->importer->process($rows, $request->string('mode', 'update'), true, null);
        } catch (\Throwable $e) {
            Disks::imports()->delete($stagingId);

            return response()->json([
                'message' => 'We could not read that file. Make sure it is a valid CSV or Excel file with the template 
header row.',], 422);
        }

        return response()->json([
            'stagingId' => $stagingId,
            'fileName' => $file->getClientOriginalName(),
            'summary' => [
                'total' => $result['total'],
                'willCreate' => $result['created'],
                'willUpdate' => $result['updated'],
                'willSkip' => $result['skipped'],
                'failed' => $result['failed'],
            ],
            'failedRows' => array_slice($result['failedRows'], 0, 200),
        ]);
    }

    /** Confirm import — create a batch and process it on the queue. */
    public function import(Request $request): JsonResponse
    {
        $data = $request->validate([
            'stagingId' => ['required', 'string'],
            'mode' => ['required', Rule::in(['skip', 'update', 'create'])],
            'fileName' => ['nullable', 'string', 'max:200'],
        ]);

        if (! str_starts_with($data['stagingId'], 'imports/') || ! Disks::imports()->exists($data['stagingId'])) {
            return response()->json(['message' => 'Your upload session expired. Please re-upload the file.'], 422);
        }

        /*
         * Finish the import even if the browser gives up waiting.
         *
         * Under QUEUE_CONNECTION=sync the dispatch below runs the whole import
         * INSIDE this request. PHP's default is to kill a script when the
         * client disconnects, so a client-side timeout was terminating imports
         * part-way through: rows written up to that point, the rest never
         * attempted, and the batch frozen at `processing` because none of the
         * job's catch/finally/failed() handlers get to run when the process is
         * simply killed. That is exactly what stranded batch 019fd916.
         *
         * Harmless when the queue is a real worker (`database`), where the
         * request returns immediately and there is nothing in flight to protect.
         */
        ignore_user_abort(true);

        $user = $request->user();
        $batch = AlumniImportBatch::create([
            'uploaded_by' => $user->id,
            'uploaded_by_name' => $user->name,
            'original_filename' => $data['fileName'] ?? basename($data['stagingId']),
            'mode' => $data['mode'],
            'status' => 'processing',
            'ip_address' => $request->ip(),
        ]);

        ProcessAlumniMasterImport::dispatch(
            $batch->id, $data['stagingId'], $data['mode'], $user->id, $user->name, $request->ip(), $user->role,
        );

        return response()->json(['batchId' => $batch->id, 'status' => 'processing'], 202);
    }

    /** Import history (most recent first). */
    public function batches(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 50);
        $batches = AlumniImportBatch::latest()->paginate($perPage);

        return response()->json(ApiResponse::paginate($batches, AlumniImportBatchResource::class));
    }

    /** Single batch status — polled by the UI while an import runs. */
    public function batch(AlumniImportBatch $batch): AlumniImportBatchResource
    {
        return new AlumniImportBatchResource($batch);
    }

    /** Download a batch's failed-rows report. */
    public function failedReport(AlumniImportBatch $batch): BinaryFileResponse|StreamedResponse
    {
        $rows = is_array($batch->failed_rows) ? $batch->failed_rows : [];

        return Excel::download(
            new AlumniImportFailedRowsExport($rows),
            'failed-rows-'.substr($batch->id, 0, 8).'.xlsx',
        );
    }

    /** Add a single alumni record from the UI. */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validateRecord($request);
        $record = AlumniMaster::create($this->mapRecord($data) + [
            'imported_by' => $request->user()->id,
            'status' => $data['status'] ?? 'active',
        ]);

        AuditLogger::log('alumni_master.created', 'alumni', $record->id, ['vkollabId' => $record->vkollab_id]);

        $this->importer->sendRecordWelcomeEmail($record, 'manual');

        $this->notifySuperAdminIfHr(
            $request,
            'Alumni Added',
            "{$request->user()->name} added {$record->name} ({$record->vkollab_id}) to the alumni roster.",
        );

        return response()->json(['data' => new AlumniMasterResource($record)], 201);
    }

    /** Edit a single alumni record. */
    public function update(Request $request, AlumniMaster $alumniMaster): JsonResponse
    {
        $data = $this->validateRecord($request, $alumniMaster->id);
        $alumniMaster->update($this->mapRecord($data));

        AuditLogger::log('alumni_master.updated', 'alumni', $alumniMaster->id, ['vkollabId' => $alumniMaster->vkollab_id]);

        return response()->json(['data' => new AlumniMasterResource($alumniMaster->fresh())]);
    }

    /**
     * Set status on several roster records at once.
     *
     * A dedicated endpoint rather than the client looping update(): that route
     * takes a full RecordPayload, so a status-only change through it would
     * send back every other field and clobber whatever a colleague edited in
     * between. One request is also one transaction and one audit entry
     * describing the operation that actually happened, instead of fifty
     * unrelated-looking edits a second apart.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:500'],
            /*
             * Soft-deleted records are excluded here rather than left to the
             * update. Without whereNull the id validates, the query's global
             * scope skips the row anyway, and the caller is told the batch
             * succeeded while one record silently did not change.
             *
             * Unknown or deleted ids fail the whole request rather than being
             * skipped: a bulk status change that quietly applies to fewer
             * records than asked leaves the operator believing all of them
             * moved, which is the wrong thing to be wrong about on a roster.
             */
            'ids.*' => ['string', Rule::exists('alumni_masters', 'id')->whereNull('deleted_at')],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $updated = AlumniMaster::whereIn('id', $data['ids'])->update(['status' => $data['status']]);

        AuditLogger::log('alumni_master.bulk_status', 'alumni', null, [
            'status' => $data['status'],
            'requested' => count($data['ids']),
            'updated' => $updated,
        ]);

        return response()->json(['updated' => $updated]);
    }

    /** Soft-delete a roster record (only if not yet claimed). */
    public function destroy(Request $request, AlumniMaster $alumniMaster): JsonResponse
    {
        if ($alumniMaster->isClaimed()) {
            return response()->json([
                'message' => 'This record has been claimed by a registered account and cannot be removed.',
            ], 422);
        }

        $name = $alumniMaster->name;
        $vkollabId = $alumniMaster->vkollab_id;
        $alumniMaster->delete();
        AuditLogger::log('alumni_master.deleted', 'alumni', $alumniMaster->id, ['vkollabId' => $vkollabId]);

        $this->notifySuperAdminIfHr(
            $request,
            'Roster Record Deleted',
            "{$request->user()->name} removed {$name} ({$vkollabId}) from the alumni roster.",
        );

        return response()->json(['message' => 'Record removed from the roster.']);
    }

    /** Download the full (or filtered) roster as an Excel file. */
    public function export(Request $request): BinaryFileResponse|StreamedResponse
    {
        $filters = $request->only('search', 'status', 'claimed');
        AuditLogger::log('alumni_master.exported', 'alumni', null, ['filters' => $filters]);

        $format = $request->string('format') == 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
        $ext = $format === \Maatwebsite\Excel\Excel::CSV ? 'csv' : 'xlsx';

        return Excel::download(new AlumniMasterExport($filters), 'alumni-master-'.now()->format('Y-m-d').".{$ext}", $format);
    }

    /** Download the blank import template. */
    public function template(): BinaryFileResponse|StreamedResponse
    {
        return Excel::download(new AlumniMasterTemplateExport, 'alumni-master-template.xlsx');
    }

    // ---- helpers -------------------------------------------------------------

    /**
     * HR-entry field set — the 10 essential fields, plus record
     * status/lifecycle, plus the 5 HR-managed "My Information" exit-record
     * fields (last working location, band, supervisor, UAN, PF number) —
     * these are never alumni-editable, so HR is the only place they're ever
     * set. organization/currentDesignation/location are still NOT collected
     * here: they stay nullable/fillable on the AlumniMaster schema and are
     * set later by HR via the Alumni Control "Manage" dialog, once the
     * alumnus has activated.
     */
    private function validateRecord(Request $request, ?string $ignoreId = null): array
    {
        // whereNull('deleted_at') on every uniqueness check: AlumniMaster is
        // soft-deleted, and Laravel's `unique` rule counts trashed rows. Without
        // this, deleting a roster record made its EMP ID / email / PAN
        // permanently unusable — re-adding the same person was rejected as
        // "already exists" while the record was nowhere in the visible list.
        $live = fn ($q) => $q->whereNull('deleted_at');


        // The claimed account carries the same EMP ID and email as its roster
        // row by design (the observer keeps them in step), so editing a claimed
        // record must not trip the users-table check against its own account.
        $claimantId = $ignoreId
            ? AlumniMaster::whereKey($ignoreId)->value('claimed_by')
            : null;

        // Uniqueness is checked against BOTH tables. Checking only
        // alumni_masters let an EMP ID or address that already belonged to a
        // live account through, and the collision then surfaced as a raw
        // constraint error at activation, long after whoever typed it had
        // moved on.
        $liveUser = fn ($q) => $q->whereNull('deleted_at');

        return $request->validate([
            'vkollabId' => [
                'required', 'string', 'max:40',
                Rule::unique('alumni_masters', 'vkollab_id')->ignore($ignoreId)->where($live),
                Rule::unique('users', 'vkollab_id')->ignore($claimantId)->where($liveUser),
            ],
            'name' => ['required', 'string', 'max:160'],
            'email' => [
                'nullable', 'email', 'max:160',
                Rule::unique('alumni_masters', 'email')->ignore($ignoreId)->where($live),
                Rule::unique('users', 'email')->ignore($claimantId)->where(
                    fn ($q) => $q->whereNull('deleted_at')->where('role', 'alumni')
                ),
            ],
            'phone' => ['nullable', new \App\Rules\PhoneNumber],
            /*
             * Uniqueness runs against the blind index, not the column.
             * `pan` is encrypted non-deterministically, so Rule::unique on it
             * would match nothing and wave every duplicate through.
             *
             * A closure rather than Rule::unique('alumni_masters','pan_hash'):
             * that would need the hash merged into the request, and the 422
             * would then come back keyed on `pan_hash` -- a field the form has
             * no input for, so the message would never reach the PAN box.
             * The soft-delete scope on the model already excludes trashed
             * rows, which is what keeps a deleted row from holding a PAN.
             */
            'pan' => [
                'nullable',
                new \App\Rules\PanNumber,
                function (string $attribute, mixed $value, \Closure $fail) use ($ignoreId) {
                    $clash = AlumniMaster::where('pan_hash', PanHash::make((string) $value))
                        ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                        ->exists();

                    if ($clash) {
                        $fail('This PAN number is already associated with another record.');
                    }
                },
            ],
            'department' => ['nullable', 'string', 'max:120'],
            'designation' => ['nullable', 'string', 'max:120'],
            // A date of birth in the future, or today, is always a typo.
            'dob' => [
                'nullable', 'date', 'before:today',
                /*
                 * Under-18 is a WARNING that must be acknowledged, not a silent
                 * block and not a silent accept.
                 *
                 * An alumnus under 18 is nearly always a mistyped year, but not
                 * impossible — apprentices and interns exist. A hard block would
                 * push whoever is entering the record into inventing a date that
                 * passes, which is worse data than the typo. Accepting quietly
                 * lets the typo through. So the save is refused once, with a
                 * message naming the computed age, and goes through when the
                 * client resends with ageAcknowledged.
                 *
                 * The gate lives here rather than only in the form so it cannot
                 * be stepped over by calling the API directly.
                 */
                function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                    if (! $value || $request->boolean('ageAcknowledged')) {
                        return;
                    }

                    $age = \Carbon\Carbon::parse($value)->age;

                    if ($age < 18) {
                        $fail("This date of birth makes the alumnus {$age} years old. "
                            .'Check the year — if it is correct, confirm to save anyway.');
                    }
                },
            ],
            'ageAcknowledged' => ['sometimes', 'boolean'],
            'joinedOn' => ['nullable', 'date'],
            // Leaving before joining is not a thing. Only bites when a joining
            // date is present, so a partial roster row is still saveable.
            'exitedOn' => ['nullable', 'date', 'after_or_equal:joinedOn'],
            'lastWorkingLocation' => ['nullable', 'string', 'max:160'],
            'band' => ['nullable', 'string', 'max:40'],
            'supervisorName' => ['nullable', 'string', 'max:160'],
            'supervisorEmail' => ['nullable', 'email', 'max:160'],
            'uanNumber' => ['nullable', new \App\Rules\UanNumber],
            'pfNumber' => ['nullable', 'string', 'max:40'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ], [
            'vkollabId.unique' => 'This EMP ID already exists. Please use a different EMP ID.',
            'email.unique' => 'This email address is already registered.',
            'dob.before' => 'Date of birth must be in the past.',
            'exitedOn.after_or_equal' => 'Date of exit cannot be earlier than the date of joining.',
        ]);
    }

    private function mapRecord(array $data): array
    {
        return array_filter([
            // vkollab_id and pan are NOT normalised here any more — the model
            // mutators own that, so this path and the bulk importer cannot
            // drift apart on a rule that registration depends on for an exact
            // match. Pass the raw value; AlumniMaster normalises on write.
            'vkollab_id' => $data['vkollabId'] ?? null,
            'name' => $data['name'] ?? null,
            'email' => isset($data['email']) ? strtolower(trim($data['email'])) : null,
            'phone' => $data['phone'] ?? null,
            'pan' => $data['pan'] ?? null,
            'department' => $data['department'] ?? null,
            'designation' => $data['designation'] ?? null,
            'dob' => $data['dob'] ?? null,
            'joined_on' => $data['joinedOn'] ?? null,
            'exited_on' => $data['exitedOn'] ?? null,
            'last_working_location' => $data['lastWorkingLocation'] ?? null,
            'band' => $data['band'] ?? null,
            'supervisor_name' => $data['supervisorName'] ?? null,
            'supervisor_email' => isset($data['supervisorEmail']) ? strtolower(trim($data['supervisorEmail'])) : null,
            'uan_number' => $data['uanNumber'] ?? null,
            'pf_number' => $data['pfNumber'] ?? null,
            'status' => $data['status'] ?? null,
        ], fn ($v) => $v !== null);
    }
}
