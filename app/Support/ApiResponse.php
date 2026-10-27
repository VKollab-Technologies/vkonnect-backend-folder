<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Shaping helpers for consistent API envelopes. Maps Laravel's snake_case
 * paginator metadata to the camelCase `Paginated<T>` shape the frontend
 * expects (see frontend/src/types/index.ts).
 */
class ApiResponse
{
    /**
     * @param  class-string  $resource  API Resource class to wrap each item.
     */
    public static function paginate(LengthAwarePaginator $paginator, string $resource): array
    {
        return [
            'data' => $resource::collection($paginator->items()),
            'meta' => [
                'currentPage' => $paginator->currentPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
                'lastPage' => $paginator->lastPage(),
            ],
        ];
    }
}
