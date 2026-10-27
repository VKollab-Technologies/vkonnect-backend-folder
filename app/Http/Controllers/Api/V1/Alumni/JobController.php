<?php

namespace App\Http\Controllers\Api\V1\Alumni;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alumni\ApplyToJobRequest;
use App\Http\Resources\JobResource;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/** Alumni-facing job discovery + application. Only `open` roles are listed. */
class JobController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 12), 50);

        $jobs = JobPosting::query()
            ->where('status', 'open')
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->boolean('remote'), fn ($q) => $q->where('is_remote', true))
            ->latest()
            ->paginate($perPage);

        // Mark which roles the alumnus has already applied to.
        $appliedIds = JobApplication::where('user_id', $request->user()->id)
            ->pluck('job_posting_id')->flip();

        $jobs->getCollection()->each(
            fn (JobPosting $job) => $job->hasApplied = $appliedIds->has($job->id)
        );

        return response()->json(ApiResponse::paginate($jobs, JobResource::class));
    }

    public function apply(ApplyToJobRequest $request, JobPosting $job): JsonResponse
    {
        abort_unless($job->status === 'open', 422, 'This role is no longer accepting applications.');

        $exists = JobApplication::where('job_posting_id', $job->id)
            ->where('user_id', $request->user()->id)->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'job' => ['You have already applied to this role.'],
            ]);
        }

        $resumePath = $request->hasFile('resume')
            ? $request->file('resume')->store("resumes/{$request->user()->id}", config('filesystems.documents'))
            : null;

        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $request->user()->id,
            'status' => 'submitted',
            'resume_path' => $resumePath,
            'cover_note' => $request->validated('coverNote'),
            'applied_at' => now(),
        ]);

        AuditLogger::log('application.created', 'applications', $application->id, [
            'job' => $job->title,
            'applicant' => $request->user()->name,
        ]);

        // HR acts on it; Super Admin gets the same oversight signal it already
        // receives for job posts, announcements and document deletions.
        $this->notifications->notifyRoles(
            ['hr_admin', 'super_admin'],
            'application',
            'New Job Application Received',
            "{$request->user()->name} applied for {$job->title}.",
            '/hr/applications',
        );

        return response()->json(['message' => 'Application submitted.'], 201);
    }
}
