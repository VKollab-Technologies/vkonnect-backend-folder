<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sliding session expiry. Sanctum tokens are issued with `expires_at` set to
 * 24h out (AuthService::issueToken) and never touched by Sanctum's own
 * `sanctum.expiration` config (deliberately left null — that setting is a
 * FIXED window measured from `created_at`, which cannot slide; using it here
 * would either duplicate this window uselessly or silently cap sessions at a
 * fixed login-time-relative limit no amount of activity could extend). This
 * middleware is the actual mechanism: every authenticated request pushes the
 * token's `expires_at` another 24h out, so a continuously-active session
 * never expires, while an idle one dies exactly 24h after its last request.
 *
 * Only writes when the extension is actually meaningful (more than 5 minutes
 * since the last one) — without that, a UI that polls every 15-30s would
 * write to the tokens table on nearly every single request.
 */
class ExtendTokenExpiry
{
    private const REWRITE_THRESHOLD_MINUTES = 5;

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();

        /*
         * Only a real PersonalAccessToken has an expiry to extend.
         *
         * Sanctum hands back a TransientToken when the request authenticated
         * through the session guard rather than a bearer token, and that class
         * has no expires_at at all -- reading it raises an undefined-property
         * warning on every such request. Bearer auth is what this app uses, so
         * the path is latent rather than live, but the guard costs nothing and
         * the alternative is a warning nobody would connect back to here.
         */
        if ($token instanceof PersonalAccessToken && $token->expires_at) {
            // By the time we get here auth:sanctum has already rejected an
            // expired token, so expires_at is always still in the future —
            // this is purely "how much runway is left", computed directly
            // from timestamps to avoid relying on a diff method's sign convention.
            $secondsRemaining = $token->expires_at->getTimestamp() - now()->getTimestamp();
            $sessionSeconds = AuthService::SESSION_HOURS * 3600;
            $rewriteThreshold = self::REWRITE_THRESHOLD_MINUTES * 60;

            if ($secondsRemaining < $sessionSeconds - $rewriteThreshold) {
                $token->forceFill(['expires_at' => now()->addHours(AuthService::SESSION_HOURS)])->save();
            }
        }

        return $next($request);
    }
}
