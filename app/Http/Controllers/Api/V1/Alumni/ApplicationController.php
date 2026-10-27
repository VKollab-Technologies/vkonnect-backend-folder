<?php

namespace App\Http\Controllers\Api\V1\Alumni;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplication;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $applications = JobApplication::query()
            ->where('user_id', $request->user()->id)
            ->with('job')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('applied_at')
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($applications, JobApplicationResource::class));
    }
}
