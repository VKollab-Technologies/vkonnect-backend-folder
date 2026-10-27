<?php

namespace App\Http\Resources;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AuditLog
 */
class AuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'actor' => $this->actor_name ?? 'System',
            'actorRole' => $this->actor_role,
            'action' => $this->action,
            'status' => $this->status,
            'module' => $this->module,
            'target' => $this->targetLabel(),
            'ip' => $this->ip_address,
            'severity' => $this->severity(),
            'timestamp' => $this->created_at?->toIso8601String(),
        ];
    }

    /** Human-friendly subject derived from the contextual meta. */
    private function targetLabel(): ?string
    {
        $meta = $this->meta ?? [];
        foreach (['title', 'name', 'alumnus', 'user', 'owner', 'key', 'vkollabId', 'file'] as $k) {
            if (! empty($meta[$k])) {
                return (string) $meta[$k];
            }
        }

        return $this->module;
    }
}
