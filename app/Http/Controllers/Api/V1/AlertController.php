<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlertResource;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Shared, read-only ticker feed. Any authenticated realm polls this for the
 * live alerts relevant to its audience.
 */
class AlertController extends Controller
{
    public function active(Request $request): JsonResponse
    {
        $user = $request->user();

        // The switch is enforced by `feature:alumni.ticker` on the route and
        // returns 403 — see NotificationController for why the empty-feed
        // approach was replaced.

        // Pass the viewer id so their own targeted alerts surface alongside
        // the broadcast ones.
        $alerts = Alert::query()->liveFor($user->role, $user->id)->limit(20)->get();

        return response()->json(['data' => AlertResource::collection($alerts)]);
    }
}
