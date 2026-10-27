<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\RolePermissionService;
use App\Support\Permissions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Role-level permission toggles for HR and Alumni, grouped by category for the
 * premium Permission Center. Flipping a switch persists the role default and
 * applies it to every user of that role. Super Admin is excluded — it always
 * holds every permission (Gate::before).
 */
class PermissionController extends Controller
{
    private const MANAGED_ROLES = ['hr_admin', 'alumni'];

    public function __construct(private readonly RolePermissionService $rolePerms) {}

    public function index(): JsonResponse
    {
        $roles = collect(self::MANAGED_ROLES)->map(function (string $role) {
            $enabled = $this->rolePerms->defaults($role);

            $categories = collect(Permissions::groupedFor($role))->map(fn (array $group) => [
                'category' => $group['category'],
                'permissions' => collect($group['permissions'])->map(fn (array $p) => [
                    'name' => $p['name'],
                    'label' => $p['label'],
                    'description' => $p['description'],
                    'enabled' => in_array($p['name'], $enabled, true),
                ])->values(),
            ])->values();

            return [
                'role' => $role,
                'label' => $role === 'hr_admin' ? 'HR Admin' : 'Alumni',
                'userCount' => User::where('role', $role)->count(),
                'categories' => $categories,
            ];
        });

        return response()->json(['roles' => $roles]);
    }

    public function update(Request $request, string $role): JsonResponse
    {
        abort_unless(in_array($role, self::MANAGED_ROLES, true), 404);

        $data = $request->validate([
            'permissions' => ['present', 'array'],
            'permissions.*' => ['string', Rule::in(Permissions::all())],
        ]);

        $applied = $this->rolePerms->setForRole($role, $data['permissions']);

        AuditLogger::log('permission.role_updated', 'permissions', null, [
            'role' => $role,
            'count' => count($applied),
        ]);

        return $this->index();
    }
}
