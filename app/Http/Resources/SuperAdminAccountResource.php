<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Super Admin account row for the Super Admin Management module.
 *
 * @mixin User
 */
class SuperAdminAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'vkollabId' => $this->vkollab_id,
            'status' => $this->status,
            'isSelf' => $this->id === $request->user()?->id,
            'lastLoginAt' => $this->last_login_at?->toIso8601String(),
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
