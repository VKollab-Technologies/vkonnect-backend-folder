<?php

namespace App\Http\Requests\Account;

use App\Support\PasswordPolicy;
use Illuminate\Foundation\Http\FormRequest;

/** Authenticated self-service password change — shared by every realm. */
class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currentPassword' => ['required', 'string'],
            'password' => PasswordPolicy::rules(),
        ];
    }
}
