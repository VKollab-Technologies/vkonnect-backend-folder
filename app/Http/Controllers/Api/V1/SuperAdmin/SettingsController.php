<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Platform settings management. Changes made here by the Super Admin are
 * persisted to the `settings` table and read back everywhere.
 */
class SettingsController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    /**
     * Intentionally unpaginated (no `meta`): the full settings list is small
     * and fixed (config keys, not a growing record set), and the admin UI
     * renders every group at once — pagination would only get in the way.
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all();

        return $this->successResponse($settings->map(fn (Setting $s) => [
            'key' => $s->key,
            'value' => $s->typedValue(),
            'type' => $s->type,
            'label' => $s->label,
            'group' => $s->group,
        ])->values());
    }

    public function update(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['present'],
        ]);

        $changed = [];
        foreach ($payload['settings'] as $key => $value) {
            $setting = Setting::find($key);
            if (! $setting) {
                continue; // ignore unknown keys
            }
            $normalized = Setting::normalize($setting->type, $value);
            if ($normalized !== $setting->value) {
                $changed[$key] = $normalized;
            }
            $setting->update(['value' => $normalized]);
        }

        if ($changed) {
            AuditLogger::log('settings.updated', 'settings', null, ['changed' => array_keys($changed)]);

            $this->notifications->notifyRoles(
                ['hr_admin'],
                'system',
                'Platform Update',
                "{$request->user()->name} updated platform settings.",
                '/hr/settings',
            );
        }

        return $this->index();
    }
}
