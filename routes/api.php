<?php

use App\Http\Controllers\Api\V1\Alumni\AnnouncementController as AlumniAnnouncementController;
use App\Http\Controllers\Api\V1\Alumni\ApplicationController as AlumniApplicationController;
use App\Http\Controllers\Api\V1\Alumni\DashboardController as AlumniDashboard;
use App\Http\Controllers\Api\V1\Alumni\DocumentController as AlumniDocumentController;
use App\Http\Controllers\Api\V1\Alumni\JobController as AlumniJobController;
use App\Http\Controllers\Api\V1\Alumni\ProfileController as AlumniProfileController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PublicController;
use App\Http\Controllers\Api\V1\SuperAdmin\ContentController;
use App\Http\Controllers\Api\V1\Hr\AnnouncementController;
use App\Http\Controllers\Api\V1\Hr\DashboardController as HrDashboard;
use App\Http\Controllers\Api\V1\Hr\JobController;
use App\Http\Controllers\Api\V1\SuperAdmin\AlumniController;
use App\Http\Controllers\Api\V1\SuperAdmin\AlumniMasterController;
use App\Http\Controllers\Api\V1\SuperAdmin\AuditLogController;
use App\Http\Controllers\Api\V1\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\Api\V1\SuperAdmin\HrAdminController;
use App\Http\Controllers\Api\V1\SuperAdmin\PermissionController;
use App\Http\Controllers\Api\V1\SuperAdmin\SettingsController;
use App\Http\Controllers\Api\V1\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Api\V1\SuperAdmin\UserPermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| VKONNECT API — version 1
|--------------------------------------------------------------------------
| Three isolated authentication realms (super_admin | hr_admin | alumni),
| each with its own login namespace and role-guarded route group.
| See docs/03-api-contract.md.
*/

Route::prefix('v1')->group(function () {

    // Deep health check (DB/cache/queue connectivity) for load balancers and
    // uptime monitors — separate from the framework's built-in /up liveness check.
    //
    // Throttled generously (120/min) rather than tightly: this endpoint decides
    // whether the app stays in rotation, so a 429 here would read as "unhealthy"
    // and pull a working instance out of service. 120/min is far above any
    // plausible monitor cadence while still bounding abuse — it touches DB,
    // cache and queue, so it is the most expensive public GET we have.
    Route::get('health', HealthController::class)->middleware('throttle:120,1');

    // ---- Public auth (per realm) ------------------------------------------
    // Two-step sign-in: login verifies the password and emails an OTP;
    // verify-otp checks the code and issues the token. Realm is a route default.
    foreach (['super-admin' => 'super_admin', 'hr' => 'hr_admin', 'alumni' => 'alumni'] as $prefix => $realm) {
        // Both raised from 6/min, which was blocking legitimate retries in a
        // way that read as an outage. Admins get the higher ceiling (20 vs 10)
        // because several of them share one office IP, and Laravel keys guest
        // throttles by IP — so their attempts pool into a single bucket.
        //
        // Per-account lockout still bounds guessing against any one account
        // underneath: alumni 5 fails -> 15 min, admins 15 -> 30 min.
        Route::post("{$prefix}/auth/login", [AuthController::class, 'login'])
            ->defaults('realm', $realm)
            ->middleware($realm === 'alumni' ? 'throttle.auth:10,1' : 'throttle.auth:20,1');

        Route::post("{$prefix}/auth/verify-otp", [AuthController::class, 'verifyOtp'])
            ->defaults('realm', $realm)->middleware('throttle.auth:6,1');
        Route::post("{$prefix}/auth/resend-otp", [AuthController::class, 'resendOtp'])
            ->middleware('throttle.auth:3,1');
    }

    // Public, unauthenticated site content (footer config + Terms/Privacy).
    //
    // 60/min: these are hit on every landing-page load and on opening the legal
    // modals, so the limit has to clear normal browsing (a visitor clicking
    // around might reasonably fire a handful a minute) while stopping the pair
    // being used as a free amplification target. Read-only and cacheable, so a
    // higher ceiling than the auth endpoints is appropriate.
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('public/site', [PublicController::class, 'site']);
        Route::get('public/content/{key}', [PublicController::class, 'content']);
    });

    // Alumni account activation (NOT open signup) — validates an existing roster
    // record by Employee ID / PAN, then OTP → password. Rate-limited per step.
    Route::post('alumni/auth/register/verify-identity', [RegisterController::class, 
'verifyIdentity'])->middleware('throttle.auth:30
,1');
    Route::post('alumni/auth/register/verify-otp', [RegisterController::class, 'verifyOtp'])->middleware('throttle.auth:6,1');
    Route::post('alumni/auth/register/resend-otp', [RegisterController::class, 'resendOtp'])->middleware('throttle.auth:3,1');
    Route::post('alumni/auth/register/set-password', [RegisterController::class, 'setPassword'])->middleware('throttle.auth:6,1');

    // OTP-based password recovery.
    Route::post('auth/forgot-password', [PasswordController::class, 'forgot'])->middleware('throttle.auth:3,1');
    Route::post('auth/reset-password', [PasswordController::class, 'reset'])->middleware('throttle.auth:3,1');

    // Shared notifications + live alert ticker (any authenticated realm).
    Route::middleware(['auth:sanctum', 'extend.token'])->group(function () {
        // `notifications.view` is an ALUMNI-only permission, but this group
        // serves all three realms — a bare `permission:` would 403 every HR
        // admin and Super Admin, who can never hold it. role_or_permission
        // lets the admin realms through by role while holding alumni to the
        // permission a Super Admin can actually revoke from them.
        // Shared across all three realms, so both realm entries are stacked —
        // each ignores the realms it does not own (see EnsureFeature). This
        // replaces role_or_permission and, more importantly, makes a
        // switched-off inbox return 403 rather than an empty list: an empty
        // feed is indistinguishable from simply having no notifications.
        Route::middleware(['feature:alumni.notifications,notifications.view', 'feature:hr.notifications'])
            ->group(function () {
                Route::get('notifications', [NotificationController::class, 'index']);
                Route::post('notifications/read-all', [NotificationController::class, 'markAllRead']);
                Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead']);
            });

        // Same treatment for the ticker: 403 when switched off, not an empty
        // array the client cannot tell apart from "no alerts right now".
        //
        // Alumni only. `hr.ticker` means "may AUTHOR alerts" — gating the read
        // endpoint on it would hide the feed from an admin whose authoring
        // rights were revoked but who still needs to see what is running.
        Route::get('alerts/active', [\App\Http\Controllers\Api\V1\AlertController::class, 'active'])
            ->middleware('feature:alumni.ticker');

        // Account self-service available to every realm (manage own avatar, change own password).
        //
        // The avatar routes carry the same profile capability gate as their
        // /alumni/profile/avatar twins, which call these exact controller
        // methods. Without it this pair was a working bypass: an alumnus with
        // "My Information" switched off got a 403 on one path and a 200 on the
        // other. Password change is deliberately never gated — locking someone
        // out of rotating their own credentials is a security regression, not
        // a feature toggle.
        Route::post('account/avatar', [AlumniProfileController::class, 'uploadAvatar'])
            ->middleware('feature:alumni.profile');
        Route::delete('account/avatar', [AlumniProfileController::class, 'deleteAvatar'])
            ->middleware('feature:alumni.profile');
        Route::put('account/password', [AlumniProfileController::class, 'changePassword']);
    });

    // ---- Authenticated: Super Admin realm ---------------------------------
    Route::prefix('super-admin')
        ->middleware(['auth:sanctum', 'extend.token', 'role:super_admin'])
        ->group(function () {
            Route::get('auth/me', [AuthController::class, 'me'])->defaults('realm', 'super_admin');
            Route::post('auth/logout', [AuthController::class, 'logout']);
            Route::get('dashboard', SuperAdminDashboard::class)->middleware('permission:dashboard.view');
            Route::get('dashboard/export', [SuperAdminDashboard::class, 'export'])->middleware('permission:reports.export');

            // Announcements + alert ticker, mirrored onto the SAME controllers
            // as /hr for the same reason documents are below: Super Admin can
            // already do everything HR can (Gate::before), but its token is
            // rejected by the /hr group's role middleware, so without these the
            // authoring tabs 403 for the one role that outranks HR.
            //
            // No hr.capability here — those switches are Super Admin's own
            // controls over an HR admin, and applying them to Super Admin would
            // let an admin restrict the role that administers them.
            Route::get('announcements', [AnnouncementController::class, 'index'])->middleware('permission:announcements.create|announcements.edit|announcements.delete');
            Route::post('announcements', [AnnouncementController::class, 'store'])->middleware('permission:announcements.create');
            Route::post('announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])->middleware('permission:announcements.edit');
            Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->middleware('permission:announcements.delete');

            Route::get('alerts', [\App\Http\Controllers\Api\V1\Hr\AlertController::class, 'index'])->middleware('permission:ticker.manage');
            Route::post('alerts', [\App\Http\Controllers\Api\V1\Hr\AlertController::class, 'store'])->middleware('permission:ticker.manage');
            Route::put('alerts/{alert}', [\App\Http\Controllers\Api\V1\Hr\AlertController::class, 'update'])->middleware('permission:ticker.manage');
            Route::delete('alerts/{alert}', [\App\Http\Controllers\Api\V1\Hr\AlertController::class, 'destroy'])->middleware('permission:ticker.manage');

            // Documents + document categories — Super Admin gets the same
            // capability HR has, over the same records, so these mirror the
            // /hr routes onto the SAME controllers rather than duplicating the
            // logic (same approach as Alumni Control, which is shared between
            // both realms). The realm groups differ only by their role
            // middleware, which is why the paths have to be declared twice.
            Route::get('documents', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'index'])->middleware('permission:documents.view');
            Route::get('documents/categories', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'categories'])->middleware('permission:documents.view');
            Route::get('documents/recipients', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'recipients'])->middleware('permission:documents.view');
            Route::get('documents/{document}/download', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'download'])->middleware('permission:documents.download');
            Route::post('documents', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'store'])->middleware('permission:documents.upload');
            Route::post('documents/{document}/replace', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'replace'])->middleware('permission:documents.replace');
            Route::delete('documents/{document}', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'destroy'])->middleware('permission:documents.delete');

            // The taxonomy belongs to Super Admin: create, rename, delete, and
            // decide which categories HR may use. HR has no equivalent routes.
            Route::middleware('permission:document_categories.manage')->group(function () {
                Route::get('document-categories', [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'index']);
                Route::post('document-categories', [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'store']);
                Route::put('document-categories/{category}', [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'update']);
                Route::patch('document-categories/{category}/hr-access', [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'setHrAccess']);
                Route::delete('document-categories/{category}', [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'destroy']);
            });

            // Alumni management
            Route::get('alumni', [AlumniController::class, 'index']);
            Route::put('alumni/{alumnus}/profile', [AlumniController::class, 'updateProfile'])->middleware('permission:alumni.edit');
            Route::patch('alumni/{alumnus}/status', [AlumniController::class, 'updateStatus'])->middleware('permission:alumni.disable|alumni.activate');
            Route::patch('alumni/{alumnus}/lock', [AlumniController::class, 'updateLock'])->middleware('permission:alumni.lock|alumni.unlock');
            Route::post('alumni/{alumnus}/reset-password', [AlumniController::class, 'resetPassword'])->middleware('permission:alumni.reset_password');
            Route::patch('alumni/{alumnus}/controls', [AlumniController::class, 'updateControls'])
                ->middleware('permission:alumni.disable_downloads|alumni.disable_documents|alumni.disable_jobs|alumni.disable_applications|alumni.disable_notifications|alumni.disable_ticker|alumni.disable_profile');
            Route::delete('alumni/{alumnus}', [AlumniController::class, 'destroy'])->middleware('permission:alumni.delete');

            // Alumni Master Data Management — permission-gated
            Route::get('alumni-master', [AlumniMasterController::class, 'index'])->middleware('permission:alumni_master.view');
            Route::get('alumni-master/template', [AlumniMasterController::class, 'template'])->middleware('permission:alumni_master.view');
            Route::get('alumni-master/export', [AlumniMasterController::class, 'export'])->middleware('permission:alumni_master.export');
            Route::get('alumni-master/batches', [AlumniMasterController::class, 'batches'])->middleware('permission:alumni_master.view');
            Route::get('alumni-master/batches/{batch}', [AlumniMasterController::class, 'batch'])->middleware('permission:alumni_master.view');
            Route::get('alumni-master/batches/{batch}/failed-report', [AlumniMasterController::class, 'failedReport'])->middleware('permission:alumni_master.import');
            Route::post('alumni-master/validate', [AlumniMasterController::class, 'validateUpload'])->middleware('permission:alumni_master.import');
            Route::post('alumni-master/import', [AlumniMasterController::class, 'import'])->middleware('permission:alumni_master.import');
            // Placed before the {alumniMaster} routes so 'bulk-status' is not read as an id.
            Route::patch('alumni-master/bulk-status', [AlumniMasterController::class, 'bulkStatus'])->middleware('permission:alumni_master.manage');
            Route::post('alumni-master', [AlumniMasterController::class, 'store'])->middleware('permission:alumni_master.manage');
            Route::put('alumni-master/{alumniMaster}', [AlumniMasterController::class, 'update'])->middleware('permission:alumni_master.manage');
            Route::delete('alumni-master/{alumniMaster}', [AlumniMasterController::class, 'destroy'])->middleware('permission:alumni_master.manage');

            // HR admin management
            Route::middleware('permission:hr_admins.manage')->group(function () {
                Route::get('hr-admins', [HrAdminController::class, 'index']);
                Route::post('hr-admins', [HrAdminController::class, 'store']);
                Route::patch('hr-admins/{hrAdmin}', [HrAdminController::class, 'update']);
            // "HR Control" — per-HR-admin feature switches, the HR-side mirror
            // of Alumni Control's per-alumnus switches.
            Route::patch('hr-admins/{hrAdmin}/capabilities', [HrAdminController::class, 'updateCapabilities']);
                Route::post('hr-admins/{hrAdmin}/reset-password', [HrAdminController::class, 'resetPassword']);
                Route::delete('hr-admins/{hrAdmin}', [HrAdminController::class, 'destroy']);
            });

            // Super Admin accounts — multi-admin. Every Super Admin is a peer with
            // identical implicit authority, so any of them may manage any other.
            // The only restriction is self-disable/self-delete, enforced in the
            // controller (guardNotSelf), not just hidden in the UI.
            Route::middleware('permission:super_admins.manage')->group(function () {
                Route::get('super-admins', [SuperAdminController::class, 'index']);
                Route::post('super-admins', [SuperAdminController::class, 'store']);
                Route::patch('super-admins/{superAdmin}', [SuperAdminController::class, 'update']);
                Route::post('super-admins/{superAdmin}/reset-password', [SuperAdminController::class, 'resetPassword']);
                Route::delete('super-admins/{superAdmin}', [SuperAdminController::class, 'destroy']);
            });

            // Role-level permission toggles (HR / Alumni) — applies to all users
            Route::middleware('permission:permissions.manage')->group(function () {
                Route::get('permissions', [PermissionController::class, 'index']);
                Route::put('roles/{role}/permissions', [PermissionController::class, 'update']);
                // Per-individual permission overrides
                Route::get('users/{user}/permissions', [UserPermissionController::class, 'show']);
                Route::put('users/{user}/permissions', [UserPermissionController::class, 'update']);
            });

            // Platform settings
            Route::get('settings', [SettingsController::class, 'index']);
            Route::put('settings', [SettingsController::class, 'update']);

            // Editable public content blocks (Terms, Privacy, footer)
            Route::middleware('permission:content.manage')->group(function () {
                Route::get('content', [ContentController::class, 'index']);
                Route::put('content/{key}', [ContentController::class, 'update']);
            });

            // Audit / activity / login logs + export
            Route::get('audit-logs', [AuditLogController::class, 'index'])->middleware('permission:reports.view');
            Route::get('audit-logs/filters', [AuditLogController::class, 'filters'])->middleware('permission:reports.view');
            Route::get('audit-logs/export', [AuditLogController::class, 'export'])->middleware('permission:reports.export');
        });

    // ---- Authenticated: HR Admin realm ------------------------------------
    Route::prefix('hr')
        ->middleware(['auth:sanctum', 'extend.token', 'role:hr_admin'])
        ->group(function () {
            Route::get('auth/me', [AuthController::class, 'me'])->defaults('realm', 'hr_admin');
            Route::post('auth/logout', [AuthController::class, 'logout']);

            // Matches the alumni and super-admin dashboards, which already
            // carry this — HR was the only realm where the permission was
            // togglable in the Permission Center but enforced nowhere.
            // Every route below carries exactly one gate: `feature:<key>` for
            // the Super Admin switch, plus the permission it needs as the
            // second argument. See App\Http\Middleware\EnsureFeature.
            Route::get('dashboard', HrDashboard::class)->middleware('feature:hr.dashboard,dashboard.view');

            // Alumni Control — complete per-alumnus account management.
            Route::get('alumni', [\App\Http\Controllers\Api\V1\Hr\AlumniController::class, 'index'])->middleware('feature:hr.alumni,alumni.view');
            Route::get('alumni/{alumnus}', [\App\Http\Controllers\Api\V1\Hr\AlumniController::class, 'show'])->middleware('feature:hr.alumni,alumni.view');
            Route::put('alumni/{alumnus}/profile', [\App\Http\Controllers\Api\V1\Hr\AlumniController::class, 'updateProfile'])->middleware('feature:hr.alumni,alumni.edit');
            Route::patch('alumni/{alumnus}/status', [\App\Http\Controllers\Api\V1\Hr\AlumniController::class, 'updateStatus'])->middleware('feature:hr.alumni,alumni.disable|alumni.activate');
            Route::patch('alumni/{alumnus}/lock', [\App\Http\Controllers\Api\V1\Hr\AlumniController::class, 'updateLock'])->middleware('feature:hr.alumni,alumni.lock|alumni.unlock');
            Route::post('alumni/{alumnus}/reset-password', [\App\Http\Controllers\Api\V1\Hr\AlumniController::class, 'resetPassword'])->middleware('feature:hr.alumni,alumni.reset_password');
            Route::patch('alumni/{alumnus}/controls', [\App\Http\Controllers\Api\V1\Hr\AlumniController::class, 'updateControls'])
                ->middleware('feature:hr.alumni,alumni.disable_downloads|alumni.disable_documents|alumni.disable_jobs|alumni.disable_applications|alumni.disable_notifications|alumni.disable_ticker|alumni.disable_profile|alumni.disable_announcements|alumni.disable_dashboard|alumni.disable_service_requests');
            Route::delete('alumni/{alumnus}', [\App\Http\Controllers\Api\V1\Hr\AlumniController::class, 'destroy'])->middleware('feature:hr.alumni,alumni.delete');

            // Alumni Master Data Management (granular)
            Route::get('alumni-master', [AlumniMasterController::class, 'index'])->middleware('feature:hr.alumni_master,alumni_master.view');
            Route::get('alumni-master/template', [AlumniMasterController::class, 'template'])->middleware('feature:hr.alumni_master,alumni_master.view');
            Route::get('alumni-master/export', [AlumniMasterController::class, 'export'])->middleware('feature:hr.alumni_master,alumni_master.export');
            Route::get('alumni-master/batches', [AlumniMasterController::class, 'batches'])->middleware('feature:hr.alumni_master,alumni_master.view');
            Route::get('alumni-master/batches/{batch}', [AlumniMasterController::class, 'batch'])->middleware('feature:hr.alumni_master,alumni_master.view');
            Route::get('alumni-master/batches/{batch}/failed-report', [AlumniMasterController::class, 'failedReport'])->middleware('feature:hr.alumni_master,alumni_master.import');
            Route::post('alumni-master/validate', [AlumniMasterController::class, 'validateUpload'])->middleware('feature:hr.alumni_master,alumni_master.import');
            Route::post('alumni-master/import', [AlumniMasterController::class, 'import'])->middleware('feature:hr.alumni_master,alumni_master.import');
            Route::post('alumni-master', [AlumniMasterController::class, 'store'])->middleware('feature:hr.alumni_master,alumni_master.manage');
            Route::put('alumni-master/{alumniMaster}', [AlumniMasterController::class, 'update'])->middleware('feature:hr.alumni_master,alumni_master.manage');
            Route::delete('alumni-master/{alumniMaster}', [AlumniMasterController::class, 'destroy'])->middleware('feature:hr.alumni_master,alumni_master.manage');

            // Announcements. Any announcement permission grants the list:
            // `announcements.view` is ALUMNI-only and never assignable to HR,
            // so gating on it locked out an admin holding only edit or delete.
            Route::get('announcements', [AnnouncementController::class, 'index'])->middleware('feature:hr.announcements,announcements.create|announcements.edit|announcements.delete');
            Route::post('announcements', [AnnouncementController::class, 'store'])->middleware('feature:hr.announcements,announcements.create');
            Route::post('announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])->middleware('feature:hr.announcements,announcements.edit');
            Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->middleware('feature:hr.announcements,announcements.delete');

            // Jobs
            Route::get('jobs', [JobController::class, 'index'])->middleware('feature:hr.jobs,jobs.view');
            Route::post('jobs', [JobController::class, 'store'])->middleware('feature:hr.jobs,jobs.create');
            Route::put('jobs/{job}', [JobController::class, 'update'])->middleware('feature:hr.jobs,jobs.edit');
            Route::patch('jobs/{job}/status', [JobController::class, 'updateStatus'])->middleware('feature:hr.jobs,jobs.close');
            Route::delete('jobs/{job}', [JobController::class, 'destroy'])->middleware('feature:hr.jobs,jobs.delete');

            // Applications
            Route::get('applications', [\App\Http\Controllers\Api\V1\Hr\ApplicationController::class, 'index'])->middleware('feature:hr.applications,applications.view');
            Route::patch('applications/{application}/status', [\App\Http\Controllers\Api\V1\Hr\ApplicationController::class, 'updateStatus'])->middleware('feature:hr.applications,applications.update_status');
            Route::get('applications/{application}/resume', [\App\Http\Controllers\Api\V1\Hr\ApplicationController::class, 'downloadResume'])->middleware('feature:hr.applications,applications.download_resume');

            // Live alert ticker management
            Route::get('alerts', [\App\Http\Controllers\Api\V1\Hr\AlertController::class, 'index'])->middleware('feature:hr.ticker,ticker.manage');
            Route::post('alerts', [\App\Http\Controllers\Api\V1\Hr\AlertController::class, 'store'])->middleware('feature:hr.ticker,ticker.manage');
            Route::put('alerts/{alert}', [\App\Http\Controllers\Api\V1\Hr\AlertController::class, 'update'])->middleware('feature:hr.ticker,ticker.manage');
            Route::delete('alerts/{alert}', [\App\Http\Controllers\Api\V1\Hr\AlertController::class, 'destroy'])->middleware('feature:hr.ticker,ticker.manage');

            // Documents — HR manages alumni vaults
            Route::get('documents', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'index'])->middleware('feature:hr.documents,documents.view');
            Route::get('documents/categories', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'categories'])->middleware('feature:hr.documents,documents.view');
            Route::get('documents/recipients', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'recipients'])->middleware('feature:hr.documents,documents.view');
            Route::get('documents/{document}/download', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'download'])->middleware('feature:hr.documents,documents.download');
            Route::post('documents', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'store'])->middleware('feature:hr.documents,documents.upload');
            Route::post('documents/{document}/replace', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'replace'])->middleware('feature:hr.documents,documents.replace');
            Route::delete('documents/{document}', [\App\Http\Controllers\Api\V1\Hr\DocumentController::class, 'destroy'])->middleware('feature:hr.documents,documents.delete');

            // Document categories. Listing is permission-only so the page still
            // renders with the switch off — HR can see the taxonomy and say
            // what they need changed, just not change it.
            // Read-only, and filtered to the categories Super Admin has granted
            // this deployment's HR. Managing the taxonomy — creating, renaming,
            // deleting, granting — is Super Admin's alone; per-category access
            // replaced the single blanket switch HR used to hold.
           
            Route::middleware('permission:document_categories.manage')->group(function () {

    Route::get(
        'document-categories',
        [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'index']
    );

    Route::post(
        'document-categories',
        [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'store']
    );

    Route::put(
        'document-categories/{category}',
        [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'update']
    );

    Route::delete(
        'document-categories/{category}',
        [\App\Http\Controllers\Api\V1\Hr\DocumentCategoryController::class, 'destroy']
    );
});

          // Read-only General settings
            Route::get('settings', [\App\Http\Controllers\Api\V1\Hr\SettingsController::class, 'index'])->middleware('feature:hr.settings,hr_settings.access');
        });

    // ---- Authenticated: Alumni realm --------------------------------------
    Route::prefix('alumni')
        ->middleware(['auth:sanctum', 'extend.token', 'role:alumni'])
        ->group(function () {
            Route::get('auth/me', [AuthController::class, 'me'])->defaults('realm', 'alumni');
            Route::post('auth/logout', [AuthController::class, 'logout']);

            Route::get('dashboard', AlumniDashboard::class)->middleware('feature:alumni.dashboard,dashboard.view');

            // "My Information" — entirely HR-managed, read-only for the alumnus
            // (see Alumni\ProfileController's docblock). Only the profile photo
            // stays self-service, still gated by profile.edit.
            Route::get('profile', [AlumniProfileController::class, 'show'])->middleware('feature:alumni.profile,profile.view');
            Route::post('profile/avatar', [AlumniProfileController::class, 'uploadAvatar'])->middleware('feature:alumni.profile,profile.edit');
            Route::delete('profile/avatar', [AlumniProfileController::class, 'deleteAvatar'])->middleware('feature:alumni.profile,profile.edit');

            // Secure document vault — granular: view list vs. download
            Route::get('documents', [AlumniDocumentController::class, 'index'])->middleware('feature:alumni.documents,documents.view');
            Route::get('documents/categories', [AlumniDocumentController::class, 'categories'])->middleware('feature:alumni.documents,documents.view');

            // Downloads need BOTH switches: the vault must be open and
            // downloading must be allowed, so both features are stacked.
            Route::get('documents/{document}/download', [AlumniDocumentController::class, 'requestDownload'])
                ->middleware(['feature:alumni.documents,documents.download', 'feature:alumni.downloads']);

            // "Send to Email" — the same gates as the download it wraps, so it
            // can never be the weaker path to a file. Additionally gated by the
            // alumni_document_email_enabled platform setting in the controller.
            //
            // throttle:5,1 because each call sends a real email to a real
            // inbox: unthrottled it is a way to flood someone's own mailbox.
            Route::post('documents/{document}/email', [AlumniDocumentController::class, 'emailToSelf'])
                ->middleware([
                    'feature:alumni.documents,documents.download',
                    'feature:alumni.downloads',
                    'throttle:5,1',
                ]);

            // Job portal — browsing and applying are separate features, so an
            // alumnus can keep read access while applications are switched off.
            Route::get('jobs', [AlumniJobController::class, 'index'])->middleware('feature:alumni.jobs,jobs.view');
            Route::post('jobs/{job}/apply', [AlumniJobController::class, 'apply'])->middleware('feature:alumni.applications,jobs.apply');
            Route::get('applications', [AlumniApplicationController::class, 'index'])->middleware('feature:alumni.applications,applications.view');

            // Announcements archive — read-only, published + alumni-addressed only.
            Route::get('announcements', [AlumniAnnouncementController::class, 'index'])->middleware('feature:alumni.announcements,announcements.view');
        });

    // Signed, public document stream (no Sanctum token on browser navigation —
    // authorized by the short-lived signature issued after an ownership check).
    //
    // "signed:relative", NOT "signed". These URLs are generated with
    // absolute:false so they resolve against whatever origin the browser is
    // already on — localhost, a LAN IP, or a real domain — and ride the same
    // proxy as every other API call. An absolute URL baked in whatever Host
    // the backend saw, which behind the Vite dev proxy is localhost:8010:
    // meaningless on a tester's device, where localhost is THEIR machine.
    // The signature is computed over the relative path, so the validation
    // side has to match or every link 403s.
    //
    // 30/min on each signed stream. The signature already scopes access to one
    // resource for a few minutes, so this is not the access control — it bounds
    // how hard a leaked URL can be pulled before it expires, and these are the
    // only public endpoints that stream files off disk.
    Route::get('alumni/documents/{document}/stream', [AlumniDocumentController::class, 'stream'])
        ->middleware(['signed:relative', 'throttle:30,1'])
        ->name('alumni.documents.stream');

    // Signed avatar stream — used directly in <img> tags (no bearer token).
    // 120/min — higher than the other streams because avatars render inline in
    // <img> tags: a table of 50 alumni legitimately fires 50 of these on one
    // page load, and a stricter limit would break the page rather than an attack.
    Route::get('avatars/{user}/show', [AlumniProfileController::class, 'streamAvatar'])
        ->middleware(['signed:relative', 'throttle:120,1'])
        ->name('avatars.show');

    // Signed applicant resume stream (HR review) — same pattern as document streaming.
    Route::get('hr/applications/{application}/resume/stream', [\App\Http\Controllers\Api\V1\Hr\ApplicationController::class, 'streamResume'])
        ->middleware(['signed:relative', 'throttle:30,1'])
        ->name('hr.applications.resume.stream');
});
