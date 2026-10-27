<?php

namespace App\Support;

use Illuminate\Validation\Rules\Password;

/**
 * Enterprise password complexity policy shared by every request that sets or
 * changes a password (registration, admin-created accounts, forgot/reset,
 * self-service change). Mirrors the frontend `strongPassword` Zod schema in
 * frontend/src/modules/auth/schemas.ts.
 */
class PasswordPolicy
{
    /** @return array<int, string|Password> */
    public static function rules(): array
    {
        return ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()];
    }
}
