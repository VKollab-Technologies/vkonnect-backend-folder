<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'alumni_master_id',
        'category_id',
        'uploaded_by',
        'title',
        'financial_year',
        'period_month',
        'disk_path',
        'original_name',
        'mime_type',
        'size_kb',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The roster record this document was filed against, when it was uploaded
     * before the alumnus registered. Exactly one of `user_id` /
     * `alumni_master_id` is set; activation moves the document from the second
     * to the first (AuthService::activateFromMaster).
     */
    public function rosterRecord(): BelongsTo
    {
        return $this->belongsTo(AlumniMaster::class, 'alumni_master_id');
    }

    /** True while the document is waiting for its owner to activate. */
    public function isAwaitingActivation(): bool
    {
        return $this->user_id === null && $this->alumni_master_id !== null;
    }

    /** EMP ID of whoever owns this document, account or roster record. */
    public function ownerVkollabId(): ?string
    {
        return $this->owner?->alumniProfile?->vkollab_id
            ?? $this->owner?->vkollab_id
            ?? $this->rosterRecord?->vkollab_id;
    }
}
