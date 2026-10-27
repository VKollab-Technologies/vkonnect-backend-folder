<?php

use App\Models\DocumentCategory;
use Illuminate\Database\Migrations\Migration;

/**
 * Replaces the original 5-category taxonomy (Offer Letter, Pay Slips,
 * Increment Letter, Relieving Letter, Form 16) with the "Personal Documents"
 * set: PAN, Aadhaar, Form 16, Salary Slips (Last 6 Months),
 * Full & Final Settlement, Experience Letter. Follows the same
 * update-in-place-by-legacy-slug pattern as
 * 2026_07_30_000001_normalize_document_categories_to_five_types so existing
 * documents are never orphaned or lost.
 *
 * Chosen mapping (documented here since it isn't reconstructable from code):
 *   - Form 16            -> Form 16 (unchanged)
 *   - Pay Slips          -> Salary Slips (Last 6 Months) (renamed, same role)
 *   - Relieving Letter   -> Experience Letter (renamed — same document,
 *                           different regional naming for an exit letter)
 *   - Increment Letter   -> folded into Salary Slips (Last 6 Months), the
 *                           closest surviving category (both are
 *                           compensation-related payroll documents)
 *   - Offer Letter       -> folded into Experience Letter, the closest
 *                           surviving category (both are one-time
 *                           employment-history letters)
 *   - PAN, Aadhaar, Full & Final Settlement are brand new categories with no
 *     legacy data to carry over.
 */
return new class extends Migration
{
    /** @var array<int, array{legacySlug:?string, name:string, slug:string, description:string, sortOrder:int}> */
    private array $canonical = [
        ['legacySlug' => null, 'name' => 'PAN', 'slug' => 'pan', 'description' => 'PAN card copy', 'sortOrder' => 1],
        ['legacySlug' => null, 'name' => 'Aadhaar', 'slug' => 'aadhaar', 'description' => 'Aadhaar card copy (optional, if available)', 'sortOrder' => 2],
        ['legacySlug' => 'form-16', 'name' => 'Form 16', 'slug' => 'form-16', 'description' => 'Annual tax deduction certificate', 'sortOrder' => 3],
        ['legacySlug' => 'pay-slips', 'name' => 'Salary Slips (Last 6 Months)', 'slug' => 'salary-slips', 'description' => 'Most recent six months of salary slips', 'sortOrder' => 4],
        ['legacySlug' => null, 'name' => 'Full & Final Settlement', 'slug' => 'full-and-final-settlement', 'description' => 'Final settlement statement on exit', 'sortOrder' => 5],
        ['legacySlug' => 'relieving-letter', 'name' => 'Experience Letter', 'slug' => 'experience-letter', 'description' => 'Confirmation of tenure and separation from the organization', 'sortOrder' => 6],
    ];

    /** Stray legacy categories with no direct equivalent, folded into a surviving category's slug. */
    private array $foldInto = [
        'increment-letter' => 'salary-slips',
        'offer-letter' => 'experience-letter',
    ];

    public function up(): void
    {
        foreach ($this->canonical as $c) {
            $existing = ($c['legacySlug'] !== null ? DocumentCategory::where('slug', $c['legacySlug'])->first() : null)
                ?? DocumentCategory::where('slug', $c['slug'])->first();

            if ($existing) {
                $existing->update([
                    'name' => $c['name'],
                    'slug' => $c['slug'],
                    'description' => $c['description'],
                    'sort_order' => $c['sortOrder'],
                ]);
            } else {
                DocumentCategory::create([
                    'name' => $c['name'],
                    'slug' => $c['slug'],
                    'description' => $c['description'],
                    'sort_order' => $c['sortOrder'],
                ]);
            }
        }

        // Fold documents from categories with no direct equivalent into the
        // closest surviving category (withTrashed() — same FK-safety check
        // DocumentCategoryController::destroy() uses), then delete the strays.
        foreach ($this->foldInto as $legacySlug => $targetSlug) {
            $legacy = DocumentCategory::where('slug', $legacySlug)->first();
            if (! $legacy) {
                continue;
            }
            $targetId = DocumentCategory::where('slug', $targetSlug)->value('id');
            $legacy->documents()->withTrashed()->update(['category_id' => $targetId]);
            $legacy->delete();
        }

        // Anything else left over (arbitrary drift from ad hoc creation) also
        // gets reassigned to a fallback rather than deleted outright.
        $keepSlugs = array_column($this->canonical, 'slug');
        $fallbackCategoryId = DocumentCategory::where('slug', 'form-16')->value('id');

        DocumentCategory::whereNotIn('slug', $keepSlugs)->get()->each(function (DocumentCategory $category) use ($fallbackCategoryId) {
            $category->documents()->withTrashed()->update(['category_id' => $fallbackCategoryId]);
            $category->delete();
        });
    }

    public function down(): void
    {
        // One-way data normalization — the previous 5-category set isn't
        // worth reconstructing.
    }
};
