<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Alert;
use App\Models\JobPosting;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $jobs = JobPosting::query()
            ->withCount('applications')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($jobs, JobResource::class));
    }

    public function store(StoreJobRequest $request): JsonResponse
    {
        $data = $request->validated();

        $job = JobPosting::create([
            'posted_by' => $request->user()->id,
            'title' => $data['title'],
            'organization' => $data['organization'],
            'location' => $data['location'],
            'type' => $data['type'],
            'is_remote' => $data['isRemote'] ?? false,
            'description' => $data['description'],
            'requirements' => $data['requirements'] ?? null,
            'closes_at' => $data['closesAt'] ?? null,
            'status' => 'open',
        ]);

        AuditLogger::log('job.created', 'jobs', $job->id, ['title' => $job->title]);

        // Notify alumni about the new opportunity (powers the bell + notification-fold-in ticker items).
        $this->notifications->notifyRoles(
            ['alumni'],
            'job',
            "New job posted: {$job->title}",
            "{$job->organization} · {$job->location}",
            '/alumni/jobs',
        );

        // A real, broadcast-worthy ticker entry — this is relevant to every alumnus, not
        // just the recipient of a personal notification, so it gets its own Alert row
        // (same pattern as Announcement::fanOut).
        $alert = Alert::create([
            // Name the role so consecutive postings don't all read identically
            // in the ticker.
            'message' => Str::limit("New job opportunity: {$job->title} at {$job->organization}", 180),
            'level' => 'success',
            'audience' => 'alumni',
            'link' => '/alumni/jobs',
            'is_active' => true,
            // Expire like announcement alerts do. Without an end date every
            // job posting stayed live forever, and since the ticker feed is
            // capped at 20 rows, a handful of postings would silently push
            // announcements and every other alert out of the bar entirely.
            'ends_at' => now()->addDays(7),
            'created_by' => $request->user()->id,
        ]);
        AuditLogger::log('ticker.created', 'announcements', $alert->id, ['message' => $alert->message]);

        // Additionally email alumni, only if Super Admin has turned on email broadcasts.
        $this->notifications->emailRoles(
            ['alumni'],
            "New job posted: {$job->title}",
            'A new opportunity has been posted',
            "{$job->title} at {$job->organization} · {$job->location}",
            '/alumni/jobs',
            'View job',
        );

        // Keep Super Admin aware of HR-authored job postings.
        $this->notifications->notifyRoles(
            ['super_admin'],
            'job',
            'Job Posted',
            "{$request->user()->name} posted \"{$job->title}\".",
            '/super-admin/dashboard',
        );

        return (new JobResource($job))->response()->setStatusCode(201);
    }

    public function update(StoreJobRequest $request, JobPosting $job): JobResource
    {
        $data = $request->validated();

        $job->update([
            'title' => $data['title'],
            'organization' => $data['organization'],
            'location' => $data['location'],
            'type' => $data['type'],
            'is_remote' => $data['isRemote'] ?? false,
            'description' => $data['description'],
            'requirements' => $data['requirements'] ?? null,
            'closes_at' => $data['closesAt'] ?? null,
        ]);

        AuditLogger::log('job.updated', 'jobs', $job->id, ['title' => $job->title]);

        return new JobResource($job);
    }

    public function updateStatus(Request $request, JobPosting $job): JobResource
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['draft', 'open', 'closed'])],
        ]);

        $job->update(['status' => $validated['status']]);

        AuditLogger::log('job.status_changed', 'jobs', $job->id, ['status' => $validated['status']]);

        return new JobResource($job);
    }

    public function destroy(JobPosting $job): JsonResponse
    {
        AuditLogger::log('job.deleted', 'jobs', $job->id, ['title' => $job->title]);

        $job->delete();

        return response()->json(['message' => 'Job removed.']);
    }
}
