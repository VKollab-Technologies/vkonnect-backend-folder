<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

/**
 * Registers the 3 catalog permissions newly introduced for previously
 * role-only (no PermissionMiddleware) super_admin routes: HrAdminController,
 * SuperAdminController, ContentController, PermissionController,
 * UserPermissionController. Required so `permission:*` route middleware
 * doesn't throw PermissionDoesNotExist — Spatie's own Gate::before check
 * (which validates the permission is registered) runs before this app's
 * super-admin-bypass Gate::before, so even the omnipotent super_admin would
 * 500 on these routes without this. No RolePermissionDefault/user-sync
 * backfill is needed (unlike the dashboard.view migration) because
 * super_admin passes every permission check via Gate::before regardless of
 * direct grants — see DatabaseSeeder::seedRolesAndPermissions().
 *
 * The other permissions touched by this change (alumni.delete, alumni.lock,
 * alumni.unlock, alumni.reset_password, alumni.disable, alumni.activate,
 * hr_admins.manage, dashboard.view) already exist as Permission rows from
 * their hr_admin/alumni routes — only their assignable-roles metadata in
 * App\Support\Permissions widened, so no new row is needed for those.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['super_admins.manage', 'content.manage', 'permissions.manage'] as $name) {
            Permission::findOrCreate($name);
        }
    }

    public function down(): void
    {
        // Intentionally left as a no-op — removing a permission definition on
        // rollback could break a route mid-request if it's already in use.
    }
};
