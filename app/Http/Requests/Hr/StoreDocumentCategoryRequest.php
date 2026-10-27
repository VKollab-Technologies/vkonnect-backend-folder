<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by route permission middleware
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('document_categories', 'slug')],
            'filenameCode' => ['nullable', 'string', 'max:40', 'regex:/^[A-Z0-9_]+$/', Rule::unique('document_categories', 'filename_code')],
            'description' => ['nullable', 'string', 'max:255'],
            'sortOrder' => ['nullable', 'integer', 'min:0', 'max:32767'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.alpha_dash' => 'Slug may only contain letters, numbers, dashes, and underscores.',
            'slug.unique' => 'A category with this slug already exists.',
            'filenameCode.regex' => 'Filename code may only contain uppercase letters, numbers, and underscores.',
            'filenameCode.unique' => 'A category with this filename code already exists.',
        ];
    }
}
