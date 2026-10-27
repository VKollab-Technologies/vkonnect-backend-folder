<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\AlumniMaster;
use App\Models\ContentBlock;
use App\Models\HrAdmin;
use App\Models\Setting;
use App\Models\User;
use App\Support\Permissions as PermissionCatalog;
use Illuminate\Database\Seeder;
use RuntimeException;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed demo data.
     *
     * Hard-stops on production. This seeder creates administrator accounts,
     * and `db:seed` is one mistyped command away from any deploy shell — the
     * guard is here rather than in a runbook because a runbook cannot stop a
     * typo. Roles and permissions are seeded first and unconditionally, since
     * those are structural and safe; only the demo *accounts* are gated.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException(
                'DatabaseSeeder must never run against production. It creates demo '
                .'administrator accounts. Run permissions:sync instead.'
            );
        }

        $this->seedRolesAndPermissions();
        $this->seedSuperAdmin();
        $this->seedHrAdmins();
        $this->seedAlumniMaster();
        $this->seedSettings();
        $this->seedContent();
        $this->seedAlerts();
    }

    /** Seed a few live ticker alerts so the ticker is populated out of the box. */
    private function seedAlerts(): void
    {
        $hr = User::where('vkollab_id', 'HR1001')->first();
        // Kept deliberately generic and always-true. An earlier seed claimed
        // "Form 16 for FY 2024-2025 is available in your document vault",
        // which was shown to every alumnus regardless of whether that document
        // actually existed for them — a false statement in the product.
        $alerts = [
            ['Welcome to VKONNECT — keep your profile up to date for better job matches.', 'info', 'all'],
            ['New SAP roles are posted regularly — check the Job Portal for openings.', 'success', 'alumni'],
            ['Your employment documents are available anytime under My Documents.', 'info', 'alumni'],
        ];
        foreach ($alerts as [$message, $level, $audience]) {
            Alert::firstOrCreate(['message' => $message], [
                'level' => $level,
                'audience' => $audience,
                'is_active' => true,
                'created_by' => $hr?->id,
            ]);
        }
    }

    /**
     * Seed editable public content blocks. Bodies use lightweight markup: lines
     * starting with "## " are sub-headings; blank lines separate paragraphs.
     * firstOrCreate keeps any Super-Admin edits intact on re-seed.
     */
    private function seedContent(): void
    {
        $terms = <<<'TXT'
        ## 1. Acceptance of Terms
        By accessing or using VKONNECT (the "Platform"), operated by VKollab Technologies, you agree to be bound by these Terms & Conditions. If you do not agree, please do not use the Platform.

        ## 2. Eligibility & Accounts
        Access is limited to authorized alumni, HR administrators, and platform administrators. You are responsible for maintaining the confidentiality of your credentials and for all activity under your account. One-time passwords (OTP) sent to your registered email are required to sign in.

        ## 3. Acceptable Use
        You agree to use the Platform only for lawful purposes. You may not attempt to access data that is not yours, disrupt the service, or misuse documents, job postings, or communications made available to you.

        ## 4. Documents & Data
        Sensitive documents (such as PAN, Form 16, and payslips) are stored privately and made available to you through secure, time-limited links. You agree not to share these links or documents with unauthorized parties.

        ## 5. Privacy
        We process your personal information to operate the Platform, including profile details, documents, job applications, and activity records. We apply reasonable safeguards and retain data only as long as necessary.

        ## 6. Communications
        By registering, you consent to receive transactional emails (OTP, password resets, approvals) and organizational announcements relevant to your role.

        ## 7. Termination
        VKollab Technologies may suspend or disable accounts that violate these terms or where required for security or compliance reasons.

        ## 8. Changes to These Terms
        We may update these Terms from time to time. Continued use of the Platform after changes take effect constitutes acceptance of the revised Terms.

        ## 9. Contact
        Questions about these Terms can be directed to alumni@vkollab.com.
        TXT;

        $privacy = <<<'TXT'
        ## 1. Information We Collect
        We collect information you provide when registering and using VKONNECT — your name, email, EMP ID, profile details, uploaded documents, and job applications — along with activity and login records generated as you use the Platform.

        ## 2. How We Use Your Information
        Your information is used to operate the Platform: to authenticate you, deliver documents securely, process job applications, send transactional emails and announcements, and maintain audit and security logs.

        ## 3. Document Security
        Sensitive documents are stored on private storage and are never publicly accessible. They are delivered only through short-lived, single-use signed links after an ownership check.

        ## 4. Data Sharing
        We do not sell your personal information. Information is accessible only to authorized HR administrators and platform administrators for legitimate operational purposes.

        ## 5. Data Retention
        We retain your information for as long as your account is active or as needed to provide services, comply with legal obligations, resolve disputes, and enforce agreements.

        ## 6. Your Rights
        You may review and update your profile information at any time. For requests relating to your personal data, contact us at alumni@vkollab.com.

        ## 7. Changes to This Policy
        We may update this Privacy Policy periodically. Material changes will be reflected here with an updated revision date.

        ## 8. Contact
        For privacy questions, contact alumni@vkollab.com.
        TXT;

        $landingAbout = "VKONNECT is VKollab Technologies' enterprise alumni engagement platform — built to formalize and sustain the organization's relationship with its alumni community for the long term. It brings together verified alumni identity, professional networking, career opportunities, and secure document access in a single governed environment, so the relationship an alumnus built with VKollab continues to grow long after their tenure.";

        $blocks = [
            ['terms', 'Terms & Conditions', $terms],
            ['privacy', 'Privacy Policy', $privacy],
            ['landing_about', 'The trusted enterprise home for VKollab alumni.', $landingAbout],
            ['landing_hero', 'Your Career at VKollab Doesn\'t End Here', 'VKONNECT is the trusted enterprise platform connecting VKollab Technologies alumni for life — lifelong professional networking, career growth opportunities, secure access to your employment documents, and priority visibility into new roles across the organization and its partner network.'],
            ['landing_cta', 'Your VKollab alumni identity, ready when you are.', 'Join VKONNECT to unlock lifelong networking, career opportunities, and secure access to your documents — all in one trusted enterprise platform.'],
        ];
        foreach ($blocks as [$key, $title, $body]) {
            ContentBlock::firstOrCreate(['key' => $key], ['title' => $title, 'body' => $body]);
        }
    }

    private function seedSettings(): void
    {
        // firstOrCreate keeps existing (admin-edited) values intact on re-seed.
        $defaults = [
            ['platform_name', 'VKONNECT', 'string', 'Platform name', 'general'],
            ['allow_registration', '1', 'boolean', 'Allow alumni self-registration', 'access'],
            // Off by default — emailing a link to employment documents should
            // be a deliberate choice, not a default. See the matching
            // migration, which back-fills the row on existing installs.
            // group 'documents' — surfaced on the Documents page, not in
            // Platform Settings (SettingsPage skips this group).
            ['alumni_document_email_enabled', '0', 'boolean', 'Let alumni email their own documents to themselves', 'documents'],
            ['email_broadcasts_enabled', '0', 'boolean', 'Email alumni/HR on new jobs & announcements', 'general'],
            // Contact Information (editable by Super Admin)
            ['support_email', 'alumni@vkollab.com', 'string', 'Support email', 'contact'],
            ['footer_address', 'VKollab Technologies, India', 'string', 'Address', 'contact'],
            ['footer_phone', '+91 80 4567 8900', 'string', 'Support phone number', 'contact'],
            ['footer_map_url', 'https://www.google.com/maps/search/?api=1&query=VKollab+Technologies%2C+India', 'string', 'Google Maps URL', 'contact'],
            // Public footer content (editable by Super Admin)
            ['footer_about', 'The alumni network by VKollab Technologies — securely connecting professionals, careers, and opportunities across the organization.', 'string', 'Footer about text', 'footer'],
            ['social_linkedin', 'https://www.linkedin.com/company/vkollab', 'string', 'LinkedIn URL', 'footer'],
            ['social_youtube', 'https://www.youtube.com/@vkollab', 'string', 'YouTube URL', 'footer'],
            ['social_instagram', 'https://www.instagram.com/vkollab', 'string', 'Instagram URL', 'footer'],
            ['footer_copyright_holder', 'VKollab Technologies', 'string', 'Copyright holder name', 'footer'],
            // Landing page copy (Hero eyebrow/CTA labels, About pillars) —
            // the rest of the landing page's copy (headline/subtext) lives in
            // ContentBlock rows 'landing_hero'/'landing_cta' above, alongside 'landing_about'.
            ['landing_hero_eyebrow', 'VKollab Alumni Network', 'string', 'Hero eyebrow badge text', 'landing'],
            ['landing_cta_primary_label', 'Join VKONNECT', 'string', 'Primary button label (Hero + CTA sections)', 'landing'],
            ['landing_cta_secondary_label', 'Member Login', 'string', 'Secondary button label (Hero + CTA sections)', 'landing'],
            ['landing_about_pillar_1', 'Lifelong alumni engagement, backed by a verified professional identity', 'string', 'About pillar 1', 'landing'],
            ['landing_about_pillar_2', 'Structured professional networking with colleagues across every batch and function', 'string', 'About pillar 2', 'landing'],
            ['landing_about_pillar_3', 'Priority access to career growth opportunities within the organization and its partner network', 'string', 'About pillar 3', 'landing'],
            ['landing_about_pillar_4', 'Secure, self-service access to your employment documents and records', 'string', 'About pillar 4', 'landing'],
        ];

        foreach ($defaults as [$key, $value, $type, $label, $group]) {
            Setting::firstOrCreate(['key' => $key], [
                'value' => $value,
                'type' => $type,
                'label' => $label,
                'group' => $group,
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed the authoritative Alumni Master roster. Existing demo alumni get a
     * matching, already-claimed record; a handful of UNCLAIMED records (with a
     * known PAN) are added so registration-by-PAN/Employee-ID can be tested.
     */
    private function seedAlumniMaster(): void
    {
        $departments = ['SAP Technical', 'SAP Functional', 'SAP BASIS', 'SAP Analytics', 'SAP Delivery'];

        // Mirror every seeded alumnus into the master, marked claimed.
        $alumni = User::where('role', 'alumni')->with('alumniProfile')->get();
        foreach ($alumni as $i => $user) {
            $empId = $user->alumniProfile?->vkollab_id ?? $user->vkollab_id;
            if (! $empId) {
                continue;
            }
            AlumniMaster::firstOrCreate(
                ['vkollab_id' => strtoupper($empId)],
                [
                    'pan' => $this->fakePan($i + 1),
                    'name' => $user->name,
                    'email' => $user->email,
                    'designation' => $user->alumniProfile?->designation,
                    'department' => $user->alumniProfile?->department ?? $departments[$i % count($departments)],
                    'organization' => 'VKollab Technologies',
                    'status' => 'active',
                    'joined_on' => now()->subYears(random_int(2, 8))->startOfYear(),
                    'claimed_by' => $user->id,
                    'claimed_at' => $user->created_at,
                ]
            );
        }

        // Unclaimed roster rows — eligible to register. ALM3001 has a known PAN
        // (ABCDE1234F) for deterministic registration testing.
        // VKollab is an SAP consultancy, so every seeded role is an SAP one —
        // generic "Product Manager"/"UX Designer" placeholders did not reflect
        // the business.
        $eligible = [
            ['ALM3001', 'ABCDE1234F', 'Asha Rao', 'asha.rao@example.com', 'SAP ABAP Developer', 'SAP Technical'],
            ['ALM3002', 'PQRSX6789K', 'Vikram Nair', 'vikram.nair@example.com', 'SAP FICO Consultant', 'SAP Functional'],
            ['ALM3003', 'LMNOP4321J', 'Sara Iqbal', 'sara.iqbal@example.com', 'SAP MM Consultant', 'SAP Functional'],
            ['ALM3004', 'GHIJK1357M', 'Rohit Verma', 'rohit.verma@example.com', 'SAP BASIS Administrator', 'SAP Technical'],
            ['ALM3005', 'WXYZA2468N', 'Neha Gupta', 'neha.gupta@example.com', 'SAP SD Consultant', 'SAP Functional'],
        ];
        foreach ($eligible as [$empId, $pan, $name, $email, $designation, $dept]) {
            AlumniMaster::firstOrCreate(
                ['vkollab_id' => $empId],
                [
                    'pan' => $pan,
                    'name' => $name,
                    'email' => $email,
                    'designation' => $designation,
                    'department' => $dept,
                    'organization' => 'VKollab Technologies',
                    'status' => 'active',
                    'joined_on' => now()->subYears(random_int(2, 6))->startOfYear(),
                ]
            );
        }
    }

    /** Deterministic, format-valid placeholder PAN (ABCDE1234F). */
    private function fakePan(int $n): string
    {
        $letters = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $l = fn ($x) => $letters[$x % 26];
        $five = $l($n).$l($n + 3).$l($n + 7).$l($n + 11).$l($n + 17);
        $four = str_pad((string) (($n * 1373) % 10000), 4, '0', STR_PAD_LEFT);

        return $five.$four.$l($n + 5);
    }

    private function seedRolesAndPermissions(): void
    {
        // Seed every permission from the granular catalog (DB-driven RBAC).
        foreach (PermissionCatalog::all() as $name) {
            Permission::findOrCreate($name);
        }

        // Roles are realm markers only. Capabilities are assigned per-user
        // (directly), so the Super Admin can tune any individual's access.
        // hr_admin and alumni roles intentionally hold NO permissions; the
        // single super_admin passes every check via Gate::before.
        Role::findOrCreate('super_admin');
        Role::findOrCreate('hr_admin');
        Role::findOrCreate('alumni');

        // Role-level default toggles (drive the Permissions page switches).
        foreach (PermissionCatalog::defaultsFor("hr_admin") as $p) {
            \App\Models\RolePermissionDefault::firstOrCreate(['role' => 'hr_admin', 'permission' => $p]);
        }
        foreach (PermissionCatalog::defaultsFor("alumni") as $p) {
            \App\Models\RolePermissionDefault::firstOrCreate(['role' => 'alumni', 'permission' => $p]);
        }
    }

    /**
     * Read a seed password from config, refusing to invent one.
     *
     * Read via config() rather than env() because `env()` returns NULL once
     * `config:cache` has run. The previous version used env() WITH a literal
     * default, so on any config-cached environment it silently seeded a
     * hardcoded, publicly-known password — the failure mode was invisible and
     * happened exactly where it mattered most.
     *
     * There is no default now. An unset password is a hard stop, not a
     * fallback: refusing to seed is always safer than seeding a guessable
     * administrator.
     */
    private function requirePassword(string $configKey, string $envName): string
    {
        $password = config("app.{$configKey}");

        if (! is_string($password) || $password === '') {
            throw new RuntimeException(
                "{$envName} is not set. Set it in .env before seeding — this seeder "
                .'will not fall back to a default password.'
            );
        }

        return $password;
    }

    private function seedSuperAdmin(): void
    {
        // Demo Super Admin — the single platform administrator. Keyed on the
        // unique vkollab_id (email is no longer unique).
        $password = $this->requirePassword('seed_admin_password', 'STAGING_ADMIN_PASSWORD');

        $user = User::firstOrCreate(
            ['vkollab_id' => 'SA1001'],
            [
                'name' => 'Super Administrator',
                'email' => 'kgsmanikanta1010@gmail.com',
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );
        $user->syncRoles('super_admin');
    }

    private function seedHrAdmins(): void
    {
        // Demo HR Admin — the single HR administrator account.
        $password = $this->requirePassword('seed_hr_password', 'STAGING_HR_PASSWORD');

        $user = User::firstOrCreate(
            ['vkollab_id' => 'HR1001'],
            [
                'name' => 'HR Administrator',
                'email' => 'geethasaimanikanta.k@gmail.com',
                'password' => Hash::make($password),
                'role' => 'hr_admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );
        $user->syncRoles('hr_admin');
        $user->syncPermissions(PermissionCatalog::defaultsFor("hr_admin")); // direct, per-user
        HrAdmin::firstOrCreate(
            ['user_id' => $user->id],
            ['vkollab_id' => 'HR1001', 'department' => 'Human Resources']
        );
    }
}
