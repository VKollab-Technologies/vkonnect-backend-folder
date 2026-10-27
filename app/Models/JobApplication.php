<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'job_posting_id',
        'user_id',
        'status',
        'resume_path',
        'cover_note',
        'reviewed_by',
        'applied_at',
    ];

    protected function casts(): array
    {
        return ['applied_at' => 'datetime'];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    /**
     * withTrashed is load-bearing, not defensive. Users soft-delete (an HR
     * admin removing someone in Alumni Control), but their applications stay
     * for the hiring record. Without this the belongsTo's SoftDeletingScope
     * resolves to null and every HR-facing read of the applicant — the
     * Applications table, the resume filename, the audit log entry — silently
     * degrades to "—" while the row itself still lists. Same reasoning as the
     * withTrashed on job(): the parent going away must not erase who applied.
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
