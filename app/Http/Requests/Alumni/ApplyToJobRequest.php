<?php

namespace App\Http\Requests\Alumni;

use Illuminate\Foundation\Http\FormRequest;

class ApplyToJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coverNote' => ['nullable', 'string', 'max:2000'],
            // Resumes are documents — PDF only (content-validated).
            'resume' => ['nullable', new \App\Rules\PdfFile(5120)],
        ];
    }
}
