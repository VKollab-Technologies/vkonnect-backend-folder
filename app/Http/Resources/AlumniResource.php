<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Alumni row for management tables. Flattens the User + AlumniProfile into the
 * camelCase shape consumed by the Super Admin alumni list.
 *
 * @mixin User
 */
class AlumniResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $profile = $this->alumniProfile;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'vkollabId' => $profile?->vkollab_id,
            'department' => $profile?->department,
            'designation' => $profile?->designation,
            'currentOrganization' => $profile?->current_organization,
            'phone' => $profile?->phone,
            'location' => $profile?->location,
            'lastWorkingLocation' => $profile?->last_working_location,
            'band' => $profile?->band,
            'supervisorName' => $profile?->supervisor_name,
            'supervisorEmail' => $profile?->supervisor_email,
            'uanNumber' => $profile?->uan_number,
            'pfNumber' => $profile?->pf_number,
            'profileCompletion' => $profile?->profile_completion ?? 0,
            'permissions' => $this->getDirectPermissions()->pluck('name')->values(),
            'joinedOn' => $profile?->joined_on?->toDateString(),
            'lastLoginAt' => $this->last_login_at?->toIso8601String(),
            'createdAt' => $this->created_at?->toIso8601String(),
            // Alumni Control — HR-driven, per-alumnus access flags.
            'isLocked' => $this->isLocked(),
            'downloadsEnabled' => $this->canDownloadDocuments(),
            'documentsEnabled' => $this->canAccessDocuments(),
            'jobsEnabled' => $this->canAccessJobs(),
            'applicationsEnabled' => $this->canApplyToJobs(),
            'notificationsEnabled' => $this->canReceiveNotifications(),
            'tickerEnabled' => $this->canSeeTicker(),
            'profileEnabled' => $this->canAccessProfile(),
            'announcementsEnabled' => $this->canSeeAnnouncements(),
            'dashboardEnabled' => $this->alumniCan('dashboard'),
            'serviceRequestsEnabled' => $this->alumniCan('service_requests'),
        ];
    }
}
