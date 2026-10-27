<?php

namespace App\Http\Controllers\Api\V1\Alumni;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Alumni-facing announcements archive — read-only. Previously announcements
 * were only reachable transiently via the notification bell/ticker; this
 * gives alumni a persistent, paginated place to browse past announcements.
 *
 * Reuses Announcement + AnnouncementResource + ApiResponse::paginate from
 * Hr\AnnouncementController rather than duplicating them, but is its own
 * controller (not a shared index()) because the query differs meaningfully:
 * alumni must only ever see published announcements addressed to them,
 * whereas HR's index() intentionally lists every status for management.
 */
class AnnouncementController extends Controller
{
    /** Published announcements addressed to alumni (audience 'alumni' or 'all'), most recent first. */
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 50);

        $items = Announcement::query()
            ->with('author')
            ->where('status', 'published')
            ->whereIn('audience', ['all', 'alumni'])
            ->latest('published_at')
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($items, AnnouncementResource::class));
    }
}
