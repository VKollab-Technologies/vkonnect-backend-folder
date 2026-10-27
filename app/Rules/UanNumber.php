<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A UAN is exactly 12 digits — no letters, spaces or symbols.
 *
 * The Universal Account Number issued by EPFO is a fixed 12-digit identifier.
 * The column previously accepted any string up to 20 characters, so a typo or
 * a pasted "UAN: 1234..." label stored cleanly and only failed later, wherever
 * the number was actually used.
 */
class UanNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match('/^[0-9]{12}$/', $value)) {
            $fail('Please enter a valid 12-digit UAN (digits only).');
        }
    }
}
