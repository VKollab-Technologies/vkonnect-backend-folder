<?php

use App\Models\RolePermissionDefault;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

/**
 * `document_categories.manage` is a newly-introduced catalog permission,
 * default-on for hr_admin (Permissions::defaultsFor). Existing installations
 * already have hr_admin users whose direct permissions were synced before
 * this key existed, so this backfills it exactly the same way the seeder
 * would for a fresh install — skipping anyone with an individual permission
 * profile (permissions_customized_at), whose access is never silently
 * changed by a catalog update. Mirrors 2026_07_22_000002_backfill_dashboard_view_permission.
 */
return new class extends Migration
{
    public function up(): void
    {
        Permission::findOrCreate('document_categories.manage');

        RolePermissionDefault::firstOrCreate(['role' => 'hr_admin', 'permission' => 'document_categories.manage']);

        User::where('role', 'hr_admin')
            ->whereNull('permissions_customized_at')
            ->each(function (User $user) {
                if (! $user->hasPermissionTo('document_categories.manage')) {
                    $user->givePermissionTo('document_categories.manage');
                }
            });
    }

    public function down(): void
    {
        // Intentionally left as a no-op — removing a permission grant on
        // rollback could lock out users who started relying on it.
    }
};
