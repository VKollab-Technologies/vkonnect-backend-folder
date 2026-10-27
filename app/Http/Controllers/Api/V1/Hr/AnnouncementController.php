<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreAnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Alert;
use App\Models\Announcement;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    /** Map an announcement audience to recipient roles. */
    private function audienceRoles(string $audience): array
    {
        return match ($audience) {
            'alumni' => ['alumni'],
            'hr' => ['hr_admin'],
            default => ['alumni', 'hr_admin'],
        };
    }

    private function fanOut(Announcement $announcement, string $actorName): void
    {
        $roles = $this->audienceRoles($announcement->audience);

        $this->notifications->notifyRoles(
            $roles,
            'announcement',
            $announcement->title,
            \Illuminate\Support\Str::limit($announcement->body, 120),
        );

        // Additionally email recipients, only if Super Admin has turned on email broadcasts.
        $this->notifications->emailRoles(
            $roles,
            $announcement->title,
            'New announcement',
            \Illuminate\Support\Str::limit($announcement->body, 240),
        );

        // Surface the published announcement on the live ticker for ~7 days.
        $alert = Alert::create([
            'message' => \Illuminate\Support\Str::limit($announcement->title, 180),
            'level' => 'info',
            'audience' => $announcement->audience,
            'is_active' => true,
            'ends_at' => now()->addDays(7),
            'created_by' => $announcement->author_id,
        ]);
        AuditLogger::log('ticker.created', 'announcements', $alert->id, ['message' => $alert->message]);

        // Keep Super Admin aware of HR-authored announcements.
        $this->notifications->notifyRoles(
            ['super_admin'],
            'announcement',
            'Announcement Published',
            "{$actorName} published \"{$announcement->title}\".",
            '/super-admin/dashboard',
        );
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $items = Announcement::query()
            ->with('author')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($items, AnnouncementResource::class));
    }

    public function store(StoreAnnouncementRequest $request): JsonResponse
    {
        $data = $request->validated();
        $publish = $data['publish'] ?? false;

        $announcement = Announcement::create([
            'author_id' => $request->user()->id,
            'title' => $data['title'],
            'body' => $data['body'],
            'audience' => $data['audience'] ?? 'all',
            'status' => $publish ? 'published' : 'draft',
            'published_at' => $publish ? now() : null,
        ]);

        AuditLogger::log('announcement.created', 'announcements', $announcement->id, [
            'title' => $announcement->title,
            'published' => $publish,
        ]);

        if ($publish) {
            $this->fanOut($announcement, $request->user()->name);
        }

        return (new AnnouncementResource($announcement->load('author')))
            ->response()
            ->setStatusCode(201);
    }

    public function publish(Request $request, Announcement $announcement): AnnouncementResource
    {
        $announcement->update(['status' => 'published', 'published_at' => now()]);
        $this->fanOut($announcement, $request->user()->name);

        AuditLogger::log('announcement.published', 'announcements', $announcement->id, ['title' => $announcement->title]);

        return new AnnouncementResource($announcement->load('author'));
    }

    public function destroy(Announcement $announcement): JsonResponse
    {
        AuditLogger::log('announcement.deleted', 'announcements', $announcement->id, ['title' => $announcement->title]);
        $announcement->delete();

        return response()->json(['message' => 'Announcement removed.']);
    }
}
