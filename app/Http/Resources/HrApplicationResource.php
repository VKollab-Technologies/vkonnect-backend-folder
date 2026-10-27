<?php

namespace App\Http\Resources;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Job application row for HR review (applicant + job + status).
 *
 * @mixin JobApplication
 */
class HrApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'applicantName' => $this->applicant?->name,
            'applicantVkollabId' => $this->applicant?->vkollab_id,
            'applicantEmail' => $this->applicant?->email,
            // The applicant relation is withTrashed, so a removed alumnus still
            // resolves. Flag it rather than hiding it: HR needs to know the
            // account is gone before trying to shortlist or contact them.
            'applicantRemoved' => $this->applicant?->trashed() ?? true,
            'jobId' => $this->job_posting_id,
            'jobTitle' => $this->job?->title,
            'organization' => $this->job?->organization,
            'status' => $this->status,
            'coverNote' => $this->cover_note,
            'hasResume' => ! empty($this->resume_path),
            'appliedAt' => $this->applied_at?->toIso8601String(),
        ];
    }
}
