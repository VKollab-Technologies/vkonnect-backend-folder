<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\UpdateUserStatusRequest;
use App\Http\Resources\AlumniResource;
use App\Models\AlumniProfile;
use App\Models\User;
use App\Services\AlumniAccountService;
use App\Services\AuditLogger;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function __construct(private readonly AlumniAccountService $accounts) {}

    /**
     * Edit an alumnus's profile fields on their behalf. Identical logic to
     * Hr\AlumniController::updateProfile — Super Admin gets the same
     * capability HR has (Gate::before already grants every permission
     * check below implicitly). No super_admin notification here, unlike
     * the HR version: that call exists so Super Admin stays aware of
     * HR-authored changes, which doesn't apply when Super Admin is the
     * actor — see AlumniAccountService for the same actor-aware pattern.
     */
    public function updateProfile(Request $request, User $alumnus): AlumniResource
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:120'],
            'designation' => ['sometimes', 'nullable', 'string', 'max:120'],
            'department' => ['sometimes', 'nullable', 'string', 'max:120'],
            'currentOrganization' => ['sometimes', 'nullable', 'string', 'max:160'],
            'phone' => ['sometimes', 'nullable', new \App\Rules\PhoneNumber],
            'location' => ['sometimes', 'nullable', 'string', 'max:160'],
            // "My Information" fields — HR-managed only, never alumni-editable.
            'lastWorkingLocation' => ['sometimes', 'nullable', 'string', 'max:160'],
            'band' => ['sometimes', 'nullable', 'string', 'max:40'],
            'supervisorName' => ['sometimes', 'nullable', 'string', 'max:160'],
            'supervisorEmail' => ['sometimes', 'nullable', 'email', 'max:160'],
            'uanNumber' => ['sometimes', 'nullable', new \App\Rules\UanNumber],
            'pfNumber' => ['sometimes', 'nullable', 'string', 'max:40'],
        ]);

        if (isset($data['name'])) {
            $alumnus->update(['name' => $data['name']]);
        }

        $profile = $alumnus->alumniProfile ?? new AlumniProfile(['user_id' => $alumnus->id]);
        $profile->fill([
            'designation' => $data['designation'] ?? $profile->designation,
            'department' => $data['department'] ?? $profile->department,
            'current_organization' => $data['currentOrganization'] ?? $profile->current_organization,
            'phone' => $data['phone'] ?? $profile->phone,
            'location' => $data['location'] ?? $profile->location,
            'last_working_location' => $data['lastWorkingLocation'] ?? $profile->last_working_location,
            'band' => $data['band'] ?? $profile->band,
            'supervisor_name' => $data['supervisorName'] ?? $profile->supervisor_name,
            'supervisor_email' => $data['supervisorEmail'] ?? $profile->supervisor_email,
            'uan_number' => $data['uanNumber'] ?? $profile->uan_number,
            'pf_number' => $data['pfNumber'] ?? $profile->pf_number,
        ]);
        $profile->save();

        AuditLogger::log('alumni.profile_edited', 'alumni', $alumnus->id, ['alumnus' => $alumnus->name]);

        return new AlumniResource($alumnus->fresh()->load('alumniProfile'));
    }

    /**
     * Granular, database-driven capability toggles — identical logic to
     * Hr\AlumniController::updateControls. See that method's docblock.
     */
    public function updateControls(Request $request, User $alumnus): AlumniResource
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $data = $request->validate(
            collect(AlumniAccountService::controlMap())
                ->map(fn () => ['sometimes', 'boolean'])
                ->all()
        );

        $this->accounts->updateControls($alumnus, $data, $request->user());

        return new AlumniResource($alumnus->fresh()->load('alumniProfile'));
    }

    /** Paginated, searchable, status-filterable alumni list. */
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 15), 100);

        $alumni = User::query()
            ->where('role', 'alumni')
            ->with('alumniProfile', 'permissions')
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->string('search');
                $q->where(fn ($sub) => $sub
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%"));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate($perPage);

        return response()->json(ApiResponse::paginate($alumni, AlumniResource::class));
    }

    /** Enable / disable an alumnus account. */
    public function updateStatus(UpdateUserStatusRequest $request, User $alumnus): AlumniResource
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $this->accounts->updateStatus($alumnus, $request->validated('status'), 'super_admin', $request->user()->name);

        return new AlumniResource($alumnus->load('alumniProfile'));
    }

    /** Lock / unlock an alumnus's account (revokes active sessions when locking). */
    public function updateLock(Request $request, User $alumnus): AlumniResource
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $this->accounts->updateLock($alumnus, $request->boolean('locked'));

        return new AlumniResource($alumnus->fresh()->load('alumniProfile'));
    }

    /**
     * Reset an alumnus's password. Never generates or transmits a plaintext
     * password — revokes all active sessions and points them to the existing
     * "Forgot Password" self-service flow.
     */
    public function resetPassword(User $alumnus): JsonResponse
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $this->accounts->resetPassword($alumnus, 'super_admin');

        return response()->json(['message' => 'Password reset. The alumnus has been notified to set a new one.']);
    }

    /** Soft-delete an alumnus. */
    public function destroy(User $alumnus): JsonResponse
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $this->accounts->destroy($alumnus, 'super_admin', request()->user()->name);

        return response()->json(['message' => 'Alumnus removed.']);
    }
}
