<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Controller;
use App\Http\Resources\HrApplicationResource;
use App\Models\JobApplication;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Support\ApiResponse;
use App\Support\Disks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * HR-facing job application review. Gated by the granular `applications.*`
 * permissions (per-individual configurable by the Super Admin).
 */
class ApplicationController extends Controller
{
    private const SIGNED_TTL_SECONDS = 60;

    /** Human-readable copy for the status the applicant now sits at. */
    private const STATUS_COPY = [
        'submitted' => 'Your application has been received.',
        'under_review' => 'Your application is now under review.',
        'shortlisted' => 'Good news — you have been shortlisted.',
        'rejected' => 'Your application was not taken forward this time.',
        'hired' => 'Congratulations — you have been selected.',
    ];

    public function __construct(private readonly NotificationService $notifications) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $applications = JobApplication::query()
            // withTrashed on job: a job can be soft-deleted (see JobPosting::destroy)
            // while its applications remain reviewable — without this, the belongsTo
            // relation's default SoftDeletingScope silently nulls out jobTitle/
            // organization for any application whose job was later removed.
            ->with(['applicant', 'job' => fn ($q) => $q->withTrashed()])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('jobId'), fn ($q) => $q->where('job_posting_id', $request->string('jobId')))
            ->latest('applied_at')
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($applications, HrApplicationResource::class));
    }

    public function updateStatus(Request $request, JobApplication $application): HrApplicationResource
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['submitted', 'under_review', 'shortlisted', 'rejected', 'hired'])],
        ]);

        $application->update(['status' => $data['status'], 'reviewed_by' => $request->user()->id]);

        AuditLogger::log('application.status_changed', 'applications', $application->id, [
            'status' => $data['status'],
        ]);

        // Tell the applicant. Without this an alumnus moved to shortlisted or
        // hired got no signal at all and had to keep re-opening the
        // Applications page to discover it.
        $application->loadMissing(['job' => fn ($q) => $q->withTrashed()]);
        $jobTitle = $application->job?->title ?? 'your application';
        $this->notifications->notify(
            $application->user_id,
            'application',
            "Application update: {$jobTitle}",
            self::STATUS_COPY[$data['status']] ?? 'Your application status has changed.',
            '/alumni/applications',
        );

        return new HrApplicationResource($application->load(['applicant', 'job' => fn ($q) => $q->withTrashed()]));
    }

    /** Issue a short-lived signed URL to stream the applicant's resume. */
    public function downloadResume(JobApplication $application): JsonResponse
    {
        abort_unless($application->resume_path, 404, 'No resume was submitted with this application.');

        $signedUrl = URL::temporarySignedRoute(
            'hr.applications.resume.stream',
            now()->addSeconds(self::SIGNED_TTL_SECONDS),
            ['application' => $application->id],
            absolute: false, // see Alumni\DocumentController::requestDownload
        );

        AuditLogger::log('application.resume_downloaded', 'applications', $application->id, [
            'applicant' => $application->applicant?->name,
        ]);

        return response()->json(['signedDownloadUrl' => $signedUrl]);
    }

    /** Signed, unauthenticated stream endpoint backing the URL above. */
    public function streamResume(JobApplication $application): StreamedResponse
    {
        abort_unless($application->resume_path && Disks::documents()->exists($application->resume_path), 404);

        $filename = $application->applicant?->vkollab_id
            ? "{$application->applicant->vkollab_id}-resume.pdf"
            : 'resume.pdf';

        return Disks::documents()->download($application->resume_path, $filename);
    }
}
