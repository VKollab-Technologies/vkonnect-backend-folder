<?php

use App\Models\DocumentCategory;
use Illuminate\Database\Migrations\Migration;

/**
 * NOTE: shares the timestamp 2026_07_30_060000 with fold_pan_aadhaar_into_personal_documents.
 * Laravel orders same-timestamp migrations by full filename, so
 * fold_pan_aadhaar currently runs before rename_salary_slips. Neither
 * depends on the other; do not introduce a dependency without giving one
 * of them a later timestamp.
 *
 * Drops the redundant "(Last 6 Months)" qualifier from the Salary Slips
 * category name — the period is already expressed by the per-document
 * financial year, not the category label. Slug/filename_code are untouched. */
return new class extends Migration
{
    public function up(): void
    {
        DocumentCategory::where('slug', 'salary-slips')->update(['name' => 'Salary Slips']);
    }

    public function down(): void
    {
        DocumentCategory::where('slug', 'salary-slips')->update(['name' => 'Salary Slips (Last 6 Months)']);
    }
};
