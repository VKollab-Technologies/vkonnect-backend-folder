<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Per-category HR access, replacing the single blanket switch.
 *
 * `hr_document_categories_disabled_at` answered one question for the whole
 * taxonomy: may HR touch categories at all. That is too coarse — a payslip
 * category and a disciplinary-letter category are not the same grant. This
 * column moves the decision to each category, where Super Admin makes it.
 *
 * DEFAULT FALSE, so a category created from now on is invisible to HR until
 * Super Admin enables it — the same opt-in posture as every other grant in
 * this system.
 *
 * Existing rows are backfilled to TRUE, deliberately. They are the five seeded
 * categories HR files documents into every day; flipping them off in a
 * migration would silently break uploads with no administrator having chosen
 * that. New categories opt in; existing ones are grandfathered.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->boolean('hr_enabled')->default(false)->after('sort_order');
        });

        DB::table('document_categories')->update(['hr_enabled' => true]);
    }

    public function down(): void
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropColumn('hr_enabled');
        });
    }
};
