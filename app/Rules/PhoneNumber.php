<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/** Exactly 10 digits — no letters, symbols, or spaces. */
class PhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match('/^[0-9]{10}$/', $value)) {
            $fail('Please enter a valid 10-digit mobile number.');
        }
    }
}
