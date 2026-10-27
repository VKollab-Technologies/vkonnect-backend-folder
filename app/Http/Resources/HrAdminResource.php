<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * HR Admin row for management tables. Surfaces the user's directly-assigned
 * permissions so the Super Admin can see and tune each HR's capabilities.
 *
 * @mixin User
 */
class HrAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'vkollabId' => $this->vkollab_id,
            'status' => $this->status,
            'department' => $this->hrAdmin?->department,
            // Effective permissions (direct + inherited from the hr_admin role).
            'permissions' => $this->getAllPermissions()->pluck('name')->values(),
            // Super Admin's per-HR-admin feature switches — a reversible
            // override that sits on top of permissions without marking the
            // user "customized" the way editing their permission profile does.
            'capabilities' => $this->hrCapabilities(),
            'lastLoginAt' => $this->last_login_at?->toIso8601String(),
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
