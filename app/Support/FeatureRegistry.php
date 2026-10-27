<?php

namespace App\Support;

use App\Models\User;

/**
 * One declaration per controllable feature — the intended single source of
 * truth for the whole access-control system.
 *
 * Today availability is decided by EIGHT overlapping mechanisms: Spatie
 * permissions, hr_*_disabled_at columns, *_disabled_at columns, the settings
 * table, the Gate::before super-admin short-circuit, ad-hoc in-controller
 * checks, navigation.ts's `capability` field, and router.tsx's `c()` guard.
 * Adding a feature correctly means editing six places, and missing one fails
 * silently — the feature simply is not controllable, with nothing to catch it.
 *
 * This registry is deliberately DESCRIPTIVE first: it records what each
 * feature's control surface is *meant* to be, so the guard tests in
 * tests/Feature/Governance can compare intent against reality and report the
 * gaps. Nothing reads it at runtime yet. Enforcement (a single `feature:`
 * middleware, generated columns, a single /auth/me payload) comes only after
 * the reported gap list is agreed.
 *
 * `column => null` marks a feature that has NO per-account switch today. That
 * is a recorded gap, not an oversight in this file.
 */
class FeatureRegistry
{
    public const REALM_HR = 'hr_admin';

    public const REALM_ALUMNI = 'alumni';

    /**
     * @return array<string, array{
     *     realm: string,
     *     label: string,
     *     column: string|null,
     *     capability: string|null,
     *     permissions: list<string>,
     *     routes: list<string>,
     *     nav: string|null,
     *     togglableBy: string,
     * }>
     */
    public static function all(): array
    {
        return [
            // ---- HR features, switched by Super Admin ---------------------
            'hr.alumni' => [
                'realm' => self::REALM_HR,
                'label' => 'Alumni Control',
                'column' => 'hr_alumni_disabled_at',
                'capability' => 'alumni',
                'permissions' => [
                    'alumni.view', 'alumni.edit', 'alumni.disable', 'alumni.activate',
                    'alumni.lock', 'alumni.unlock', 'alumni.reset_password', 'alumni.delete',
                ],
                'routes' => ['hr/alumni', 'hr/alumni/*'],
                'nav' => '/hr/alumni',
                'togglableBy' => 'super_admin',
            ],
            'hr.alumni_master' => [
                'realm' => self::REALM_HR,
                'label' => 'Alumni Master',
                'column' => 'hr_alumni_master_disabled_at',
                'capability' => 'alumni_master',
                'permissions' => ['alumni_master.view', 'alumni_master.import', 'alumni_master.export', 'alumni_master.manage'],
                'routes' => ['hr/alumni-master', 'hr/alumni-master/*'],
                'nav' => '/hr/alumni-master',
                'togglableBy' => 'super_admin',
            ],
            'hr.documents' => [
                'realm' => self::REALM_HR,
                'label' => 'Documents',
                'column' => 'hr_documents_disabled_at',
                'capability' => 'documents',
                'permissions' => ['documents.view', 'documents.upload', 'documents.replace', 'documents.delete', 'documents.download'],
                'routes' => ['hr/documents', 'hr/documents/*'],
                'nav' => '/hr/documents',
                'togglableBy' => 'super_admin',
            ],
            /*
             * `hr.document_categories` was removed here, not disabled.
             *
             * It answered one question for the entire taxonomy — may HR touch
             * categories at all — and per-category `hr_enabled` now answers a
             * strictly better version of it, per category, which is what the
             * blanket switch could never express: a payslip category and a
             * disciplinary-letter category are not the same grant.
             *
             * Keeping it as a master switch layered on top was the alternative
             * and was rejected: two controls answering "can HR use this
             * category?" is exactly the overlapping-mechanism problem this
             * registry exists to end, and the master switch's remaining scope
             * (create/edit/delete) no longer belongs to HR at all — the
             * taxonomy is Super Admin's.
             *
             * `hr_document_categories_disabled_at` is left on the users table
             * deliberately: dropping a column is the one irreversible step, and
             * nothing reads it now. Remove it in a later migration once this
             * model has been through QA.
             */
            'hr.jobs' => [
                'realm' => self::REALM_HR,
                'label' => 'Jobs',
                'column' => 'hr_jobs_disabled_at',
                'capability' => 'jobs',
                'permissions' => ['jobs.view', 'jobs.create', 'jobs.edit', 'jobs.close', 'jobs.delete'],
                'routes' => ['hr/jobs', 'hr/jobs/*'],
                'nav' => '/hr/jobs',
                'togglableBy' => 'super_admin',
            ],
            'hr.applications' => [
                'realm' => self::REALM_HR,
                'label' => 'Applications',
                'column' => 'hr_applications_disabled_at',
                'capability' => 'applications',
                'permissions' => ['applications.view', 'applications.update_status', 'applications.download_resume'],
                'routes' => ['hr/applications', 'hr/applications/*'],
                'nav' => '/hr/applications',
                'togglableBy' => 'super_admin',
            ],
            'hr.announcements' => [
                'realm' => self::REALM_HR,
                'label' => 'Announcements',
                'column' => 'hr_announcements_disabled_at',
                'capability' => 'announcements',
                'permissions' => ['announcements.create', 'announcements.edit', 'announcements.delete'],
                'routes' => ['hr/announcements', 'hr/announcements/*'],
                // Shares one sidebar entry with the ticker (two tabs, one page).
                'nav' => '/hr/notifications',
                'togglableBy' => 'super_admin',
            ],
            'hr.ticker' => [
                'realm' => self::REALM_HR,
                'label' => 'Alert Ticker',
                'column' => 'hr_ticker_disabled_at',
                'capability' => 'ticker',
                'permissions' => ['ticker.manage'],
                'routes' => ['hr/alerts', 'hr/alerts/*'],
                // A tab inside the Notifications page, not its own sidebar
                // item — that entry belongs to hr.announcements. The page
                // gates each tab separately.
                'nav' => null,
                'togglableBy' => 'super_admin',
            ],
            'hr.dashboard' => [
                'realm' => self::REALM_HR,
                'label' => 'Dashboard',
                'column' => 'hr_dashboard_disabled_at',
                'capability' => 'dashboard',
                'permissions' => ['dashboard.view'],
                'routes' => ['hr/dashboard'],
                'nav' => '/hr/dashboard',
                'togglableBy' => 'super_admin',
            ],
            'hr.settings' => [
                'realm' => self::REALM_HR,
                'label' => 'Settings (read-only)',
                'column' => 'hr_settings_disabled_at',
                'capability' => 'settings',
                'permissions' => ['hr_settings.access'],
                'routes' => ['hr/settings'],
                'nav' => '/hr/settings',
                'togglableBy' => 'super_admin',
            ],
            'hr.notifications' => [
                'realm' => self::REALM_HR,
                'label' => 'Notifications (own inbox)',
                'column' => 'hr_notifications_disabled_at',
                'capability' => 'notifications',
                // Shared endpoint: alumni reach the same routes under their own
                // feature, so the middleware resolves by the caller's realm.
                'permissions' => [],
                'routes' => ['notifications', 'notifications/*'],
                'nav' => null,
                'togglableBy' => 'super_admin',
            ],

            // ---- Alumni features, switched by HR --------------------------
            'alumni.documents' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Document Vault',
                'column' => 'documents_disabled_at',
                'capability' => 'documents',
                'permissions' => ['documents.view'],
                'routes' => ['alumni/documents', 'alumni/documents/*'],
                'nav' => '/alumni/documents',
                'togglableBy' => 'hr_admin',
            ],
            'alumni.downloads' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Document Downloads',
                'column' => 'downloads_disabled_at',
                'capability' => 'downloads',
                'permissions' => ['documents.download'],
                'routes' => ['alumni/documents/*/download'],
                // An in-page action, not its own nav entry.
                'nav' => null,
                'togglableBy' => 'hr_admin',
            ],
            'alumni.jobs' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Job Portal',
                'column' => 'jobs_disabled_at',
                'capability' => 'jobs',
                'permissions' => ['jobs.view'],
                'routes' => ['alumni/jobs'],
                'nav' => '/alumni/jobs',
                'togglableBy' => 'hr_admin',
            ],
            'alumni.applications' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Job Applications',
                'column' => 'applications_disabled_at',
                'capability' => 'applications',
                'permissions' => ['jobs.apply', 'applications.view'],
                'routes' => ['alumni/applications', 'alumni/jobs/*/apply'],
                // A tab inside the Job Portal page.
                'nav' => null,
                'togglableBy' => 'hr_admin',
            ],
            'alumni.profile' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'My Information',
                'column' => 'profile_disabled_at',
                'capability' => 'profile',
                'permissions' => ['profile.view', 'profile.edit'],
                'routes' => ['alumni/profile', 'alumni/profile/*', 'account/avatar'],
                'nav' => '/alumni/profile',
                'togglableBy' => 'hr_admin',
            ],
            'alumni.announcements' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Announcements',
                'column' => 'announcements_disabled_at',
                'capability' => 'announcements',
                'permissions' => ['announcements.view'],
                'routes' => ['alumni/announcements'],
                'nav' => '/alumni/announcements',
                'togglableBy' => 'hr_admin',
            ],
            'alumni.notifications' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Notifications',
                'column' => 'notifications_disabled_at',
                'capability' => 'notifications',
                'permissions' => ['notifications.view'],
                'routes' => ['notifications', 'notifications/*'],
                'nav' => '/alumni/notifications',
                'togglableBy' => 'hr_admin',
            ],
            'alumni.ticker' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Alert Ticker',
                'column' => 'ticker_disabled_at',
                'capability' => 'ticker',
                'permissions' => [],
                'routes' => ['alerts/active'],
                'nav' => null,
                'togglableBy' => 'hr_admin',
            ],
            'alumni.dashboard' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Dashboard',
                'column' => 'dashboard_disabled_at',
                'capability' => 'dashboard',
                'permissions' => ['dashboard.view'],
                'routes' => ['alumni/dashboard'],
                'nav' => '/alumni/dashboard',
                'togglableBy' => 'hr_admin',
            ],

            /*
             * Service Request — three real tiers over a placeholder page.
             *
             *   1. platformFlag  Super Admin decides whether this deployment
             *                    offers the feature to HR at all (off by
             *                    default).
             *   2. column        HR flips it per alumnus in Alumni Control.
             *   3. permissions   the role must hold service_request.view.
             *
             * All three must pass; forUser() ANDs them into the one `features`
             * entry the sidebar and router read, so the switches genuinely
             * control what an alumnus sees.
             *
             * `uiOnly` records that there is deliberately no endpoint yet — the
             * page is still "coming soon", so only VISIBILITY is governed. It
             * exempts this from the guard's "switch exists but no route
             * enforces it" finding, and from nothing else: it still needs a
             * switch and still needs frontend gating. Delete the flag and fill
             * in `routes` the moment the feature grows a backend.
             */
            'alumni.service_requests' => [
                'realm' => self::REALM_ALUMNI,
                'label' => 'Service Request',
                'column' => 'service_requests_disabled_at',
                'capability' => 'service_requests',
                'permissions' => ['service_request.view'],
                'platformFlag' => PlatformFeatures::SERVICE_REQUESTS,
                'routes' => [],
                'uiOnly' => true,
                'nav' => '/alumni/service-request',
                'togglableBy' => 'hr_admin',
            ],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public static function forRealm(string $realm): array
    {
        return array_filter(self::all(), fn (array $f) => $f['realm'] === $realm);
    }

    /**
     * Route URI patterns that are legitimately outside the registry: session
     * plumbing and self-service that no administrator should be able to switch
     * off. Listing them here is what lets the coverage test treat every other
     * ungated route as a finding.
     *
     * @return list<string>
     */
    public static function exemptRoutes(): array
    {
        return [
            'api/v1/*/auth/me',        // identity — needed to discover your own access
            'api/v1/*/auth/logout',    // must always be possible
            'api/v1/account/password', // locking someone out of rotating their own password is a regression
            // Read-only, and controlled per ROW rather than per route: the
            // response contains only the categories Super Admin has granted
            // this realm (document_categories.hr_enabled). A registry feature
            // would be a second, coarser answer to a question the row filter
            // already answers precisely.
            'api/v1/hr/document-categories',
        ];
    }

    /**
     * Frontend paths that are intentionally outside the registry: static
     * placeholders for features that do not exist yet.
     *
     * These have no route, no permission and no switch, in any realm, and that
     * is the point. Service Request previously had both a permission and a
     * switch while having no backend at all, so an administrator could grant
     * access that did nothing and "switch off" a feature that was never on —
     * the same ghost-permission shape that was cleared out of the catalog.
     *
     * A placeholder earns a registry entry when it earns a backend, not
     * before. Until then it is listed here so the guard tests can tell
     * "deliberately ungoverned" from "someone forgot".
     *
     * @return list<string>
     */
    public static function uiOnlyRoutes(): array
    {
        return [
            // Service Request has moved OUT of this list: it is now a declared
            // registry feature with a real three-tier gate, so Guard 3 governs
            // it and Guard 4 must not demand that its nav item be ungated.
            //
            // The HR and Super Admin Service Request pages remain ungated by a
            // registry key — Super Admin sits above every switch, and HR's view
            // is gated on the platform flag in navigation.ts rather than on a
            // per-HR switch — so they are listed here, not in the registry.
            '/hr/service-request',
            '/super-admin/service-request',
        ];
    }

    /** Columns the registry expects to exist, for cross-checking against User. */
    public static function declaredColumns(): array
    {
        return array_values(array_filter(array_column(self::all(), 'column')));
    }

    /**
     * The feature map for one user, as the frontend consumes it.
     *
     * Keyed by registry key (`hr.documents`, `alumni.jobs`), true when the user
     * both holds a required permission and has not had the feature switched
     * off. This is the single payload that replaced the separate
     * permissions/capabilities/features triple the client had to reconcile
     * itself — and reconciling it in three places is how the UI and the server
     * ended up disagreeing.
     *
     * Super Admin sees every feature as available: nothing sits above it, and
     * Gate::before already grants it every permission.
     *
     * @return array<string, bool>
     */
    public static function forUser(?User $user): array
    {
        if (! $user) {
            return [];
        }

        $map = [];

        foreach (self::all() as $key => $feature) {
            if ($user->role === 'super_admin') {
                $map[$key] = true;

                continue;
            }

            if ($user->role !== $feature['realm']) {
                continue; // not this user's realm — omitted rather than false
            }

            $permitted = ! $feature['permissions']
                || collect($feature['permissions'])->contains(fn (string $p) => $user->can($p));

            $switchedOn = $feature['column'] === null || $user->{$feature['column']} === null;

            // Optional third tier: a deployment-wide flag above the per-person
            // switch. Off by default, so a feature declaring one stays hidden
            // until a Super Admin turns it on for the whole platform.
            $platformOn = ! isset($feature['platformFlag'])
                || PlatformFeatures::enabled($feature['platformFlag']);

            $map[$key] = $permitted && $switchedOn && $platformOn;
        }

        return $map;
    }

    /** Features with no per-account switch — the control gaps, named. */
    public static function withoutSwitch(): array
    {
        return array_keys(array_filter(self::all(), fn (array $f) => $f['column'] === null));
    }

    /** Sanity check: every declared column really exists on User. */
    public static function undeclaredColumns(): array
    {
        return array_values(array_diff(self::declaredColumns(), User::capabilityColumns()));
    }
}
