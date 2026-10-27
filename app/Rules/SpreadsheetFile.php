<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Enterprise data-governance rule: a DATA file must be Excel (.xlsx) or CSV
 * (.csv) — nothing else. Validation is content-aware (not extension-only):
 * extension + real MIME type + magic bytes + non-empty + size, to defeat
 * disguised / malicious / corrupt uploads. Used for every import.
 */
class SpreadsheetFile implements ValidationRule
{
    /** Content types finfo legitimately reports for .xlsx / .csv. */
    private const ALLOWED_MIME = [
        'text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip', 'application/octet-stream',
    ];

    /**
     * 20 MB. The single source of truth for import size — callers pass no
     * argument. Override only for a genuinely different limit, never to
     * restate this one.
     */
    public function __construct(private readonly int $maxKb = 20480) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('The file could not be uploaded. Please try again.');

            return;
        }

        $size = (int) $value->getSize();
        if ($size <= 0) {
            $fail('The file is empty. Upload an Excel or CSV file that contains data.');

            return;
        }
        if ($size > $this->maxKb * 1024) {
            $fail('The file is too large. Maximum allowed size is '.intdiv($this->maxKb, 1024).' MB.');

            return;
        }

        $ext = strtolower($value->getClientOriginalExtension());
        if (! in_array($ext, ['xlsx', 'csv'], true)) {
            $fail('Only Excel (.xlsx) and CSV (.csv) files are allowed for data uploads. ".'.$ext.'" is not permitted.');

            return;
        }

        $head = $this->magicBytes($value);
        $isZip = str_starts_with($head, "PK\x03\x04");           // .xlsx is a zip container
        $isPdf = str_starts_with($head, '%PDF');
        $isExe = str_starts_with($head, 'MZ') || str_starts_with($head, "\x7FELF");

        if ($isPdf) {
            $fail('PDF files are not allowed for data uploads. Use the predefined Excel/CSV template.');

            return;
        }
        if ($isExe) {
            $fail('This file looks like an executable and was blocked for security reasons.');

            return;
        }
        if ($ext === 'xlsx' && ! $isZip) {
            $fail('This .xlsx file is not a valid Excel workbook (it may be corrupted or renamed).');

            return;
        }
        if ($ext === 'csv' && $isZip) {
            $fail('This .csv file is actually a different file type and was rejected.');

            return;
        }

        $mime = (string) $value->getMimeType();
        if ($mime !== '' && ! in_array($mime, self::ALLOWED_MIME, true)) {
            $fail('Unsupported file content ('.$mime.'). Only Excel (.xlsx) and CSV (.csv) are allowed.');
        }
    }

    private function magicBytes(UploadedFile $file): string
    {
        $bytes = @file_get_contents($file->getRealPath(), false, null, 0, 8);

        return $bytes === false ? '' : $bytes;
    }
}
