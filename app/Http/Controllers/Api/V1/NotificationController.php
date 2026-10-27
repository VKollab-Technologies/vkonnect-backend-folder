<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\AppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Shared notification endpoints — available to any authenticated user in any
 * realm (super admin, HR, alumni). Powers the bell dropdown and live marquee.
 */
class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $user->id;

        // The switch is enforced by `feature:alumni.notifications` on the route
        // and returns 403. It used to be handled here with an empty feed, which
        // the client could not tell apart from genuinely having no
        // notifications — and which made this the one switch that behaved
        // differently from every other.

        $notifications = AppNotification::where('user_id', $userId)
            ->latest()
            ->limit(30)
            ->get();

        // Not an Eloquent paginator (limit(30), not paginate()), so `meta`
        // here carries unreadCount rather than pagination fields.
        return $this->successResponse(
            NotificationResource::collection($notifications),
            ['unreadCount' => AppNotification::where('user_id', $userId)->whereNull('read_at')->count()],
        );
    }

    public function markRead(Request $request, AppNotification $notification): JsonResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked as read.']);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        AppNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All marked as read.']);
    }
}
