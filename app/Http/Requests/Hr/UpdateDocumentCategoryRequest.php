<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by route permission middleware
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['sometimes', 'string', 'max:80'],
            'slug' => [
                'sometimes',
                'string',
                'max:80',
                'alpha_dash',
                Rule::unique('document_categories', 'slug')->ignore($category),
            ],
            'filenameCode' => [
                'sometimes',
                'string',
                'max:40',
                'regex:/^[A-Z0-9_]+$/',
                Rule::unique('document_categories', 'filename_code')->ignore($category),
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'sortOrder' => ['sometimes', 'integer', 'min:0', 'max:32767'],
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
