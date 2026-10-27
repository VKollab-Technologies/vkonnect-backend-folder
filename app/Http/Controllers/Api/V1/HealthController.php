<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Throwable;

/**
 * Deep health check for load balancers / uptime monitors — verifies the
 * database, cache, and queue connections are actually reachable, unlike the
 * framework's built-in /up liveness check.
 */
class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => $this->check(fn () => DB::connection()->getPdo()),
            'cache' => $this->check(function () {
                Cache::put('health:check', true, 5);
                if (Cache::get('health:check') !== true) {
                    throw new \RuntimeException('Cache read/write mismatch.');
                }
            }),
            'queue' => $this->check(fn () => Queue::connection()->size()),
        ];

        $healthy = ! in_array('error', $checks, true);

        return response()->json([
            'status' => $healthy ? 'ok' : 'error',
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }

    private function check(Closure $probe): string
    {
        try {
            $probe();

            return 'ok';
        } catch (Throwable $e) {
            Log::error('Health check dependency failed.', ['error' => $e->getMessage()]);

            return 'error';
        }
    }
}
