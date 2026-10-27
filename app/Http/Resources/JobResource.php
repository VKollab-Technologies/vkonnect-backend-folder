<?php

namespace App\Http\Resources;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin JobPosting */
class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'organization' => $this->organization,
            'location' => $this->location,
            'type' => $this->type,
            'isRemote' => $this->is_remote,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'status' => $this->status,
            'closesAt' => $this->closes_at?->toDateString(),
            'postedAt' => $this->created_at?->toIso8601String(),
            // Only present when the caller eager-loaded the count (Hr\JobController@index).
            'applicationsCount' => $this->when(isset($this->applications_count), fn () => (int) $this->applications_count),
            // Only present when the caller set $job->hasApplied (currently
            // Alumni\JobController@index only) — omitted entirely elsewhere
            // (e.g. Hr\JobController) rather than always defaulting to false.
            'hasApplied' => $this->when(! is_null($this->hasApplied), fn () => (bool) $this->hasApplied),
        ];
    }
}
