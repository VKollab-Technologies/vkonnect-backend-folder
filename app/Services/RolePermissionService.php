<?php

namespace App\Services;

use App\Models\RolePermissionDefault;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Support\Facades\DB;

/**
 * Manages role-level permission toggles (HR / Alumni). The toggle state lives in
 * `role_permission_defaults`; flipping it persists the default AND bulk-applies
 * the change to every user of that role (their direct permissions), so a change
 * "here" takes effect "there" immediately.
 */
class RolePermissionService
{
    /**
     * Default permission names currently enabled for a role.
     *
     * Filtered against the catalog on the way out. The stored template is a
     * snapshot of whatever was enabled when a Super Admin last saved it, and it
     * does NOT move when a permission is retired from the catalog — so a
     * removed name survived here and was handed straight to syncPermissions(),
     * which made Spatie throw
     *   "There is no permission named `service_request.view` for guard `web`"
     * at the person activating their account. Filtering here fixes every
     * caller at once; SyncPermissions additionally prunes the rows so the drift
     * does not persist silently.
     */
    public function defaults(string $role): array
    {
        $stored = array_values(array_intersect(
            RolePermissionDefault::forRole($role),
            Permissions::assignableFor($role),
        ));

        // Fall back to the catalog baseline if nothing is stored yet — or if
        // filtering emptied it, which would otherwise strip a role bare.
        if (empty($stored)) {
            return Permissions::defaultsFor($role);
        }

        return $stored;
    }

    /**
     * Set the enabled permissions for a role: persist the default template and
     * apply to every existing user of that role.
     *
     * @return array the normalized, persisted permission list
     */
    public function setForRole(string $role, array $permissions): array
    {
        $allowed = Permissions::assignableFor($role);
        $permissions = array_values(array_intersect($permissions, $allowed));

        DB::transaction(function () use ($role, $permissions) {
            // Persist the role default template.
            RolePermissionDefault::where('role', $role)->delete();
            foreach ($permissions as $p) {
                RolePermissionDefault::create(['role' => $role, 'permission' => $p]);
            }

            // Bulk-apply to every user of this role EXCEPT those with an individual
            // permission profile (Permission Center) — those are only touched via
            // UserPermissionController, never silently overwritten by a role-level change.
            User::where('role', $role)
                ->whereNull('permissions_customized_at')
                ->select('id')
                ->chunkById(200, function ($users) use ($permissions) {
                    foreach ($users as $user) {
                        $user->syncPermissions($permissions);
                    }
                });
        });

        return $permissions;
    }
}
