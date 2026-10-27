<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\AuditLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * One-time password issuing + verification for login, registration, and
 * password reset.
 *
 * OTPs are TRANSIENT, so they live in the CACHE STORE with a short TTL rather
 * than in a business table. On this deployment that store is `database`, which
 * means the `cache` table in MySQL.
 * Codes are hashed, single-use, and attempt-limited.
 *
 * Email delivery uses the configured mailer (the `log` driver by default). Set
 * MAIL_MAILER=smtp + credentials in .env for real delivery. In non-production
 * the issued code is also returned to the caller so the flow is testable.
 */
class OtpService
{
    // Intentionally a fixed platform constant, not admin-configurable: OTP
    // validity is a security invariant of the auth flow, not a per-tenant
    // preference — an admin-editable "otp_ttl_minutes" setting existed
    // previously but was never wired to this value, so it was removed rather
    // than left as a misleading no-op (see 2026_07_27_000001_remove_dead_security_settings).
    /** Public so callers and tests state the window rather than hardcoding 10. */
    public const TTL_MINUTES = 10;
    private const MAX_ATTEMPTS = 5;

    private function key(string $email, string $purpose): string
    {
        return 'otp:'.$purpose.':'.sha1(mb_strtolower(trim($email)));
    }

    /**
     * Generate, store (hashed, in the cache store) and email a fresh OTP.
     * Returns the code.
     *
     * The cache store is `database`, so codes live
     * in the `cache` table with a TTL and are swept when it expires.
     *
     * @param  ?string  $name  Alumni/user display name, for email personalization.
     * @param  ?string  $userId  FK to `users.id` for audit linkage, when one exists
     *                           yet (registration OTP is issued before the User
     *                           row exists, so this is null for that purpose).
     * @param  ?string  $role  Realm the OTP belongs to (alumni/hr_admin/super_admin)
     *                         — drives the email's role-aware badge/accent only.
     */
    public function issue(string $email, string $purpose, ?string $name = null, ?string $userId = null, ?string $role = null): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(self::TTL_MINUTES);

        Cache::put($this->key($email, $purpose), [
            'hash' => Hash::make($code),
            'attempts' => 0,
            'expiresAt' => $expiresAt->getTimestamp(),
        ], $expiresAt);

        $this->send($email, $purpose, $code, $name, $userId, $role);

        return $code;
    }

    /**
     * When the outstanding code for this address expires, ISO-8601, or null if
     * there is none.
     *
     * Read from the stored entry rather than recomputed from TTL_MINUTES: a
     * resend replaces the entry, and a countdown derived from "now + TTL" would
     * drift from the deadline the server will actually enforce.
     */
    public function expiresAtFor(string $email, string $purpose): ?string
    {
        $otp = Cache::get($this->key($email, $purpose));

        return isset($otp['expiresAt'])
            ? Carbon::createFromTimestamp($otp['expiresAt'])->toIso8601String()
            : null;
    }

    /** Verify a code; throws ValidationException on failure. Consumes on success. */
    public function verify(string $email, string $purpose, string $code): void
    {
        $key = $this->key($email, $purpose);
        $otp = Cache::get($key);

        $fail = fn (string $msg) => throw ValidationException::withMessages(['code' => [$msg]]);

        if (! $otp) {
            $fail('No active code. Please request a new one.');
        }

        if (now()->getTimestamp() > ($otp['expiresAt'] ?? 0)) {
            Cache::forget($key);
            $fail('This code has expired. Please request a new one.');
        }

        if (($otp['attempts'] ?? 0) >= self::MAX_ATTEMPTS) {
            Cache::forget($key);
            $fail('Too many incorrect attempts. Please request a new code.');
        }

        if (! Hash::check($code, $otp['hash'])) {
            $otp['attempts'] = ($otp['attempts'] ?? 0) + 1;
            $remaining = max(1, ($otp['expiresAt'] ?? 0) - now()->getTimestamp());
            Cache::put($key, $otp, $remaining);
            $fail('Incorrect code. Please try again.');
        }

        Cache::forget($key); // single-use
    }

    /**
     * Whether the OTP may be surfaced to the client. Always false now that real
     * email delivery is configured — codes are delivered only to the inbox.
     */
    public function exposesCode(): bool
    {
        return false;
    }

    /** Partially mask an email for display, e.g. al***@vkollab.com. */
    public static function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) {
            return '***';
        }
        [$local, $domain] = explode('@', $email, 2);
        $visible = mb_substr($local, 0, min(2, mb_strlen($local)));

        return $visible.str_repeat('*', 3).'@'.$domain;
    }

    /** Queue the OTP email for delivery (picked up by `queue:work`) + audit it. */
    private function send(string $email, string $purpose, string $code, ?string $name, ?string $userId, ?string $role = null): void
    {
        $action = match ($purpose) {
            'register' => 'email.registration_otp_sent',
            'reset' => 'email.forgot_password_otp_sent',
            'login' => 'email.login_otp_sent',
            default => 'email.otp_sent',
        };

        try {
            Mail::to($email)->send(new OtpMail($code, $purpose, self::TTL_MINUTES, $name, $role));

            AuditLog::create([
                'user_id' => $userId,
                'actor_name' => $name,
                'action' => $action,
                'module' => 'auth',
                'meta' => ['email' => self::maskEmail($email), 'status' => 'queued'],
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Never let mail delivery break the auth flow — the OTP is already
            // in the cache store and (in non-prod) returned to the client.
            Log::warning('OTP mail dispatch failed: '.$e->getMessage());

            AuditLog::create([
                'user_id' => $userId,
                'actor_name' => $name,
                'action' => $action,
                'module' => 'auth',
                'meta' => ['email' => self::maskEmail($email), 'status' => 'failed', 'error' => $e->getMessage()],
                'created_at' => now(),
            ]);
        }
    }
}
