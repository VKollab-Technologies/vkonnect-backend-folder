<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:160'],
            'organization' => ['required', 'string', 'max:160'],
            'location' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'internship'])],
            'isRemote' => ['sometimes', 'boolean'],
            // Ceiling mirrors DESCRIPTION_MAX in the HR Jobs form. The column
            // is TEXT, so this is a sanity bound rather than a storage limit.
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'requirements' => ['nullable', 'string'],
            'closesAt' => ['nullable', 'date', 'after:today'],
        ];
    }
}
