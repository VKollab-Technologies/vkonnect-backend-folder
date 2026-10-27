<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Enterprise data-governance rule: a DOCUMENT file must be a real PDF — and
 * only the document module accepts PDFs. Content-aware: extension + MIME +
 * "%PDF" magic bytes + non-empty + size, to reject disguised / corrupt /
 * malicious uploads.
 */
class PdfFile implements ValidationRule
{
    public function __construct(private readonly int $maxKb = 10240) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('The file could not be uploaded. Please try again.');

            return;
        }

        $size = (int) $value->getSize();
        if ($size <= 0) {
            $fail('The file is empty. Upload a valid PDF document.');

            return;
        }
        if ($size > $this->maxKb * 1024) {
            $fail('The file is too large. Maximum allowed size is '.intdiv($this->maxKb, 1024).' MB.');

            return;
        }

        if (strtolower($value->getClientOriginalExtension()) !== 'pdf') {
            $fail('Only PDF (.pdf) files are allowed for documents.');

            return;
        }

        $head = @file_get_contents($value->getRealPath(), false, null, 0, 5);
        if ($head === false || ! str_starts_with($head, '%PDF')) {
            $fail('This file is not a valid PDF (it may be corrupted or renamed from another format).');

            return;
        }

        $mime = (string) $value->getMimeType();
        if ($mime !== '' && $mime !== 'application/pdf') {
            $fail('Unsupported file content ('.$mime.'). Only PDF documents are allowed.');
        }
    }
}
