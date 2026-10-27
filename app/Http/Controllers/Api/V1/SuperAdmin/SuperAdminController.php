<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreSuperAdminRequest;
use App\Http\Requests\SuperAdmin\UpdateSuperAdminRequest;
use App\Http\Resources\SuperAdminAccountResource;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Support\ApiResponse;
use App\Support\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Super Admin account management — multi-admin.
 *
 * The platform supports any number of Super Admins. They are peers: every
 * Super Admin holds every permission implicitly (AppServiceProvider::boot's
 * Gate::before), so there is no per-admin permission profile and no notion of
 * a "primary" admin. Any Super Admin may create, update, reset, or remove any
 * OTHER Super Admin.
 *
 * The one asymmetry is self-protection (guardNotSelf): nobody can disable or
 * delete their own account. That is not about privilege — it stops an admin
 * locking themselves out mid-session, and prevents the last remaining admin
 * deleting themselves and leaving the platform unadministrable.
 */
class SuperAdminController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    /** Provision a peer Super Admin — same role, same implicit full access. */
    public function store(StoreSuperAdminRequest $request): JsonResponse
    {
        $data = $request->validated();

        $admin = DB::transaction(function () use ($data) {
            $admin = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'vkollab_id' => $data['vkollabId'],
                'password' => Hash::make($data['password']),
                'role' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // No syncPermissions here on purpose: super_admin authority comes
            // from the Gate::before short-circuit, not from stored grants, so
            // every Super Admin is automatically identical to every other.
            $admin->assignRole('super_admin');

            return $admin;
        });

        AuditLogger::log('super_admin.created', 'platform', $admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
        ]);

        // Was the generic NotificationMail with no link and no credentials —
        // it told the new admin to use "the password you were given" without
        // giving it to them, or saying where to sign in.
        $this->notifications->adminWelcome(
            admin: $admin,
            temporaryPassword: $data['password'],
            role: 'super_admin',
            invitedBy: $request->user()->name,
        );

        return (new SuperAdminAccountResource($admin))->response()->setStatusCode(201);
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $admins = User::query()
            ->where('role', 'super_admin')
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->string('search');
                $q->where(fn ($sub) => $sub
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%"));
            })
            ->latest()
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($admins, SuperAdminAccountResource::class));
    }

    public function update(UpdateSuperAdminRequest $request, User $superAdmin): SuperAdminAccountResource
    {
        abort_unless($superAdmin->role === 'super_admin', 404);
        $data = $request->validated();

        if (($data['status'] ?? null) === 'disabled') {
            $this->guardNotSelf($superAdmin);
        }

        $superAdmin->fill(array_filter(
            ['name' => $data['name'] ?? null, 'status' => $data['status'] ?? null],
            fn ($v) => $v !== null,
        ))->save();

        AuditLogger::log('super_admin.updated', 'platform', $superAdmin->id, ['name' => $superAdmin->name]);

        return new SuperAdminAccountResource($superAdmin->fresh());
    }

    public function destroy(User $superAdmin): JsonResponse
    {
        abort_unless($superAdmin->role === 'super_admin', 404);

        $this->guardNotSelf($superAdmin);

        AuditLogger::log('super_admin.deleted', 'platform', $superAdmin->id, ['name' => $superAdmin->name]);
        $superAdmin->delete();

        return response()->json(['message' => 'Super Admin removed.']);
    }

    public function resetPassword(User $superAdmin): JsonResponse
    {
        abort_unless($superAdmin->role === 'super_admin', 404);

        // See PasswordReset — the old password stops working, which is the
        // whole point of the action and previously did not happen.
        PasswordReset::scramble($superAdmin);

        $this->notifications->email(
            $superAdmin->email,
            'Your VKONNECT password was reset',
            'Password reset by a fellow Super Admin',
            "A platform administrator has reset your password on VKONNECT. Please use the 'Forgot Password' option on the sign-in page with your EMP ID to set a new password.",
            null,
            null,
            'super_admin',
        );

        AuditLogger::log('super_admin.password_reset', 'platform', $superAdmin->id, ['name' => $superAdmin->name]);

        return response()->json(['message' => 'Password reset. They have been notified to set a new one.']);
    }

    private function guardNotSelf(User $target): void
    {
        if ($target->id === request()->user()?->id) {
            throw ValidationException::withMessages([
                'status' => ['You cannot disable or remove your own Super Admin account.'],
            ]);
        }
    }
}
