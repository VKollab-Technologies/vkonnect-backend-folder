<?php

namespace App\Http\Resources;

use App\Models\AlumniMaster;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AlumniMaster
 */
class AlumniMasterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vkollabId' => $this->vkollab_id,
            'pan' => $this->pan,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'department' => $this->department,
            'designation' => $this->designation,
            'organization' => $this->organization,
            'currentDesignation' => $this->current_designation,
            'location' => $this->location,
            'dob' => $this->dob?->toDateString(),
            'joinedOn' => $this->joined_on?->toDateString(),
            'exitedOn' => $this->exited_on?->toDateString(),
            'lastWorkingLocation' => $this->last_working_location,
            'band' => $this->band,
            'supervisorName' => $this->supervisor_name,
            'supervisorEmail' => $this->supervisor_email,
            'uanNumber' => $this->uan_number,
            'pfNumber' => $this->pf_number,
            'status' => $this->status,
            // isClaimed(), not the raw timestamp: a claim held by a deleted
            // account must not show as "Registered" in the roster, or the row
            // looks locked when it is actually free.
            'claimed' => $this->isClaimed(),
            'claimedAt' => $this->claimed_at?->toIso8601String(),
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
