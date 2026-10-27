<?php

namespace App\Http\Controllers\Api\V1\Alumni;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Mail\DocumentDeliveryMail;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentDownload;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Services\OtpService;
use App\Support\Disks;
use App\Support\PlatformFeatures;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Alumni document vault. Sensitive files (PAN, Form 16, payslips) live on a
 * PRIVATE disk and are only ever reachable through a short-lived signed URL
 * issued after an ownership check. No file is served from a public path.
 */
class DocumentController extends Controller
{
    private const SIGNED_TTL_SECONDS = 60;

    /**
     * Largest document that may be sent as an email attachment, 15 MB.
     *
     * Mail providers cap the ENCODED message at roughly 20-25 MB, and base64
     * inflates a file by about a third, so 15 MB raw lands near 20 MB on the
     * wire — under the strictest common ceiling. Uploads are separately capped
     * at 10 MB (App\Rules\PdfFile), so no document can reach this today; it
     * exists so that raising the upload limit surfaces as an explicit refusal
     * here instead of a bounced email.
     */
    private const MAX_ATTACHMENT_KB = 15360;

    public function __construct(private readonly NotificationService $notifications) {}

    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()->canAccessDocuments(), 403, 'Document access has been disabled for your account. Contact HR.');

        $documents = Document::query()
            ->where('user_id', $request->user()->id)
            ->with('category')
            ->when($request->filled('categoryId'), fn ($q) => $q->where('category_id', $request->string('categoryId')))
            ->when($request->filled('financialYear'), fn ($q) => $q->where('financial_year', $request->string('financialYear')))
            ->when($request->filled('periodMonth'), fn ($q) => $q->where('period_month', $request->integer('periodMonth')))
            // Newest period first, with month narrowing within a year. NULL
            // months sort last inside their year, so the payslips uploaded
            // before months were recorded sit below the dated ones rather than
            // above them.
            ->orderByDesc('financial_year')
            ->orderByRaw('period_month IS NULL, period_month DESC')
            ->latest()
            ->get();

        // Distinct financial years available to this user (for the filter UI).
        $years = Document::where('user_id', $request->user()->id)
            ->distinct()->orderByDesc('financial_year')->pluck('financial_year');

        return response()->json([
            'data' => DocumentResource::collection($documents),
            'financialYears' => $years,
        ]);
    }

    public function categories(Request $request): JsonResponse
    {
        abort_unless($request->user()->canAccessDocuments(), 403, 'Document access has been disabled for your account. Contact HR.');

        return response()->json([
            'data' => DocumentCategory::orderBy('sort_order')->get(['id', 'name', 'slug']),
        ]);
    }

    /** Issue a short-lived signed URL after verifying ownership. */
    public function requestDownload(Request $request, Document $document): JsonResponse
    {
        abort_unless($document->user_id === $request->user()->id, 403, 'You do not have access to this document.');
        abort_unless($request->user()->canAccessDocuments(), 403, 'Document access has been disabled for your account. Contact HR.');
        abort_unless($request->user()->canDownloadDocuments(), 403, 'Downloads have been disabled for your account. Contact HR.');

        $mode = $request->string('mode')->value() === 'view' ? 'view' : 'download';

        $signedUrl = URL::temporarySignedRoute(
            'alumni.documents.stream',
            now()->addSeconds(self::SIGNED_TTL_SECONDS),
            ['document' => $document->id, 'mode' => $mode],
            // RELATIVE, deliberately. An absolute URL bakes in whatever Host
            // the backend saw, which behind the Vite proxy is localhost:8010 --
            // meaningless on any other device, where localhost is THEIR machine.
            // A relative link resolves against the origin the browser is already
            // on and rides the same proxy as every other API call.
            absolute: false,
        );

        AuditLogger::log('document.downloaded', 'documents', $document->id, [
            'title' => $document->title,
            'category' => $document->category?->name,
            'mode' => $mode,
        ]);

        $this->notifications->notifyRoles(
            ['hr_admin'],
            'document',
            'Document Download Activity Detected',
            "{$request->user()->name} downloaded {$document->title}.",
            '/hr/documents',
        );

        return response()->json(['signedDownloadUrl' => $signedUrl]);
    }

    /**
     * Email the signed-in alumnus their own document, as an attachment.
     *
     * Three gates, all of which must pass:
     *   1. the platform feature flag (Super Admin, off by default),
     *   2. the same ownership + Alumni Control checks requestDownload() uses —
     *      deliberately identical, so this cannot become a weaker path to the
     *      same file,
     *   3. the route's own permission/capability middleware.
     *
     * The recipient is read from the account, never from the request. Accepting
     * an address would turn a personal document vault into a way to mail
     * someone else's payslip anywhere, which is a different feature entirely.
     */
    public function emailToSelf(Request $request, Document $document): JsonResponse
    {
        abort_unless(
            PlatformFeatures::enabled(PlatformFeatures::DOCUMENT_EMAIL),
            403,
            'Emailing documents is not enabled on this platform.',
        );

        $user = $request->user();

        abort_unless($document->user_id === $user->id, 403, 'You do not have access to this document.');
        abort_unless($user->canAccessDocuments(), 403, 'Document access has been disabled for your account. Contact HR.');
        abort_unless($user->canDownloadDocuments(), 403, 'Downloads have been disabled for your account. Contact HR.');
        abort_unless($user->email, 422, 'Your account has no email address on file. Contact HR.');

        // The file has to exist before anything is queued. Without this a
        // document row pointing at a missing file produces a job that retries
        // three times and dies in the queue, while the alumnus was told it had
        // been sent.
        abort_unless(
            Disks::documents()->exists($document->disk_path),
            422,
            'This document is no longer available to send. Contact HR.',
        );

        $sizeBytes = Disks::documents()->size($document->disk_path);

        // Refuse loudly rather than hand the provider something it will bounce.
        // Mail is base64-encoded, which inflates the payload by about a third,
        // so a raw file must stay under roughly three-quarters of the
        // provider's ceiling. Nothing can currently reach this — every upload
        // is capped at 10 MB by App\Rules\PdfFile — but that cap and this one
        // are independent, and a future increase should surface here as a
        // clear message rather than a bounced or silently dropped email.
        abort_if(
            $sizeBytes > self::MAX_ATTACHMENT_KB * 1024,
            422,
            'This document is too large to email ('.$this->megabytes($sizeBytes).' MB). '.
            'Download it from the portal instead.',
        );

        $document->loadMissing('category');

        Mail::to($user->email)->queue(new DocumentDeliveryMail(
            name: $user->name,
            documentTitle: $document->title,
            fileName: $document->original_name,
            diskPath: $document->disk_path,
            categoryName: $document->category?->name,
            financialYear: $document->financial_year,
            mimeType: $document->mime_type,
        ));

        AuditLogger::log('document.emailed', 'documents', $document->id, [
            'title' => $document->title,
            'category' => $document->category?->name,
            // Masked: the audit log is readable by every Super Admin, and the
            // address adds nothing here — it is always the owner's own.
            'to' => OtpService::maskEmail($user->email),
            'delivery' => 'attachment',
            'sizeKb' => (int) round($sizeBytes / 1024),
        ]);

        return response()->json([
            'message' => 'Sent. The document is attached to an email to '.OtpService::maskEmail($user->email).'.',
            'maskedEmail' => OtpService::maskEmail($user->email),
        ]);
    }

    private function megabytes(int $bytes): string
    {
        return number_format($bytes / (1024 * 1024), 1);
    }

    /**
     * Stream the file. Authorized solely by the valid (unexpired) signature —
     * the `signed` middleware enforces it; no Sanctum token is present on a
     * browser navigation. Access is recorded for the audit trail.
     *
     * `mode=view` serves the file inline (browsers render PDFs/images directly);
     * anything else forces an attachment download — same signed URL mechanism,
     * just a different Content-Disposition.
     */
    public function stream(Request $request, Document $document): StreamedResponse
    {
        abort_unless(Disks::documents()->exists($document->disk_path), 404);

        // No Sanctum auth on this route (bare signed URL) — re-check the
        // document owner's current flags directly, closing the window where a
        // signed URL issued before a toggle is still technically unexpired.
        $owner = $document->owner;
        abort_unless($owner && $owner->canAccessDocuments() && $owner->canDownloadDocuments(), 403, 'This document is no longer accessible.');

        DocumentDownload::create([
            'document_id' => $document->id,
            'user_id' => $document->user_id,
            'ip_address' => $request->ip(),
            'downloaded_at' => now(),
        ]);

        if ($request->string('mode')->value() === 'view') {
            return Disks::documents()->response($document->disk_path, $document->original_name, [
                'Content-Disposition' => 'inline; filename="'.$document->original_name.'"',
            ]);
        }

        return Disks::documents()->download($document->disk_path, $document->original_name);
    }
}
