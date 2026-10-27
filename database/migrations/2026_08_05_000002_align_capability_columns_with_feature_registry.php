<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Brings the switch columns in line with the Feature Registry.
 *
 * Adds switches for three features that were permission-only and therefore not
 * actually controllable (HR dashboard, HR settings, HR's own notification
 * inbox) plus the alumni dashboard, and drops the Service Requests switch —
 * that feature has no backend, so the switch governed nothing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('hr_dashboard_disabled_at')->nullable();
            $table->timestamp('hr_settings_disabled_at')->nullable();
            $table->timestamp('hr_notifications_disabled_at')->nullable();
            $table->timestamp('dashboard_disabled_at')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('service_requests_disabled_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'hr_dashboard_disabled_at',
                'hr_settings_disabled_at',
                'hr_notifications_disabled_at',
                'dashboard_disabled_at',
            ]);
            $table->timestamp('service_requests_disabled_at')->nullable();
        });
    }
};
