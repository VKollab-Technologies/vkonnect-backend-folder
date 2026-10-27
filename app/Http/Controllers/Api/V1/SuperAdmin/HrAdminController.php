<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreHrAdminRequest;
use App\Http\Requests\SuperAdmin\UpdateHrAdminRequest;
use App\Http\Resources\HrAdminResource;
use App\Models\HrAdmin;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Support\ApiResponse;
use App\Support\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HrAdminController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $admins = User::query()
            ->where('role', 'hr_admin')
            ->with(['hrAdmin', 'permissions'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->string('search');
                $q->where(fn ($sub) => $sub
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%"));
            })
            ->latest()
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($admins, HrAdminResource::class));
    }

    public function store(StoreHrAdminRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'vkollab_id' => $data['vkollabId'],
                'password' => Hash::make($data['password']),
                'role' => 'hr_admin',
                'status' => 'active',
                'email_verified_at' => now(),
                // Opt-in switches start disabled — see
                // User::HR_DEFAULT_OFF_CAPABILITIES.
                ...array_fill_keys(User::hrDefaultOffColumns(), now()),
            ]);
            $user->assignRole('hr_admin');
            // Grant the current admin-configured defaults for this role.
            $user->syncPermissions(app(\App\Services\RolePermissionService::class)->defaults('hr_admin'));

            HrAdmin::create([
                'user_id' => $user->id,
                'vkollab_id' => $data['vkollabId'],
                'department' => $data['department'] ?? null,
                'created_by' => request()->user()->id,
            ]);

            return $user;
        });

        \App\Services\AuditLogger::log('hr_admin.created', 'hr', $user->id, ['name' => $user->name, 'email' => $user->email]);

        // Outside the transaction: queueing the mail inside it would send a
        // welcome for an account a later rollback removed.
        $this->notifications->adminWelcome(
            admin: $user,
            temporaryPassword: $data['password'],
            role: 'hr_admin',
            department: $data['department'] ?? null,
            invitedBy: $request->user()->name,
        );

        return (new HrAdminResource($user->load(['hrAdmin', 'permissions'])))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateHrAdminRequest $request, User $hrAdmin): HrAdminResource
    {
        abort_unless($hrAdmin->role === 'hr_admin', 404);
        $data = $request->validated();

        $hrAdmin->fill(array_filter(
            ['name' => $data['name'] ?? null, 'status' => $data['status'] ?? null],
            fn ($v) => $v !== null,
        ))->save();

        if (array_key_exists('department', $data)) {
            $hrAdmin->hrAdmin?->update(['department' => $data['department']]);
        }

        \App\Services\AuditLogger::log('hr_admin.updated', 'hr', $hrAdmin->id, ['name' => $hrAdmin->name]);

        return new HrAdminResource($hrAdmin->load(['hrAdmin', 'permissions']));
    }

    /**
     * Flip the per-HR-admin feature switches ("HR Control").
     *
     * Mirrors Hr\AlumniController::updateControls: a partial payload only
     * touches the switches it includes, and each is a nullable timestamp
     * (null = enabled). Deliberately separate from the Permission Center —
     * editing a permission profile there marks the user "customized" and opts
     * them out of future role-default changes, which is too heavy a
     * consequence for "turn this off for now".
     */
    public function updateCapabilities(Request $request, User $hrAdmin): HrAdminResource
    {
        abort_unless($hrAdmin->role === 'hr_admin', 404);

        $names = array_keys(User::HR_CAPABILITY_COLUMNS);

        $data = $request->validate(
            collect($names)->mapWithKeys(fn (string $n) => [$n => ['sometimes', 'boolean']])->all()
        );

        $changes = [];
        foreach ($data as $capability => $enabled) {
            $changes[User::HR_CAPABILITY_COLUMNS[$capability]] = $enabled ? null : now();
        }

        if ($changes) {
            $hrAdmin->forceFill($changes)->save();

            \App\Services\AuditLogger::log('hr_admin.capabilities_updated', 'hr', $hrAdmin->id, [
                'name' => $hrAdmin->name,
                'changes' => $data,
            ]);
        }

        return new HrAdminResource($hrAdmin->fresh()->load(['hrAdmin', 'permissions']));
    }

    public function destroy(User $hrAdmin): JsonResponse
    {
        abort_unless($hrAdmin->role === 'hr_admin', 404);

        \App\Services\AuditLogger::log('hr_admin.deleted', 'hr', $hrAdmin->id, ['name' => $hrAdmin->name]);
        $hrAdmin->delete();

        return response()->json(['message' => 'HR admin removed.']);
    }

    public function resetPassword(User $hrAdmin): JsonResponse
    {
        abort_unless($hrAdmin->role === 'hr_admin', 404);

        // Invalidates the password itself, not just the sessions — see
        // PasswordReset. Revoking tokens alone left the old credential working
        // while telling the admin it had been reset.
        PasswordReset::scramble($hrAdmin);

        $this->notifications->email(
            $hrAdmin->email,
            'Your VKONNECT password was reset',
            'Password reset by Super Admin',
            "A platform administrator has reset your password on VKONNECT. Please use the 'Forgot Password' option on the sign-in page with your EMP ID to set a new password.",
            null,
            null,
            'hr_admin',
        );

        AuditLogger::log('hr_admin.password_reset', 'hr', $hrAdmin->id, ['name' => $hrAdmin->name]);

        return response()->json(['message' => 'Password reset. The HR admin has been notified to set a new one.']);
    }
}
