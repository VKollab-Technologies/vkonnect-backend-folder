<?php

use App\Models\DocumentCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `filename_code` is the short, machine-friendly token used to build
 * generated document filenames (EMPID_CODE_YEAR, e.g. "EMP1234_FORM16_2025")
 * — see App\Support\DocumentFilename. It's independent of `name` (free text,
 * shown to people) and `slug` (URL/API identifier), since neither is a good
 * fit for a filename segment: `name` can contain spaces/punctuation
 * ("Full & Final Settlement") and `slug` uses hyphens that would make
 * "form-16" render as "FORM-16" instead of the desired "FORM16".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->string('filename_code', 40)->nullable()->after('slug');
        });

        $codes = [
            'pan' => 'PAN',
            'aadhaar' => 'AADHAAR',
            'form-16' => 'FORM16',
            'salary-slips' => 'SALARY_SLIPS',
            'full-and-final-settlement' => 'FULL_FINAL_SETTLEMENT',
            'experience-letter' => 'EXPERIENCE_LETTER',
        ];

        foreach ($codes as $slug => $code) {
            DocumentCategory::where('slug', $slug)->update(['filename_code' => $code]);
        }

        // Any other (custom, HR-created) category gets a derived code so
        // nothing is left null — same fallback rule the controller uses for
        // future categories with no explicit code supplied.
        DocumentCategory::whereNull('filename_code')->get()->each(function (DocumentCategory $category) {
            $code = strtoupper(trim(preg_replace('/[^A-Za-z0-9]+/', '_', $category->name), '_'));
            $category->update(['filename_code' => $code !== '' ? $code : 'DOCUMENT']);
        });
    }

    public function down(): void
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropColumn('filename_code');
        });
    }
};
