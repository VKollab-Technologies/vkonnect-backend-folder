<?php

namespace App\Http\Resources;

use App\Models\AlumniImportBatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AlumniImportBatch
 */
class AlumniImportBatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fileName' => $this->original_filename,
            'mode' => $this->mode,
            'status' => $this->status,
            'totalRows' => $this->total_rows,
            'created' => $this->created_count,
            'updated' => $this->updated_count,
            'skipped' => $this->skipped_count,
            'failed' => $this->failed_count,
            'uploadedBy' => $this->uploaded_by_name,
            'ip' => $this->ip_address,
            'error' => $this->error,
            'createdAt' => $this->created_at?->toIso8601String(),
            'completedAt' => $this->completed_at?->toIso8601String(),
            'failedRows' => is_array($this->failed_rows) ? $this->failed_rows : [],
        ];
    }
}
