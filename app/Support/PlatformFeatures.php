<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\User;

/**
 * Platform-wide feature flags stored in the `settings` table.
 *
 * Distinct from the two per-individual switch systems: permissions say what a
 * ROLE may do, capabilities say what an administrator switched off for ONE
 * person, and these say whether a feature exists on this deployment at all.
 * All three must pass.
 *
 * One place to read them so the key string is not spelled out in a controller,
 * a resource and a test independently — a typo in any of those fails closed and
 * silently, which is the hardest kind of flag bug to notice.
 */
class PlatformFeatures
{
    public const DOCUMENT_EMAIL = 'alumni_document_email_enabled';

    /**
     * Tier 1 of Service Request's three-tier gate: does this deployment offer
     * the feature to HR at all. Off by default.
     *
     * Tier 2 is the per-alumnus Alumni Control switch HR flips, tier 3 is the
     * `service_request.view` permission. An alumnus sees the feature only when
     * all three pass — FeatureRegistry::forUser() folds them into one answer.
     */
    public const SERVICE_REQUESTS = 'service_requests_enabled';

    public static function enabled(string $key): bool
    {
        return (bool) Setting::find($key)?->typedValue();
    }

    /**
     * The flags a given user's UI should react to.
     *
     * Alumni-only by construction. "Send to Email" delivers a document to the
     * signed-in person's own registered address; for HR or a Super Admin
     * viewing someone else's vault that is a different feature with different
     * consequences, so it is reported as off for them rather than left to each
     * call site to remember.
     *
     * @return array<string, bool>
     */
    public static function forUser(?User $user): array
    {
        return [
            'documentEmail' => $user?->role === 'alumni' && self::enabled(self::DOCUMENT_EMAIL),
            // Not alumni-scoped, unlike documentEmail: HR reads this to know
            // whether to offer the Service Request switch in Alumni Control at
            // all, and Super Admin reads it to render its own nav entry. What
            // it means per realm differs; whether it is on does not.
            'serviceRequests' => self::enabled(self::SERVICE_REQUESTS),
        ];
    }
}
