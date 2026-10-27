<?php

namespace App\Http\Resources;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Document row for the HR document manager — includes the owning alumnus so HR
 * can see whose vault each file belongs to.
 *
 * @mixin Document
 */
class HrDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'categoryId' => $this->category_id,
            // See DocumentResource: a detached document still needs a label.
            'categoryName' => $this->category?->name ?? 'Uncategorized',
            'financialYear' => $this->financial_year,
            // Null for every category except Salary Slips, and also for the
            // payslips uploaded before months were recorded — the client
            // renders those as "month not recorded" rather than guessing.
            'periodMonth' => $this->period_month,
            'sizeKb' => $this->size_kb,
            'mimeType' => $this->mime_type,
            'originalName' => $this->original_name,
            // A document can be owned by an account or, before the alumnus
            // registers, by their roster record — surface whichever applies so
            // HR always sees whose vault it belongs to.
            'ownerId' => $this->user_id ?? $this->alumni_master_id,
            'ownerName' => $this->owner?->name ?? $this->rosterRecord?->name,
            'ownerVkollabId' => $this->ownerVkollabId(),
            'awaitingActivation' => $this->isAwaitingActivation(),
            'uploadedAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
