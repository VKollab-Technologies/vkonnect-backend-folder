<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'body' => ['required', 'string', 'min:5'],
            'audience' => ['sometimes', Rule::in(['all', 'alumni', 'hr'])],
            'publish' => ['sometimes', 'boolean'],
        ];
    }
}
