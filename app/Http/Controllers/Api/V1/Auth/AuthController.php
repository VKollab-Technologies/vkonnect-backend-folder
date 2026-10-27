<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\AuthService;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Support\LoginChallenge;
use Illuminate\Validation\ValidationException;

/**
 * Realm-aware authentication. The realm (super_admin | hr_admin | alumni) is
 * supplied as a route default, so the same controller serves all three
 * isolated login namespaces. All three use the same two-step sign-in:
 * password → emailed OTP → token.
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $auth,
        private readonly OtpService $otp,
    ) {}

    /**
     * Step 1 — verify VKollab ID + password, then email a one-time code.
     *
     * All three realms are two-step. Alumni used to be password-only, which
     * made the realm holding employment documents — PAN, Form 16, payslips —
     * the one protected by a single factor. The password alone is also the
     * weakest of the three in practice, since it is the only one users set
     * themselves during self-service activation.
     *
     * No realm branching here on purpose: verifyOtp() and resendOtp() were
     * already realm-agnostic, so a branch in this method was the only thing
     * making alumni different, and the only thing that could drift.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $realm = $request->route('realm');

        $user = $this->auth->verifyCredentials(
            $realm,
            $request->validated('vkollabId'),
            $request->validated('password'),
        );

        // Must be recorded BEFORE the code is issued: it is what the OTP
        // endpoints check to prove this step happened at all.
        LoginChallenge::mark($user, $realm);

        $code = $this->otp->issue($user->email, 'login', $user->name, $user->id, $realm);

        return response()->json([
            'otpRequired' => true,
            'vkollabId' => $user->vkollab_id,
            // Masked for display only — the registered email is never exposed.
            'maskedEmail' => OtpService::maskEmail($user->email),
            // So the entry screen can count down to the deadline the server will
            // actually enforce, rather than assuming the TTL from its own clock.
            'otpExpiresAt' => $this->otp->expiresAtFor($user->email, 'login'),
            'message' => 'A verification code has been sent to your registered email.',
            'devCode' => $this->otp->exposesCode() ? $code : null,
        ]);
    }

    /** Step 2 — verify the OTP and issue the session token. */
    public function verifyOtp(Request $request): JsonResponse
    {
        $realm = $request->route('realm');
        $data = $request->validate([
            'vkollabId' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);

        $user = User::whereVkollabId($data['vkollabId'])->first();
        if (! $user || $user->role !== $realm || $user->status !== 'active') {
            AuditLogger::logFailure('auth.login', 'auth', ['vkollabId' => $data['vkollabId'], 'realm' => $realm]);
            throw ValidationException::withMessages(['code' => ['Unable to complete sign in.']]);
        }

        // A valid code is not on its own permission to sign in — the password
        // step has to have happened, and recently. Without this check the OTP
        // was the only factor rather than the second one.
        $this->requirePasswordStep($user, $realm);

        // Checked here as well as in verifyCredentials: this endpoint issues a
        // token, and an administrator who locks an account must not have that
        // undone by the holder simply completing an OTP they already hold.
        if ($user->isLocked()) {
            AuditLogger::logFor($user, 'auth.login', 'auth', ['reason' => 'locked'], 'failure');
            throw ValidationException::withMessages([
                'code' => ['Account temporarily locked. Try again later.'],
            ]);
        }

        try {
            $this->otp->verify($user->email, 'login', $data['code']);
        } catch (ValidationException $e) {
            AuditLogger::logFor($user, 'auth.login', 'auth', ['reason' => 'invalid_otp'], 'failure');
            throw $e;
        }

        // One password entry authorizes one sign-in.
        LoginChallenge::consume($user, $realm);

        $result = $this->auth->issueToken($user, $realm);

        AuditLogger::logFor($user, 'auth.login', 'auth', ['realm' => $realm]);

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
            'expiresAt' => $result['expiresAt'],
        ]);
    }

    /** Resend the login OTP (by VKollab ID). */
    public function resendOtp(Request $request): JsonResponse
    {
        $data = $request->validate(['vkollabId' => ['required', 'string']]);

        $user = User::whereVkollabId($data['vkollabId'])->where('status', 'active')->first();

        // Same gate as verifyOtp. Unauthenticated resend was the more direct
        // half of the bypass: it emailed a working login code to anyone who
        // knew an EMP ID, with no password and no rate cost to the attacker
        // beyond the route throttle.
        if ($user && LoginChallenge::passed($user, $user->role)) {
            $code = $this->otp->issue($user->email, 'login', $user->name, $user->id, $user->role);

            return response()->json([
                'message' => 'A new code has been sent.',
                'maskedEmail' => OtpService::maskEmail($user->email),
                // So the entry screen can count down to the deadline the server will
                // actually enforce, rather than assuming the TTL from its own clock.
                'otpExpiresAt' => $this->otp->expiresAtFor($user->email, 'login'),
                'devCode' => $this->otp->exposesCode() ? $code : null,
            ]);
        }

        // Neutral response — never reveal whether the account exists, nor
        // whether it is mid-login. A caller who legitimately reached this
        // point has a marker; one who did not learns nothing either way.
        return response()->json(['message' => 'If the account exists, a new code has been sent.']);
    }

    /**
     * Reject an OTP step that no password step preceded.
     *
     * Deliberately a distinct error code from a bad OTP: the client has to
     * send the user back to the password screen, and "invalid code" would have
     * them retyping a code that will never work.
     */
    private function requirePasswordStep(User $user, string $realm): void
    {
        if (LoginChallenge::passed($user, $realm)) {
            return;
        }

        AuditLogger::logFor($user, 'auth.login', 'auth', ['reason' => 'password_step_missing'], 'failure');

        throw ValidationException::withMessages([
            'code' => ['Your sign-in session has expired. Please enter your password again.'],
        ]);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        AuditLogger::log('auth.logout', 'auth');
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Signed out.']);
    }
}
