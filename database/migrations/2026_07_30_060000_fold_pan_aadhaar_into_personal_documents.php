<?php

use App\Models\DocumentCategory;
use Illuminate\Database\Migrations\Migration;

/**
 * NOTE: shares the timestamp 2026_07_30_060000 with rename_salary_slips_category.
 * Laravel orders same-timestamp migrations by full filename, so
 * fold_pan_aadhaar currently runs before rename_salary_slips. Neither
 * depends on the other; do not introduce a dependency without giving one
 * of them a later timestamp.
 *
 * PAN and Aadhaar stop being their own top-level categories — they're now
 * uploaded/viewed under "Personal Documents" like any other HR-created
 * custom document (distinguished by their document `title`, e.g. "PAN
 * Card"), rather than each getting a dedicated category tile. Personal
 * Documents was already a genuine peer category (see
 * 2026_07_30_050000_add_personal_documents_category); this just folds PAN's
 * and Aadhaar's existing documents into it and removes the two now-redundant
 * category rows — same fold-and-delete pattern as
 * 2026_07_30_010000_remap_document_categories_to_personal_documents.
 */
return new class extends Migration
{
    private array $foldSlugs = ['pan', 'aadhaar'];

    public function up(): void
    {
        $personalDocumentsId = DocumentCategory::where('slug', 'personal-documents')->value('id');

        foreach ($this->foldSlugs as $slug) {
            $category = DocumentCategory::where('slug', $slug)->first();
            if (! $category) {
                continue;
            }
            $category->documents()->withTrashed()->update(['category_id' => $personalDocumentsId]);
            $category->delete();
        }
    }

    public function down(): void
    {
        // One-way: PAN/Aadhaar documents already folded into Personal
        // Documents aren't worth splitting back out into recreated
        // categories with no reliable way to tell them apart from any other
        // Personal Documents upload made since.
    }
};
