<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

/**
 * Read-only view of the platform's general settings for HR admins. Only
 * reachable when Super Admin grants the `hr_settings.access` permission to
 * that specific HR admin — the route middleware enforces this, and the nav
 * item is hidden client-side otherwise.
 */
class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::where('group', 'general')->get();

        return response()->json([
            'data' => $settings->map(fn (Setting $s) => [
                'key' => $s->key,
                'value' => $s->typedValue(),
                'type' => $s->type,
                'label' => $s->label,
            ])->values(),
        ]);
    }
}
