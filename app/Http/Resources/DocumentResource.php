<?php

namespace App\Http\Resources;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Document metadata only — the file path is NEVER serialized. Downloads go
 * through a separate short-lived signed-URL endpoint.
 *
 * @mixin Document
 */
class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            // The generated EMPID_DOCNAME_YEAR name. This is what the file
            // downloads as, so the vault has to show it too — otherwise the
            // alumnus sees a friendly title on screen and an unrelated-looking
            // filename in their downloads folder, with no way to connect them.
            'originalName' => $this->original_name,
            'categoryId' => $this->category_id,
            // A document can outlive its category (Super Admin force-delete
            // detaches rather than destroys), so every consumer needs a label
            // rather than a null to render.
            'categoryName' => $this->category?->name ?? 'Uncategorized',
            'financialYear' => $this->financial_year,
            // Null for every category except Salary Slips, and also for the
            // payslips uploaded before months were recorded — the client
            // renders those as "month not recorded" rather than guessing.
            'periodMonth' => $this->period_month,
            'sizeKb' => $this->size_kb,
            'mimeType' => $this->mime_type,
            'uploadedAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
