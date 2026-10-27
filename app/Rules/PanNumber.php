<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Official Indian PAN format: AAAAA9999A (5 letters, 4 digits, 1 letter).
 * Case-insensitive on input — callers should still normalize to uppercase
 * before persisting (see AlumniMaster::normalizePan()) so stored values are
 * always canonical, but this rule accepts lowercase input rather than
 * rejecting it outright, since the frontend uppercases as the user types.
 */
class PanNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match('/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/', $value)) {
            $fail('Please enter a valid PAN number (Example: ABCDE1234F).');
        }
    }
}
