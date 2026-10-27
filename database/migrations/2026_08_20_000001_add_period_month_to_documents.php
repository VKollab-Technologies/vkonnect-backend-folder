<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Salary Slips are monthly; every other period-based document is annual.
 *
 * `financial_year` stays exactly as it is. Packing "05-2025" into it was the
 * obvious shortcut and the wrong one: the `^\d{4}-\d{4}$` shape is relied on
 * by request validation, the filename builder, its frontend mirror, and the
 * DISTINCT query behind the vault's year filter. One category's needs are not
 * worth breaking four call sites and the seven Form 16 records that share the
 * column.
 *
 * Nullable, and deliberately not backfilled. Five Salary Slips already exist
 * with a financial year and no month; which month each covers is not recorded
 * anywhere, and inventing one would put a wrong answer in the filename and the
 * vault. They render as "month not recorded" instead.
 *
 * No calendar-year column: month plus financial year already pins it. Months
 * 4-12 belong to the financial year's first year, months 1-3 to its second —
 * so May 2025 and Feb 2026 both sit in FY 2025-2026. A stored calendar year
 * would be derivable and therefore able to disagree.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedTinyInteger('period_month')->nullable()->after('financial_year');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('period_month');
        });
    }
};
