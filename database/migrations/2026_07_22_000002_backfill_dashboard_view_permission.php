<?php

use App\Models\RolePermissionDefault;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

/**
 * `dashboard.view` is a newly-introduced catalog permission that is part of
 * every hr_admin/alumni role's defaults (Permissions::defaultsFor). Existing
 * installations already have users whose direct permissions were synced
 * before this key existed, so this backfills it exactly the same way the
 * seeder would for a fresh install — skipping anyone with an individual
 * permission profile (permissions_customized_at), whose access is never
 * silently changed by a catalog update.
 */
return new class extends Migration
{
    public function up(): void
    {
        Permission::findOrCreate('dashboard.view');

        foreach (['hr_admin', 'alumni'] as $role) {
            RolePermissionDefault::firstOrCreate(['role' => $role, 'permission' => 'dashboard.view']);
        }

        User::whereIn('role', ['hr_admin', 'alumni'])
            ->whereNull('permissions_customized_at')
            ->each(function (User $user) {
                if (! $user->hasPermissionTo('dashboard.view')) {
                    $user->givePermissionTo('dashboard.view');
                }
            });
    }

    public function down(): void
    {
        // Intentionally left as a no-op — removing a permission grant on
        // rollback could lock out users who started relying on it.
    }
};
