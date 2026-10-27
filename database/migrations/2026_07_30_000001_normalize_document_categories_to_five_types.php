<?php

use App\Models\DocumentCategory;
use Illuminate\Database\Migrations\Migration;

/**
 * The Documents module is scoped to exactly 5 categories: Offer Letter,
 * Pay Slips (Last 6 Months), Increment Letter, Relieving Letter, Form 16.
 * Categories were never seeded (only ever created ad hoc through the CRUD
 * UI), so environments have drifted to arbitrary sets. This renames
 * existing categories in place where a document count still uses them
 * (no data loss, no FK breakage — mirrors the same withTrashed() check
 * DocumentCategoryController::destroy() uses), creates any of the 5 that
 * don't exist yet, and removes stray categories that are genuinely empty.
 */
return new class extends Migration
{
    /** @var array<int, array{legacySlug:string, name:string, slug:string, description:string, sortOrder:int}> */
    private array $canonical = [
        ['legacySlug' => 'employment', 'name' => 'Offer Letter', 'slug' => 'offer-letter', 'description' => 'Original offer of employment', 'sortOrder' => 1],
        ['legacySlug' => 'tax-record', 'name' => 'Relieving Letter', 'slug' => 'relieving-letter', 'description' => 'Confirmation of separation from the organization', 'sortOrder' => 4],
        ['legacySlug' => 'increment-letter', 'name' => 'Increment Letter', 'slug' => 'increment-letter', 'description' => 'Salary revision / increment confirmation', 'sortOrder' => 2],
        ['legacySlug' => 'payslip', 'name' => 'Pay Slips (Last 6 Months)', 'slug' => 'pay-slips', 'description' => 'Most recent six months of payslips', 'sortOrder' => 3],
        ['legacySlug' => 'form-16', 'name' => 'Form 16', 'slug' => 'form-16', 'description' => 'Annual tax deduction certificate', 'sortOrder' => 5],
    ];

    public function up(): void
    {
        foreach ($this->canonical as $c) {
            $existing = DocumentCategory::where('slug', $c['legacySlug'])->first()
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

        // Remove anything left over that isn't one of the 5. A stray category
        // can still be held alive by a SOFT-DELETED document (same FK reality
        // Part 2 fixed) — those are already invisible everywhere in the UI,
        // so it's safe to reassign them to the fallback category rather than
        // leaving a 6th category around just to hold trashed rows.
        $keepSlugs = array_column($this->canonical, 'slug');
        $fallbackCategoryId = DocumentCategory::where('slug', 'form-16')->value('id');

        DocumentCategory::whereNotIn('slug', $keepSlugs)->get()->each(function (DocumentCategory $category) use ($fallbackCategoryId) {
            $category->documents()->withTrashed()->update(['category_id' => $fallbackCategoryId]);
            $category->delete();
        });
    }

    public function down(): void
    {
        // One-way data normalization — the original ad hoc category set
        // isn't worth reconstructing.
    }
};
