<?php

namespace App\Http\Requests\SuperAdmin;

use App\Support\PasswordPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSuperAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by the route's permission middleware
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            // Email is unique PER ROLE, matching the users_role_email_unique_idx
            // index: a Super Admin and an alumnus may share an inbox, two Super
            // Admins may not.
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->where('role', 'super_admin')],
            'vkollabId' => ['required', 'string', 'max:40', 'unique:users,vkollab_id'],
            'password' => PasswordPolicy::rules(),
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'A Super Admin with this email address already exists.',
            'vkollabId.unique' => 'This EMP ID already exists. Please use a different EMP ID.',
        ];
    }
}
