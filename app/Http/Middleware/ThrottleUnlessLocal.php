<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

/**
 * Laravel's rate limiter, disabled on a local developer machine only.
 *
 * Applied to the auth endpoints (login, OTP verify/resend, forgot/reset
 * password) where manual testing legitimately means dozens of attempts a
 * minute and a 429 stops the work. Everywhere else — and in every other
 * environment — this behaves exactly like `throttle:`, because it IS
 * `throttle:`: the limits, keys, headers and 429 response are the parent's.
 *
 * The environment check lives in AuthService::protectionsApply() so all three
 * brute-force protections answer to one switch, and the arguments stay on the
 * routes so production limits remain visible where they are applied rather
 * than moving somewhere less obvious.
 *
 * NOT a general replacement for `throttle:`. Endpoints that are rate-limited
 * for reasons other than brute force — the document-email route, which sends a
 * real email per call — keep the stock middleware, since flooding an inbox is
 * just as unwelcome locally.
 */
class ThrottleUnlessLocal extends ThrottleRequests
{
    /**
     * @param  int|string  $maxAttempts
     * @param  float|int  $decayMinutes
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = ''): Response
    {
        if (! AuthService::protectionsApply()) {
            return $next($request);
        }

        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }
}
