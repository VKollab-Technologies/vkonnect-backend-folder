<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasUuids;

    public const UPDATED_AT = null; // append-only — created_at only

    protected $fillable = [
        'user_id', 'actor_name', 'actor_role', 'action', 'status', 'module',
        'subject_type', 'subject_id', 'meta', 'ip_address', 'user_agent', 'created_at',
    ];

    protected function casts(): array
    {
        return ['meta' => 'array', 'created_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Severity classification for the Super Admin feed. */
    public function severity(): string
    {
        return match (true) {
            $this->status === 'failure',
            str_contains($this->action, 'deleted'),
            str_contains($this->action, 'disabled'),
            $this->action === 'auth.locked' => 'critical',
            str_contains($this->action, 'permission'),
            str_contains($this->action, 'status'),
            str_contains($this->action, 'rejected') => 'warning',
            default => 'info',
        };
    }
}
