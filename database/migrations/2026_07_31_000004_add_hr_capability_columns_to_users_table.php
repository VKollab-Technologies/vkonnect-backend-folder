<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-HR-admin capability switches, so Super Admin can disable an individual
 * HR admin's features the same way HR disables an individual alumnus's.
 *
 * Spatie permissions already exist for this, but they are role-shaped: a
 * Super Admin editing one HR admin's permissions marks that user "customized"
 * and permanently opts them out of future role-default changes. These flags
 * are a lighter, reversible override for "switch this off for this person for
 * now" without disturbing their permission profile.
 *
 * Same nullable-timestamp pattern as the alumni columns
 * (2026_07_21_000001): null = enabled, set = disabled since that moment, so no
 * backfill is needed and existing HR admins keep every feature.
 */
return new class extends Migration
{
    /** Mirrors HR_CAPABILITY_COLUMNS in App\Models\User. */
    private array $columns = [
        'hr_documents_disabled_at',
        'hr_document_categories_disabled_at',
        'hr_jobs_disabled_at',
        'hr_applications_disabled_at',
        'hr_announcements_disabled_at',
        'hr_ticker_disabled_at',
        'hr_alumni_disabled_at',
        'hr_alumni_master_disabled_at',
    ];

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ($this->columns as $column) {
                $table->timestamp($column)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn($this->columns);
        });
    }
};
