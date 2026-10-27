<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Standard API response envelope for this app:
 *   - single resource:      {data: {...}}
 *   - list / paginated:     {data: [...], meta: {...}}
 *   - dashboards/aggregates: {data: {...}} with the aggregate fields nested
 *     inside `data`, not at the top level
 *
 * `successResponse()`/`errorResponse()` below are the one obvious way to
 * shape a JSON response — prefer them over building an ad-hoc array. For
 * paginated Eloquent results specifically, build the array via
 * `App\Support\ApiResponse::paginate()` (which already returns this same
 * {data, meta} shape) and pass it straight to `response()->json()`.
 */
abstract class Controller
{
    /**
     * @param  mixed  $data  Anything JSON-serializable — typically a
     *                       JsonResource, ResourceCollection, or plain array.
     * @param  ?array  $meta  Non-item metadata (pagination info, counts,
     *                        etc). Omitted entirely from the payload when null.
     */
    protected function successResponse(mixed $data, ?array $meta = null, int $status = 200): JsonResponse
    {
        $payload = ['data' => $data];

        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    /**
     * Matches the shape Laravel's own exception handler already renders for
     * ValidationException et al. ({message, errors?}) — use this only when a
     * controller needs to hand-build an error response outside of throwing.
     */
    protected function errorResponse(string $message, ?array $errors = null, int $status = 422): JsonResponse
    {
        $payload = ['message' => $message];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
