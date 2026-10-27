<?php

namespace App\Logging;

use App\Http\Middleware\AssignRequestId;
use Illuminate\Http\Request;
use Monolog\LogRecord;

/**
 * Attaches request metadata to every log record.
 *
 * Writes to `extra`, not `context`, and that distinction is load-bearing:
 * Laravel merges shared context with the per-call context and lets the CALLER
 * win, so a `Log::info('...', ['path' => $somePath])` anywhere in the app would
 * silently overwrite the request path and make the line describe the wrong
 * request. `extra` is Monolog's own namespace and callers cannot reach it.
 *
 * Values are read at log time rather than at middleware time so the
 * authenticated user is available — the request id is assigned before
 * auth:sanctum runs, and reading the user there would always yield null.
 *
 * Resolves nothing outside an HTTP request (console commands, queue workers),
 * where these fields are meaningless.
 */
class RequestContextProcessor
{
    public function __invoke(LogRecord $record): LogRecord
    {
        // Console commands and queue workers still have a Request bound in the
        // container, but it is a synthetic one — it would stamp every line
        // with method GET and path "/", which reads like real request data and
        // is not.
        if (app()->runningInConsole()) {
            return $record;
        }

        if (! app()->bound('request') || ! app('request') instanceof Request) {
            return $record;
        }

        /** @var Request $request */
        $request = app('request');

        $extra = [
            'request_id' => $request->attributes->get('request_id')
                ?? $request->headers->get(AssignRequestId::HEADER),
            'method' => $request->method(),
            'path' => '/'.ltrim($request->path(), '/'),
            'ip' => $request->ip(),
        ];

        // The guard may not have run yet (or at all) — this must never be the
        // thing that throws while something else is already being logged.
        try {
            if ($user = $request->user()) {
                $extra['user_id'] = $user->id;
                $extra['role'] = $user->role;
            }
        } catch (\Throwable) {
            // Deliberately ignored: logging is best-effort context.
        }

        return $record->with(extra: [...$record->extra, ...array_filter(
            $extra,
            static fn ($value) => $value !== null,
        )]);
    }
}
