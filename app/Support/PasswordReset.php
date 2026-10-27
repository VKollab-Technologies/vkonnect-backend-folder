<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Admin-initiated password reset.
 *
 * Replaces the password with a cryptographically random value that is never
 * shown, emailed, logged or stored in plaintext, and revokes every session.
 * The account can only be recovered through the OTP-backed Forgot Password
 * flow, which proves control of the registered mailbox.
 *
 * Scrambling rather than emailing a temporary password is deliberate: a
 * temporary password is a plaintext credential sitting in a mailbox, and the
 * recovery flow that would replace it already exists and is stronger. The one
 * place this codebase does email a plaintext password — AdminWelcomeMail — is
 * for accounts that have no mailbox-proven identity yet, which is not the case
 * here.
 */
class PasswordReset
{
    /**
     * Invalidate the current password and end every session.
     *
     * Also clears lockout state: the account is being handed back to its owner
     * via Forgot Password, and leaving a lock in place would block the very
     * recovery the reset email tells them to use.
     */
    public static function scramble(User $user): void
    {
        $user->forceFill([
            // 64 random chars, hashed and immediately discarded. No caller
            // receives it; there is deliberately no return value to misuse.
            'password' => Hash::make(Str::password(64)),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();

        $user->tokens()->delete();
    }
}
