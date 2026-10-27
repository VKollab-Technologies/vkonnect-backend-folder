<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "My Information" (the alumni-facing profile view) had no Alumni Control
 * toggle at all — every other alumni-facing capability (documents, jobs,
 * applications, notifications, ticker) already had one. Same nullable
 * timestamp pattern as 2026_07_21_000001_add_alumni_control_columns_to_users_table:
 * null = enabled, set = disabled since that moment.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('profile_disabled_at')->nullable()->after('ticker_disabled_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_disabled_at');
        });
    }
};
