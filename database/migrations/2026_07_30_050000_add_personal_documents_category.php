<?php

use App\Models\DocumentCategory;
use Illuminate\Database\Migrations\Migration;

/**
 * "Personal Documents" was previously only a UI section label wrapping ALL 6
 * categories as if it were their parent — not a real category, and the
 * wrapping made every other category read as a child of it instead of a
 * peer. This adds it as a genuine, standalone category: a catch-all HR can
 * upload into for anything beyond the 6 standard document types, extensible
 * like any other category via the Document Categories admin page. Sort
 * order 0 so it appears first among the now-flat, peer category tiles.
 */
return new class extends Migration
{
    public function up(): void
    {
        DocumentCategory::firstOrCreate(['slug' => 'personal-documents'], [
            'name' => 'Personal Documents',
            'filename_code' => 'PERSONAL_DOCS',
            'description' => 'Any other personal document not covered by a dedicated category',
            'sort_order' => 0,
        ]);
    }

    public function down(): void
    {
        $category = DocumentCategory::where('slug', 'personal-documents')->first();
        if ($category && ! $category->documents()->withTrashed()->exists()) {
            $category->delete();
        }
    }
};
