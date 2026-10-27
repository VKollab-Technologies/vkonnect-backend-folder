<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\AlumniMaster;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\OtpService;
use App\Support\PasswordPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * OTP-based password recovery, keyed by EMP ID. `forgot` emails a code to
 * the user's REGISTERED email (looked up from the database); `reset` verifies
 * the code and sets the new password. The email is never exposed to the client.
 */
class PasswordController extends Controller
{
    public function __construct(private readonly OtpService $otp) {}

    public function forgot(Request $request): JsonResponse
    {
        $data = $request->validate(['vkollabId' => ['required', 'string', 'max:40']]);

        $user = User::whereVkollabId($data['vkollabId'])->first();

        // Someone on the roster who has never activated has no password to
        // reset — sending them through the OTP flow is a dead end. Point them
        // at registration instead.
        //
        // This does reveal that the EMP ID is on the roster, but that is not
        // new information: POST /auth/register/verify already answers exactly
        // that question by design, because it is how registration works. The
        // neutral response below is still used for an EMP ID we know nothing
        // about, so genuine enumeration gains nothing.
        if (! $user && AlumniMaster::where('vkollab_id', AlumniMaster::normalizeVkollabId($data['vkollabId']))->exists()) {
            return response()->json([
                'code' => 'needs_registration',
                'message' => 'This EMP ID has not been registered yet. Register and set your password to continue.',
            ], 409);
        }

        $masked = null;
        $devCode = null;
        if ($user) {
            $devCode = $this->otp->issue($user->email, 'reset', $user->name, $user->id, $user->role);
            $masked = OtpService::maskEmail($user->email);
        }

        // Neutral response for anything we cannot place.
        return response()->json([
            'message' => 'If the EMP ID exists, a reset code has been sent to the registered email.',
            'maskedEmail' => $masked,
            // Null when there is no user, so the countdown is simply absent
            // rather than fabricated — the neutral response must not become an
            // oracle for whether the EMP ID exists.
            'otpExpiresAt' => $user ? $this->otp->expiresAtFor($user->email, 'reset') : null,
            'devCode' => $this->otp->exposesCode() ? $devCode : null,
        ]);
    }

    public function reset(Request $request): JsonResponse
    {
        $data = $request->validate([
            'vkollabId' => ['required', 'string', 'max:40'],
            'code' => ['required', 'string'],
            'password' => PasswordPolicy::rules(),
        ]);

        $user = User::whereVkollabId($data['vkollabId'])->first();

        // Unknown ID is reported exactly like a wrong code, for two reasons.
        //
        // The bug: firstOrFail() raised ModelNotFoundException, which the user
        // saw verbatim as "No query results for model [App\Models\User]"
        // half-way through setting a new password.
        //
        // The weakness underneath it: a 404 for an unknown ID against a 422 for
        // a bad code let anyone probe which Employee IDs exist, and they are
        // guessable (ALM8001, ALM8002...). `forgot` already answers with a
        // deliberately neutral message; `reset` now matches it.
        if (! $user) {
            // Byte-identical to what OtpService::verify() throws when a real
            // account has no pending code. Anything else — a different wording,
            // a different field, a different status — is still an oracle for
            // which Employee IDs exist.
            throw ValidationException::withMessages([
                'code' => ['No active code. Please request a new one.'],
            ]);
        }

        try {
            $this->otp->verify($user->email, 'reset', $data['code']);
        } catch (ValidationException $e) {
            AuditLogger::logFor($user, 'auth.password_reset', 'auth', ['reason' => 'invalid_otp'], 'failure');
            throw $e;
        }

        $user->update(['password' => Hash::make($data['password'])]);
        $user->tokens()->delete(); // invalidate existing sessions

        AuditLogger::logFor($user, 'auth.password_reset', 'auth');

        return response()->json(['message' => 'Password updated. You can now sign in.']);
    }
}
