<?php

namespace App\Http\Middleware;

use App\Support\FeatureRegistry;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The single access gate, replacing `permission:`, `hr.capability:` and
 * `alumni.capability:`.
 *
 * Usage:
 *   ->middleware('feature:hr.documents,documents.upload')
 *   ->middleware('feature:alumni.jobs')                    // no extra permission
 *   ->middleware('feature:hr.alumni,alumni.disable|alumni.activate')
 *
 * Two questions, one answer:
 *   1. Does this user's ROLE allow the action?  (Spatie permission)
 *   2. Has the realm above them switched the FEATURE off?  (registry column)
 * Both must pass. Previously these lived in two separate middleware entries
 * that had to be remembered independently, and a route with only one of them
 * looked guarded while being half-open — which is how several features ended
 * up switchable in the UI and unenforced on the server.
 *
 * Realm resolution is by the CALLER, not the route: shared endpoints (the
 * notification inbox) are declared once per realm in the registry, and this
 * picks the entry matching whoever is signed in.
 *
 * Super Admin is never restricted — nothing sits above it. That mirrors the
 * existing Gate::before short-circuit rather than introducing a second rule.
 */
class EnsureFeature
{
    public function handle(Request $request, Closure $next, string $featureKey, ?string $permission = null): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request); // auth:sanctum owns this case
        }

        $features = FeatureRegistry::all();

        if (! isset($features[$featureKey])) {
            // A wiring mistake, not a pass. Failing open here would silently
            // disable a gate someone believed was active.
            throw new \InvalidArgumentException("Unknown feature [{$featureKey}].");
        }

        $feature = $features[$featureKey];

        // Both checks are scoped to the realm that OWNS the feature. That is
        // what makes shared endpoints work: the notification inbox is declared
        // once per realm, and a route stacks both entries —
        //
        //   feature:alumni.notifications,notifications.view
        //   feature:hr.notifications
        //
        // An alumnus is held to the alumni permission and switch; an HR admin
        // to the HR switch; each entry ignores the realms it does not own.
        // Scoping only the switch would have made HR fail an alumni-only
        // permission it can never hold.
        if ($user->role !== $feature['realm']) {
            return $next($request);
        }

        // 1. Role permission — via Gate, so Gate::before still grants Super
        //    Admin everything (it never reaches here anyway: no feature is
        //    owned by the super_admin realm).
        if ($permission !== null && ! $this->hasAnyPermission($user, $permission)) {
            abort(403, 'You do not have permission to do that.');
        }

        // 2. The switch set by the realm above.
        if ($feature['column'] !== null && $user->{$feature['column']} !== null) {
            abort(403, $this->message($feature));
        }

        return $next($request);
    }

    /** `a|b` means either is sufficient, matching the old permission middleware. */
    private function hasAnyPermission($user, string $permission): bool
    {
        foreach (explode('|', $permission) as $name) {
            if ($user->can(trim($name))) {
                return true;
            }
        }

        return false;
    }

    private function message(array $feature): string
    {
        return $feature['realm'] === FeatureRegistry::REALM_ALUMNI
            ? "{$feature['label']} has been disabled for your account. Contact HR."
            : "{$feature['label']} has been disabled for your account by an administrator.";
    }
}
