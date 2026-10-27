<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * HR "Alumni Control" per-alumnus access flags. Each is a nullable
     * timestamp: null = enabled (today's behavior for every existing user,
     * no backfill needed), set = disabled since that moment. `status` and
     * `locked_until` (existing columns) already cover Account Active/Inactive
     * and Lock/Unlock, so this only adds the granular capability toggles.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('downloads_disabled_at')->nullable()->after('locked_until');
            $table->timestamp('documents_disabled_at')->nullable()->after('downloads_disabled_at');
            $table->timestamp('jobs_disabled_at')->nullable()->after('documents_disabled_at');
            $table->timestamp('applications_disabled_at')->nullable()->after('jobs_disabled_at');
            $table->timestamp('notifications_disabled_at')->nullable()->after('applications_disabled_at');
            $table->timestamp('ticker_disabled_at')->nullable()->after('notifications_disabled_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'downloads_disabled_at',
                'documents_disabled_at',
                'jobs_disabled_at',
                'applications_disabled_at',
                'notifications_disabled_at',
                'ticker_disabled_at',
            ]);
        });
    }
};
