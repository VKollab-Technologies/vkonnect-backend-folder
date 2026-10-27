<?php

namespace App\Http\Controllers\Api\V1\Alumni;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobApplicationResource;
use App\Http\Resources\JobResource;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * The dashboard is the one page every alumnus can reach, including one
     * whose features have all been switched off — a blocked route redirects
     * here. So each section is filtered by its own Alumni Control switch:
     * summarising a feature someone cannot open still discloses it (how many
     * documents they have, which roles are hiring) and puts a live count
     * behind a door that 403s.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $user->id;

        $canDocuments = $user->canAccessDocuments();
        $canJobs = $user->canAccessJobs();
        $canApplications = $user->canApplyToJobs();
        $canAnnouncements = $user->canSeeAnnouncements();

        return response()->json([
            'availableDocuments' => $canDocuments
                ? Document::where('user_id', $userId)->count()
                : 0,
            'activeApplications' => $canApplications
                ? JobApplication::where('user_id', $userId)
                    ->whereIn('status', ['submitted', 'under_review', 'shortlisted'])->count()
                : 0,
            'openRoles' => $canJobs ? JobPosting::where('status', 'open')->count() : 0,
            'jobRecommendations' => JobResource::collection(
                $canJobs
                    ? JobPosting::where('status', 'open')->latest()->limit(4)->get()
                    : collect()
            ),
            'recentApplications' => JobApplicationResource::collection(
                $canApplications
                    ? JobApplication::where('user_id', $userId)->with('job')->latest('applied_at')->limit(4)->get()
                    : collect()
            ),
            'announcements' => $canAnnouncements
                ? Announcement::where('status', 'published')
                    ->whereIn('audience', ['all', 'alumni'])
                    ->latest('published_at')->limit(4)
                    ->get(['id', 'title', 'body', 'published_at'])
                    ->map(fn ($a) => [
                        'id' => $a->id,
                        'title' => $a->title,
                        'body' => $a->body,
                        'publishedAt' => $a->published_at?->toIso8601String(),
                    ])
                : collect(),
        ]);
    }
}
