<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosting extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'posted_by',
        'title',
        'organization',
        'location',
        'type',
        'is_remote',
        'description',
        'requirements',
        'status',
        'closes_at',
    ];

    protected function casts(): array
    {
        return [
            'is_remote' => 'boolean',
            'closes_at' => 'date',
        ];
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_posting_id');
    }
}
