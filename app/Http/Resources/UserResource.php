<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Support\FeatureRegistry;
use App\Support\PlatformFeatures;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Serializes a User into the camelCase AuthUser shape the frontend expects
 * (see frontend/src/types/index.ts).
 *
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'vkollabId' => $this->vkollab_id,
            'role' => $this->role,
            'status' => $this->status,
            'avatarUrl' => $this->avatarUrl(),
            'permissions' => $this->getAllPermissions()->pluck('name')->values(),
            // ONE map, keyed by registry key — `hr.documents`, `alumni.jobs`.
            // True means the user holds the permission AND the feature has not
            // been switched off above them. The client no longer reconciles
            // permissions against capabilities against platform flags, which
            // is what let the UI and the server disagree.
            'features' => FeatureRegistry::forUser($this->resource),
            // Platform-wide flags that are not per-account features (currently
            // just the send-to-email switch). Kept separate because they are
            // set once for the whole deployment, not per user.
            'platformFeatures' => PlatformFeatures::forUser($this->resource),
            // Retained for the Alumni Control / HR Control dialogs, which edit
            // the raw switches. Not used for gating any more.
            'capabilities' => $this->capabilities(),
            'emailVerifiedAt' => $this->email_verified_at?->toIso8601String(),
            'lastLoginAt' => $this->last_login_at?->toIso8601String(),
        ];
    }

    /**
     * The per-alumnus Alumni Control switches, exposed to the signed-in user
     * so the UI can hide what has been turned off for them.
     *
     * Permissions answer "is this person allowed to do X by role"; capabilities
     * answer "has an administrator switched X off for this individual". Both
     * have to reach the frontend, or a toggle flipped in Alumni Control leaves
     * the alumnus staring at a button that 403s.
     *
     * Empty for Super Admin — nothing sits above them to switch anything off.
     */
    private function capabilities(): array
    {
        return match ($this->role) {
            'alumni' => $this->alumniCapabilities(),
            'hr_admin' => $this->hrCapabilities(),
            default => [],
        };
    }
}
