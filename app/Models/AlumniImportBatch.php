<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One alumni-master bulk upload run. Persists who uploaded, when, the outcome
 * counts, and the failed rows — the audit trail behind the import history UI.
 */
class AlumniImportBatch extends Model
{
    use HasUuids;

    protected $fillable = [
        'uploaded_by', 'uploaded_by_name', 'original_filename', 'mode', 'status',
        'total_rows', 'created_count', 'updated_count', 'skipped_count', 'failed_count',
        'failed_rows', 'error', 'ip_address', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'failed_rows' => 'array',
            'completed_at' => 'datetime',
            'total_rows' => 'integer',
            'created_count' => 'integer',
            'updated_count' => 'integer',
            'skipped_count' => 'integer',
            'failed_count' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
