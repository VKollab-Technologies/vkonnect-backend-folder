<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gives every request a stable id and puts it — with the authenticated user —
 * into the log context, so all lines emitted while handling one request can be
 * grouped after the fact.
 *
 * Honours an inbound X-Request-Id when a proxy or client already set one, so a
 * trace started upstream stays intact; generates a UUID otherwise. The id is
 * echoed back on the response, which is what lets a user reporting an error
 * hand you the exact key to search the logs for.
 *
 * This middleware only assigns the id. Getting it (and the authenticated user)
 * into log lines is App\Logging\RequestContextProcessor's job, which reads
 * both at log time — this runs before auth:sanctum, so the user is not known
 * yet at this point.
 */
class AssignRequestId
{
    public const HEADER = 'X-Request-Id';

    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $this->resolveIncomingId($request) ?? (string) Str::uuid();

        $request->headers->set(self::HEADER, $requestId);
        $request->attributes->set('request_id', $requestId);

        $response = $next($request);

        $response->headers->set(self::HEADER, $requestId);

        return $response;
    }

    /**
     * Trust an upstream id only if it looks like one. An unvalidated header
     * lands verbatim in every log line for the request, so it is an injection
     * point for anything that reads those logs — cap the length and restrict
     * the alphabet.
     */
    private function resolveIncomingId(Request $request): ?string
    {
        $incoming = trim((string) $request->headers->get(self::HEADER));

        if ($incoming === '' || strlen($incoming) > 128) {
            return null;
        }

        return preg_match('/^[A-Za-z0-9._:-]+$/', $incoming) === 1 ? $incoming : null;
    }
}
