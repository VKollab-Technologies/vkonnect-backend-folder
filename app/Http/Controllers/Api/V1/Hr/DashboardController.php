<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlumniResource;
use App\Models\Announcement;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * HR operational overview. Returns the payload consumed by the HR dashboard
 * (frontend/src/modules/hr/types.ts).
 */
class DashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return $this->successResponse([
            'stats' => [
                'totalAlumni' => User::where('role', 'alumni')->count(),
                'activeJobs' => JobPosting::where('status', 'open')->count(),
                'publishedAnnouncements' => Announcement::where('status', 'published')->count(),
            ],
            'recentAlumni' => AlumniResource::collection(
                User::where('role', 'alumni')
                    ->with('alumniProfile', 'permissions')->latest()->limit(5)->get()
            ),
            'recentJobs' => JobPosting::latest()->limit(5)->get()->map(fn (JobPosting $j) => [
                'id' => $j->id,
                'title' => $j->title,
                'organization' => $j->organization,
                'status' => $j->status,
                'postedAt' => $j->created_at?->toIso8601String(),
            ]),
        ]);
    }
}
