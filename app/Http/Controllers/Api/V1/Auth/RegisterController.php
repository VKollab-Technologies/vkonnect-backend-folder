<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Support\PanHash;

use App\Http\Controllers\Controller;
use App\Mail\AlumniActivationWelcomeMail;
use App\Models\AlumniMaster;
use App\Models\AuditLog;
use App\Models\User;
use App\Rules\PanNumber;
use App\Services\AuthService;
use App\Services\OtpService;
use App\Support\PasswordPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Enterprise account-activation flow (NOT open signup). An alumnus activates a
 * PRE-EXISTING record from the HR-managed master roster:
 *
 *   1. verify-identity  — EMP ID OR PAN → look up roster → OTP to the
 *                         registered email (never entered or exposed; masked).
 *   2. verify-otp       — confirm the 6-digit code (cache store, expiring,
 *                         single-use; the `database` cache store).
 *   3. set-password     — create the password → activate the account.
 *
 * A short-lived opaque token threads the steps so the client never holds the
 * email or employee record.
 */
class RegisterController extends Controller
{
    private const TOKEN_TTL_MINUTES = 15;

    public function __construct(
        private readonly AuthService $auth,
        private readonly OtpService $otp,
    ) {}

    /** Step 1 — validate identity (EMP ID and/or PAN) against the roster, OTP the registered email. */
    public function verifyIdentity(Request $request): JsonResponse
    {
        $data = $request->validate([
            'vkollabId' => ['required', 'string', 'max:40'],
        ]);

        $vkollabId = AlumniMaster::normalizeVkollabId($data['vkollabId']);

        if (! $vkollabId) {
            throw ValidationException::withMessages([
                'vkollabId' => ['Enter your EMP ID.'],
            ]);
        }

        $master = AlumniMaster::query()
            ->where('vkollab_id', $vkollabId)
            ->first();

        if (! $master) {
            throw ValidationException::withMessages([
                'vkollabId' => ['No alumni record found. Please contact the HR Team.'],
            ]);
        }


        if ($master->isClaimed()) {
            throw ValidationException::withMessages([
                'vkollabId' => ['An account has already been activated for this record. Please sign in instead.'],
            ]);
        }

        if ($master->status !== 'active') {
            throw ValidationException::withMessages([
                'vkollabId' => ['This record is inactive. Please contact HR to activate your account.'],
            ]);
        }

        if (! $master->email) {
            throw ValidationException::withMessages([
                'vkollabId' => ['No email is on file for this record. Please contact HR.'],
            ]);
        }

        // Opaque token threads the multi-step flow without exposing the email.
        $token = Str::random(48);
        Cache::put($this->tokenKey($token), $master->id, now()->addMinutes(self::TOKEN_TTL_MINUTES));

        $this->otp->issue($master->email, 'register', $master->name, null, 'alumni');

        return response()->json([
            'token' => $token,
            'maskedEmail' => OtpService::maskEmail($master->email),
            // So the entry screen can count down to the deadline the server will
            // actually enforce, rather than assuming the TTL from its own clock.
            'otpExpiresAt' => $this->otp->expiresAtFor($master->email, 'register'),
            'message' => 'We sent a verification code to your registered email.',
        ]);
    }

    /** Step 2 — verify the OTP; mark the token as OTP-verified. */
    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);

        $master = $this->resolveToken($data['token']);

        $this->otp->verify($master->email, 'register', $data['code']); // throws on failure

        Cache::put($this->verifiedKey($data['token']), $master->id, now()->addMinutes(self::TOKEN_TTL_MINUTES));

        return response()->json(['token' => $data['token'], 'verified' => true]);
    }

    /** Resend — issue a NEW OTP (invalidates the previous one) to the same email. */
    public function resendOtp(Request $request): JsonResponse
    {
        $data = $request->validate(['token' => ['required', 'string']]);

        $master = $this->resolveToken($data['token']);

        $this->otp->issue($master->email, 'register', $master->name, null, 'alumni'); // overwrites the prior code in the cache store

        return response()->json([
            'maskedEmail' => OtpService::maskEmail($master->email),
            // So the entry screen can count down to the deadline the server will
            // actually enforce, rather than assuming the TTL from its own clock.
            'otpExpiresAt' => $this->otp->expiresAtFor($master->email, 'register'),
            'message' => 'A new code has been sent to your registered email.',
        ]);
    }

    /** Step 3 — create the password and activate the account. */
    public function setPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'password' => PasswordPolicy::rules(),
            'passwordConfirmation' => ['required', 'same:password'],
            'termsAccepted' => ['required', 'accepted'],
            'privacyAccepted' => ['required', 'accepted'],
        ]);

        $master = $this->resolveToken($data['token']);

        // OTP must have been verified in this session.
        if (Cache::get($this->verifiedKey($data['token'])) !== $master->id) {
            throw ValidationException::withMessages([
                'token' => ['Please verify the code sent to your email before setting a password.'],
            ]);
        }

        // Guard against a race where the record was claimed after step 1.
        if ($master->isClaimed()) {
            throw ValidationException::withMessages([
                'token' => ['An account has already been activated for this record.'],
            ]);
        }

        $user = $this->auth->activateFromMaster($master, $data['password']);

        \App\Services\AuditLogger::logFor($user, 'auth.registered', 'auth', [
            'vkollabId' => $user->vkollab_id,
            'termsAccepted' => true,
            'privacyAccepted' => true,
        ]);

        Cache::forget($this->tokenKey($data['token']));
        Cache::forget($this->verifiedKey($data['token']));

        // Activation can only succeed once per master record (a second attempt
        // hits the isClaimed() guard above before reaching here), so this welcome
        // email is inherently sent exactly once — never resent.
        $this->sendActivationWelcomeEmail($user, $master);

        return response()->json([
            'message' => 'Your account is active. You can now sign in with your EMP ID and new password.',
        ]);
    }

    /** Best-effort, one-time welcome email — never blocks/undoes account activation on failure. */
    private function sendActivationWelcomeEmail(User $user, AlumniMaster $master): void
    {
        $meta = ['email' => OtpService::maskEmail($user->email)];

        try {
            Mail::to($user->email)->queue(new AlumniActivationWelcomeMail(
                name: $user->name,
                vkollabId: $user->vkollab_id,
                department: $master->department,
                designation: $master->designation,
                joinedOn: $master->joined_on?->format('d M Y'),
                exitedOn: $master->exited_on?->format('d M Y'),
            ));

            AuditLog::create([
                'user_id' => $user->id,
                'actor_name' => $user->name,
                'action' => 'email.welcome_sent',
                'status' => 'success',
                'module' => 'auth',
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'meta' => $meta + ['status' => 'queued'],
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Activation welcome mail dispatch failed: '.$e->getMessage());

            AuditLog::create([
                'user_id' => $user->id,
                'actor_name' => $user->name,
                'action' => 'email.welcome_sent',
                'status' => 'failure',
                'module' => 'auth',
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'meta' => $meta + ['status' => 'failed', 'error' => $e->getMessage()],
                'created_at' => now(),
            ]);
        }
    }

    // ---- helpers -------------------------------------------------------------

    private function tokenKey(string $token): string
    {
        return 'reg:tok:'.sha1($token);
    }

    private function verifiedKey(string $token): string
    {
        return 'reg:verified:'.sha1($token);
    }

    /** Resolve a registration token to its master record, or fail with 422. */
    private function resolveToken(string $token): AlumniMaster
    {
        $masterId = Cache::get($this->tokenKey($token));
        $master = $masterId ? AlumniMaster::find($masterId) : null;

        if (! $master) {
            throw ValidationException::withMessages([
                'token' => ['Your registration session has expired. Please start again.'],
            ]);
        }

        return $master;
    }
}
