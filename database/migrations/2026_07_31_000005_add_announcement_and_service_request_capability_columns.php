<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Completes the Alumni Control switch set.
 *
 * Announcements and Service Requests were alumni-facing features with no
 * switch behind them, so HR could see the rest of the sidebar under their
 * control and these two permanently on. Same nullable-timestamp shape as the
 * seven existing switches: null means enabled, a timestamp records when it was
 * turned off and by implication that it was turned off deliberately.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('announcements_disabled_at')->nullable();
            $table->timestamp('service_requests_disabled_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['announcements_disabled_at', 'service_requests_disabled_at']);
        });
    }
};
