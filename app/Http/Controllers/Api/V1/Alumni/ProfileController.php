<?php

namespace App\Http\Controllers\Api\V1\Alumni;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\ChangePasswordRequest;
use App\Http\Resources\AlumniProfileResource;
use App\Models\AlumniProfile;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Support\Disks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * "My Information" — entirely read-only from the alumni side. Every field
 * shown here (General/PF Information/Contact) is HR-managed, set via
 * Hr\AlumniController / SuperAdmin\AlumniController (Alumni Control) or
 * SuperAdmin\AlumniMasterController (the pre-activation roster) — there is
 * deliberately no update() method on this controller. The profile photo is
 * the one thing that stays self-service (it isn't one of the HR-managed
 * fields), handled by the upload/delete methods below.
 */
class ProfileController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function show(Request $request): AlumniProfileResource
    {
        abort_unless($request->user()->canAccessProfile(), 403, 'Profile access has been disabled for your account. Contact HR.');

        return new AlumniProfileResource($this->resolveProfile($request)->load('user'));
    }

    /** Upload (or replace) the current user's profile photo. */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], // 4 MB
        ]);

        $user = $request->user();
        $old = $user->avatar_path;

        $path = Disks::documents()->putFile("avatars/{$user->id}", $request->file('avatar'));
        $user->forceFill(['avatar_path' => $path])->save();

        if ($old && $old !== $path) {
            Disks::documents()->delete($old);
        }

        AuditLogger::log('profile.avatar_updated', 'profile', $user->id);

        return response()->json(['avatarUrl' => $user->avatarUrl()]);
    }

    /** Remove the current user's profile photo. */
    public function deleteAvatar(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user->avatar_path) {
            Disks::documents()->delete($user->avatar_path);
            $user->forceFill(['avatar_path' => null])->save();
            AuditLogger::log('profile.avatar_removed', 'profile', $user->id);
        }

        return response()->json(['avatarUrl' => null]);
    }

    /**
     * Authenticated self-service password change, available to every realm —
     * requires re-verifying the current password. Keeps the session that made
     * this request valid; revokes every other active token for this user, so
     * a stolen/stale token elsewhere is cut off immediately. Sends a
     * confirmation email so the user notices if they didn't make the change.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (! Hash::check($data['currentPassword'], $user->password)) {
            throw ValidationException::withMessages([
                'currentPassword' => 'Current password is incorrect.',
            ]);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        $currentTokenId = $user->currentAccessToken()?->id;
        $user->tokens()->when($currentTokenId, fn ($q) => $q->whereKeyNot($currentTokenId))->delete();

        AuditLogger::log('account.password_changed', 'account', $user->id);

        $this->notifications->email(
            $user->email,
            'Your VKONNECT password was changed',
            'Password changed',
            "Your password was just changed. If this wasn't you, contact your administrator immediately.",
        );

        return response()->json(['message' => 'Password updated.']);
    }

    /**
     * Stream a user's avatar. Authorized solely by the valid signature (the
     * `signed` middleware) — used directly in <img> tags, so no bearer token.
     */
    public function streamAvatar(User $user): StreamedResponse
    {
        abort_unless($user->avatar_path && Disks::documents()->exists($user->avatar_path), 404);

        return Disks::documents()->response($user->avatar_path);
    }

    /** Resolve (or lazily create) the current alumnus' profile. */
    private function resolveProfile(Request $request): AlumniProfile
    {
        return AlumniProfile::firstOrCreate(['user_id' => $request->user()->id]);
    }
}
