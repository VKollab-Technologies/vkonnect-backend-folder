<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlertResource;
use App\Models\Alert;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * HR management of the live alert ticker. Gated by `ticker.manage`.
 */
class AlertController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => AlertResource::collection(Alert::latest('created_at')->limit(100)->get()),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateAlert($request);
        $data['created_by'] = $request->user()->id;

        $alert = Alert::create($data);
        AuditLogger::log('ticker.created', 'announcements', $alert->id, ['message' => $alert->message]);

        return response()->json(['data' => new AlertResource($alert)], 201);
    }

    public function update(Request $request, Alert $alert): JsonResponse
    {
        $alert->update($this->validateAlert($request, partial: true));
        AuditLogger::log('ticker.updated', 'announcements', $alert->id, ['message' => $alert->message]);

        return response()->json(['data' => new AlertResource($alert->fresh())]);
    }

    public function destroy(Alert $alert): JsonResponse
    {
        $message = $alert->message;
        $alert->delete();
        AuditLogger::log('ticker.deleted', 'announcements', $alert->id);

        return response()->json(['message' => 'Alert removed.']);
    }

    private function validateAlert(Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return $request->validate([
            'message' => [$required, 'string', 'max:200'],
            'level' => ['sometimes', Rule::in(['info', 'success', 'warning', 'critical'])],
            'link' => ['nullable', 'string', 'max:255'],
            'audience' => ['sometimes', Rule::in(['all', 'alumni', 'hr'])],
            'is_active' => ['sometimes', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);
    }
}
