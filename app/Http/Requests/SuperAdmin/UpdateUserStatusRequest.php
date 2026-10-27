<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by route middleware (role + permission)
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['active', 'disabled'])],
        ];
    }
}
