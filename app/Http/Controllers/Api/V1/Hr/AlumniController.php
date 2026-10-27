<?php

namespace App\Http\Controllers\Api\V1\Hr;

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

/**
 * HR "Alumni Control" — complete per-alumnus account management. Every action
 * here is permission-gated (route middleware + per-field checks below),
 * audit-logged, and enforced immediately (token revocation for
 * session-affecting changes; the granular capability flags are re-checked on
 * every relevant request going forward, see Alumni/DocumentController,
 * Alumni/JobController, NotificationController, AlertController).
 */
class AlumniController extends Controller
{
    public function __construct(private readonly AlumniAccountService $accounts) {}

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

    /** Profile + account-control detail for a single alumnus. */
    public function show(User $alumnus): AlumniResource
    {
        abort_unless($alumnus->role === 'alumni', 404);

        return new AlumniResource($alumnus->load('alumniProfile'));
    }

    /** Edit an alumnus's profile fields on their behalf. */
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

        /*
         * Write the same facts back to the roster row this alumnus claimed.
         *
         * Alumni Control and the Alumni Master page edit the SAME HR-managed
         * facts into two different tables, and neither used to tell the other.
         * Whichever screen you used, the other kept showing the old value — the
         * "edits don't take effect" report. The roster is also what a re-import
         * or a re-registration reads, so leaving it stale would resurrect the
         * old values later.
         *
         * Guarded with `syncing` so the roster's own observer does not bounce
         * these values straight back into the profile we just wrote.
         */
        $roster = \App\Models\AlumniMaster::where('claimed_by', $alumnus->id)->first();

        if ($roster) {
            $rosterChanges = [];

            foreach (\App\Models\AlumniMaster::PROFILE_FIELD_MAP as $rosterColumn => $profileColumn) {
                if ($profile->{$profileColumn} !== null && $profile->{$profileColumn} !== $roster->{$rosterColumn}) {
                    $rosterChanges[$rosterColumn] = $profile->{$profileColumn};
                }
            }

            if ($rosterChanges) {
                $roster->forceFill($rosterChanges)->saveQuietly();
            }
        }

        AuditLogger::log('alumni.profile_edited', 'alumni', $alumnus->id, ['alumnus' => $alumnus->name]);

        return new AlumniResource($alumnus->fresh()->load('alumniProfile'));
    }

    /** Activate / disable an alumnus account. */
    public function updateStatus(UpdateUserStatusRequest $request, User $alumnus): AlumniResource
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $this->accounts->updateStatus($alumnus, $request->validated('status'), 'hr_admin', $request->user()->name);

        return new AlumniResource($alumnus->fresh()->load('alumniProfile'));
    }

    /** Lock / unlock an alumnus account. */
    public function updateLock(Request $request, User $alumnus): AlumniResource
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $this->accounts->updateLock($alumnus, $request->boolean('locked'));

        return new AlumniResource($alumnus->fresh()->load('alumniProfile'));
    }

    /**
     * Reset an alumnus's password. Never generates or transmits a plaintext
     * password — revokes all active sessions and points them to the existing
     * self-service Forgot Password (OTP) flow.
     */
    public function resetPassword(User $alumnus): JsonResponse
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $this->accounts->resetPassword($alumnus, 'hr_admin');

        return response()->json(['message' => 'Password reset. The alumnus has been notified to set a new one.']);
    }

    /** Soft-delete an alumnus. */
    public function destroy(Request $request, User $alumnus): JsonResponse
    {
        abort_unless($alumnus->role === 'alumni', 404);

        $this->accounts->destroy($alumnus, 'hr_admin', $request->user()->name);

        return response()->json(['message' => 'Alumnus removed.']);
    }

    /**
     * Granular, database-driven capability toggles. Each field present in the
     * payload is checked against its own permission and audit-logged
     * individually — a partial payload only touches the fields it includes.
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
}
