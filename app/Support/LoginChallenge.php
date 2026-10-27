<?php

namespace App\Support;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\Cache;

/**
 * Proof that the password step actually happened.
 *
 * Without this the two-step login was not two-factor at all: verify-otp and
 * resend-otp both accepted an EMP ID alone, so anyone who knew an ID and could
 * read the mailbox could mint a token having never presented a password — and
 * because the lockout counter lives in AuthService::verifyCredentials, that
 * path also skipped per-account lockout and the per-IP brute-force counter
 * entirely. The OTP was not a second factor; it was the only one.
 *
 * The marker is set only after verifyCredentials() passes, and is required by
 * both OTP endpoints. It is keyed to user AND realm so a marker earned in one
 * realm cannot authorize an OTP in another.
 *
 * TTL deliberately matches the OTP's own lifetime rather than being shorter:
 * a 5-minute marker against a 10-minute code would let a code stop working
 * halfway through the validity the email advertises, which reads as a broken
 * portal rather than an expiry. The rule is simply "you have OTP_TTL minutes
 * from entering your password to finish signing in".
 */
class LoginChallenge
{
    public static function ttlMinutes(): int
    {
        return OtpService::TTL_MINUTES;
    }

    private static function key(User $user, string $realm): string
    {
        return "login_pw_verified:{$realm}:{$user->id}";
    }

    /** Record that this user just proved their password for this realm. */
    public static function mark(User $user, string $realm): void
    {
        Cache::put(self::key($user, $realm), true, now()->addMinutes(self::ttlMinutes()));
    }

    public static function passed(User $user, string $realm): bool
    {
        return (bool) Cache::get(self::key($user, $realm));
    }

    /**
     * Single-use: the marker authorizes one sign-in, not a window in which any
     * number of tokens can be minted.
     */
    public static function consume(User $user, string $realm): void
    {
        Cache::forget(self::key($user, $realm));
    }

    /** Dropped on logout and on password change, so a stale marker cannot be reused. */
    public static function clear(User $user, string $realm): void
    {
        self::consume($user, $realm);
    }
}
