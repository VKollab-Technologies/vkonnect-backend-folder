<?php

namespace App\Http\Resources;

use App\Models\AlumniProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The alumni-facing "My Information" view — entirely HR-managed, read-only
 * data (see Alumni\ProfileController, which has no update() at all). Grouped
 * to match the three sections the frontend renders: General Information, PF
 * Information, Contact Information. `pan` and `lastWorkingLocation` are
 * deliberately absent here — not part of the visible "My Information" set —
 * even though both columns remain on the schema for other uses (PAN for
 * registration lookup, last_working_location for HR's own records).
 *
 * @mixin AlumniProfile
 */
class AlumniProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // General Information
            'name' => $this->user?->name,
            'vkollabId' => $this->vkollab_id,
            'designation' => $this->designation,
            'joinedOn' => $this->joined_on?->toDateString(),
            'exitedOn' => $this->exited_on?->toDateString(),
            'band' => $this->band,
            'supervisorName' => $this->supervisor_name,
            'supervisorEmail' => $this->supervisor_email,

            // PF Information
            'uanNumber' => $this->uan_number,
            'pfNumber' => $this->pf_number,

            // Contact Information
            'phone' => $this->phone,
            'email' => $this->user?->email,

            // Not one of the HR-managed fields — the alumnus's own photo stays self-service.
            'avatarUrl' => $this->user?->avatarUrl(),
        ];
    }
}
