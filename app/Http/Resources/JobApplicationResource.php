<?php

namespace App\Http\Resources;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin JobApplication */
class JobApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'jobId' => $this->job_posting_id,
            'jobTitle' => $this->job?->title,
            'organization' => $this->job?->organization,
            'location' => $this->job?->location,
            'status' => $this->status,
            'appliedAt' => $this->applied_at?->toIso8601String(),
        ];
    }
}
