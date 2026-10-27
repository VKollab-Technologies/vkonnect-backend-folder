<?php

namespace App\Support;

/**
 * Canonical, granular permission catalog (enterprise RBAC).
 *
 * This is the single source of truth for every permission in VKONNECT. Each
 * entry is [key, label, description, assignableRoles]. Permissions are seeded
 * into the Spatie `permissions` table from here, grouped by category for the
 * Super Admin Permission Center, and assigned per-role / per-user (direct).
 *
 * Adding a permission here + seeding makes it available everywhere — nothing is
 * hardcoded in the frontend.
 */
class Permissions
{
    /** category => [ [key, label, description, [roles...]], ... ] */
    public const CATALOG = [
        'Alumni Management' => [
            ['alumni.view', 'View Alumni', 'See the alumni directory and profiles', ['hr_admin']],
            // No approve/reject here: the manual-approval "pending" state was
            // retired, so those permissions gated nothing while still being
            // grantable — an admin could switch them on and see no effect.
            ['alumni.edit', 'Edit Alumni', 'Edit alumni profile details', ['hr_admin']],
            ['alumni.disable', 'Disable Alumni', 'Disable an alumni account', ['hr_admin', 'super_admin']],
            ['alumni.activate', 'Activate Alumni', 'Re-activate a disabled account', ['hr_admin', 'super_admin']],
            ['alumni.delete', 'Delete Alumni', 'Permanently remove an alumni account', ['hr_admin', 'super_admin']],
            ['alumni.lock', 'Lock Alumni Account', 'Temporarily lock an account', ['hr_admin', 'super_admin']],
            ['alumni.unlock', 'Unlock Alumni Account', 'Remove an account lock', ['hr_admin', 'super_admin']],
            ['alumni.reset_password', 'Reset Alumni Password', 'Trigger a password reset', ['hr_admin', 'super_admin']],
            ['alumni.disable_downloads', 'Disable Alumni Downloads', 'Block document downloads for an alumnus', ['hr_admin']],
            ['alumni.disable_documents', 'Disable Alumni Document Access', 'Block document vault access for an alumnus', ['hr_admin']],
            ['alumni.disable_jobs', 'Disable Alumni Job Access', 'Block job portal access for an alumnus', ['hr_admin']],
            ['alumni.disable_applications', 'Disable Alumni Job Applications', 'Block job applications for an alumnus', ['hr_admin']],
            ['alumni.disable_notifications', 'Disable Alumni Notifications', 'Block notifications for an alumnus', ['hr_admin']],
            ['alumni.disable_ticker', 'Disable Alumni Alert Ticker', 'Hide the alert ticker for an alumnus', ['hr_admin']],
            ['alumni.disable_profile', 'Disable Alumni Profile Access', 'Block "My Information" profile access for an alumnus', ['hr_admin']],
            ['alumni.disable_announcements', 'Disable Alumni Announcements', 'Block the announcements feed for an alumnus', ['hr_admin']],
            ['alumni.disable_dashboard', 'Disable Alumni Dashboard', 'Block the alumni dashboard for an alumnus', ['hr_admin']],
            ['alumni.disable_service_requests', 'Disable Alumni Service Requests', 'Hide the Service Request section for an alumnus', ['hr_admin']],
            ['alumni_master.view', 'View Alumni Master', 'View the imported alumni master roster', ['hr_admin']],
            ['alumni_master.import', 'Import Alumni Master', 'Bulk-upload alumni records from Excel/CSV', ['hr_admin']],
            ['alumni_master.manage', 'Manage Alumni Master', 'Add, edit and delete individual alumni records', ['hr_admin']],
            ['alumni_master.export', 'Export Alumni Master', 'Export the alumni master roster to file', ['hr_admin']],
        ],
        'Documents' => [
            ['documents.view', 'View Documents', 'View documents and metadata', ['hr_admin', 'alumni']],
            ['documents.upload', 'Upload Documents', 'Upload new documents', ['hr_admin']],
            ['documents.replace', 'Replace Documents', 'Replace an existing document', ['hr_admin']],
            ['documents.delete', 'Delete Documents', 'Delete documents', ['hr_admin']],
            ['documents.download', 'Download Documents', 'Securely download documents', ['hr_admin', 'alumni']],
            ['document_categories.manage', 'Manage Document Categories', 'Add, edit and delete document categories', ['hr_admin']],
        ],
        'Job Management' => [
            ['jobs.view', 'View Jobs', 'Browse job postings', ['hr_admin', 'alumni']],
            ['jobs.create', 'Create Job', 'Post a new job', ['hr_admin']],
            ['jobs.edit', 'Edit Job', 'Edit an existing job', ['hr_admin']],
            ['jobs.delete', 'Delete Job', 'Delete a job posting', ['hr_admin']],
            ['jobs.close', 'Close Job', 'Open or close a job posting', ['hr_admin']],
            ['jobs.apply', 'Apply to Jobs', 'Submit a job application', ['alumni']],
        ],
        'Application Management' => [
            ['applications.view', 'View Applications', 'View job applications', ['hr_admin', 'alumni']],
            ['applications.update_status', 'Update Application Status', 'Progress an application', ['hr_admin']],
            ['applications.download_resume', 'Download Resume', 'Download applicant resumes', ['hr_admin']],
        ],
        'Announcements & Notifications' => [
            ['announcements.create', 'Create Announcement', 'Publish a new announcement', ['hr_admin']],
            ['announcements.edit', 'Edit Announcement', 'Edit or publish announcements', ['hr_admin']],
            ['announcements.delete', 'Delete Announcement', 'Delete announcements', ['hr_admin']],
            // Sending to alumni is `announcements.create` — there is no
            // separate send endpoint, so a `notifications.send` permission
            // could only ever be decorative.
            ['ticker.manage', 'Manage Alert Ticker', 'Control the live alert ticker', ['hr_admin']],
        ],
        'Reports & System' => [
            ['dashboard.view', 'View Dashboard', 'View the portal dashboard', ['hr_admin', 'alumni', 'super_admin']],
            ['reports.view', 'View Reports', 'View analytics and reports', ['hr_admin']],
            ['reports.export', 'Export Reports', 'Export reports to file', ['hr_admin']],
            // Platform settings are Super Admin-only and gated by role, not by
            // a permission: a grantable `settings.manage` implied HR could be
            // given access that no endpoint would ever honour.
            ['hr_settings.access', 'HR Settings Access', 'View the HR-facing Settings page (granted individually by Super Admin)', ['hr_admin']],
            ['hr_admins.manage', 'Manage HR Admins', 'Provision and manage HR admins', ['hr_admin', 'super_admin']],
            ['super_admins.manage', 'Manage Super Admin Account', 'View and update the platform Super Admin account', ['super_admin']],
            ['content.manage', 'Manage Site Content', 'Edit public content blocks (Terms, Privacy, footer)', ['super_admin']],
            ['permissions.manage', 'Manage Permissions', 'Change role-level and per-user permission grants', ['super_admin']],
        ],
        'Profile' => [
            ['profile.view', 'View My Information', 'See own HR-managed profile details', ['alumni']],
            ['profile.edit', 'Edit Profile', 'Change own profile photo (informational fields are HR-managed only)', ['alumni']],
        ],
        /*
         * Alumni-facing features that previously had no permission key at all,
         * so a Super Admin could not turn them off for the alumni role even
         * though every other feature was configurable. Alumni Control's
         * per-person switches still apply on top of these.
         */
        'Alumni Features' => [
            ['announcements.view', 'View Announcements', 'Read the announcements archive', ['alumni']],
            ['notifications.view', 'View Notifications', 'See the notification inbox and bell', ['alumni']],
            // Tier 3 of Service Request's gate. It has no endpoint yet — the
            // page is still a "coming soon" placeholder — but it is genuinely
            // enforced: FeatureRegistry::forUser() folds it into the `features`
            // map the sidebar and router read, so revoking it removes the item.
            // Not a ghost permission; see FeatureRegistry's alumni.service_requests.
            ['service_request.view', 'View Service Requests', 'See the Service Request section', ['alumni']],
            // CLIENT-ONLY BY DESIGN — the sole permission in this catalog with
            // no server-side enforcement, and deliberately so: preferences
            // (theme, reduced motion, bell/ticker visibility) live in the
            // browser's uiStore and never reach the API, so there is no
            // endpoint to gate. Revoking it hides the Preferences page; it
            // cannot leak data, because no data is involved.
            ['preferences.manage', 'Manage Preferences', 'Change own display and notification preferences', ['alumni', 'hr_admin']],
        ],
    ];

    /** All permission keys (flat). */
    public static function all(): array
    {
        $keys = [];
        foreach (self::CATALOG as $perms) {
            foreach ($perms as [$key]) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /** Keys assignable to a given role. */
    public static function assignableFor(string $role): array
    {
        $keys = [];
        foreach (self::CATALOG as $perms) {
            foreach ($perms as [$key, , , $roles]) {
                if (in_array($role, $roles, true)) {
                    $keys[] = $key;
                }
            }
        }

        return $keys;
    }

    /** Permissions that are assignable but OFF by default — must be explicitly granted. */
    private const OPT_IN_ONLY = [
        'hr_settings.access',
    ];

    /** Default-enabled permissions for a role (all assignable except opt-in-only, on by default). */
    public static function defaultsFor(string $role): array
    {
        return array_values(array_diff(self::assignableFor($role), self::OPT_IN_ONLY));
    }

    /** Grouped catalog (category => [ {key,label,description}, ... ]) for a role. */
    public static function groupedFor(string $role): array
    {
        $out = [];
        foreach (self::CATALOG as $category => $perms) {
            $items = [];
            foreach ($perms as [$key, $label, $description, $roles]) {
                if (in_array($role, $roles, true)) {
                    $items[] = ['name' => $key, 'label' => $label, 'description' => $description];
                }
            }
            if ($items) {
                $out[] = ['category' => $category, 'permissions' => $items];
            }
        }

        return $out;
    }
}
