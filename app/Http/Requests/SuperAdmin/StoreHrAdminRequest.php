<?php

namespace App\Http\Requests\SuperAdmin;

use App\Support\PasswordPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHrAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->where('role', 'hr_admin')],
            'vkollabId' => ['required', 'string', 'max:40', 'unique:users,vkollab_id'],
            'department' => ['nullable', 'string', 'max:120'],
            'password' => PasswordPolicy::rules(),
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'vkollabId.unique' => 'This EMP ID already exists. Please use a different EMP ID.',
        ];
    }
}
