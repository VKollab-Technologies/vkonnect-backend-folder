<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'slug', 'filename_code', 'description', 'sort_order', 'hr_enabled'];

    protected function casts(): array
    {
        return ['hr_enabled' => 'boolean'];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id');
    }

    /**
     * Categories a given realm may see and file documents into.
     *
     * Super Admin owns the taxonomy and sees everything, including categories
     * it has not yet released to HR. HR sees only what has been granted, one
     * category at a time. Alumni see every category their own documents are
     * filed under — `hr_enabled` governs who may ADMINISTER a category, not
     * whose documents exist, and hiding an alumnus's own Form 16 because HR's
     * access was revoked would be a different and wrong behaviour.
     */
    public function scopeVisibleTo($query, ?string $role)
    {
        return $role === 'hr_admin' ? $query->where('hr_enabled', true) : $query;
    }
}
