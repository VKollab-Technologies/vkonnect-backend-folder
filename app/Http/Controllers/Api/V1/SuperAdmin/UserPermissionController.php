<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\RolePermissionService;
use App\Support\Permissions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Per-individual permission management, grouped by category. The Super Admin can
 * enable/disable any capability for a specific HR admin or alumnus; changes are
 * stored as direct permissions and enforced immediately.
 */
class UserPermissionController extends Controller
{
    public function show(User $user): JsonResponse
    {
        abort_if($user->role === 'super_admin', 422, 'The Super Admin holds all permissions.');

        $assigned = $user->getDirectPermissions()->pluck('name')->all();

        $categories = collect(Permissions::groupedFor($user->role))->map(fn (array $group) => [
            'category' => $group['category'],
            'permissions' => collect($group['permissions'])->map(fn (array $p) => [
                'name' => $p['name'],
                'label' => $p['label'],
                'description' => $p['description'],
                'enabled' => in_array($p['name'], $assigned, true),
            ])->values(),
        ])->values();

        return response()->json([
            'userId' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'isCustomized' => $user->permissions_customized_at !== null,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, User $user, RolePermissionService $rolePermissions): JsonResponse
    {
        abort_if($user->role === 'super_admin', 422, 'The Super Admin holds all permissions.');

        $data = $request->validate([
            'permissions' => ['present', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
            'resetToDefaults' => ['sometimes', 'boolean'],
        ]);

        if ($data['resetToDefaults'] ?? false) {
            $permissions = $rolePermissions->defaults($user->role);
            $user->syncPermissions($permissions);
            $user->forceFill(['permissions_customized_at' => null])->save();

            AuditLogger::log('permission.user_reset', 'permissions', $user->id, [
                'user' => $user->name,
                'count' => count($permissions),
            ]);

            return $this->show($user);
        }

        // Constrain to permissions assignable for this realm.
        $allowed = Permissions::assignableFor($user->role);
        $permissions = array_values(array_intersect($data['permissions'], $allowed));

        $user->syncPermissions($permissions);
        // Mark this user as having an individual permission profile so future
        // role-level default changes (Permissions page) never silently overwrite it.
        $user->forceFill(['permissions_customized_at' => now()])->save();

        AuditLogger::log('permission.user_updated', 'permissions', $user->id, [
            'user' => $user->name,
            'count' => count($permissions),
        ]);

        return $this->show($user);
    }
}
