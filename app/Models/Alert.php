<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A live ticker alert. Surfaced through the scrolling ticker on dashboards.
 */
class Alert extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'message', 'level', 'link', 'audience', 'user_id', 'is_active', 'starts_at', 'ends_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /** The single recipient, when this alert is targeted rather than broadcast. */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Currently-live alerts for a viewer.
     *
     * Two kinds of row qualify:
     *   - broadcast  — `user_id IS NULL` and `audience` matches the role
     *   - targeted   — `user_id` equals the viewer's own id
     *
     * `$viewerId` is required for targeted rows to appear at all. When it is
     * null the query returns broadcasts only; it must never fall through to
     * matching `user_id IS NULL` against the viewer, which would expose every
     * targeted alert. Note super_admin is NOT exempt here: it sees all
     * broadcast audiences, but only its own targeted rows — a targeted alert
     * is private to its recipient, and an oversight role is not a reason to
     * leak one alumnus's document activity to an admin's ticker.
     */
    public function scopeLiveFor(Builder $query, string $role, ?string $viewerId = null): Builder
    {
        $audiences = match ($role) {
            'alumni' => ['all', 'alumni'],
            'hr_admin' => ['all', 'hr'],
            default => ['all', 'alumni', 'hr'], // super_admin sees every broadcast audience
        };

        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) use ($audiences, $viewerId) {
                $q->where(fn (Builder $b) => $b->whereNull('user_id')->whereIn('audience', $audiences));
                if ($viewerId !== null) {
                    $q->orWhere('user_id', $viewerId);
                }
            })
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->latest('created_at');
    }
}
