<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Backs the per-account lockout in AuthService (MAX_ATTEMPTS/LOCK_MINUTES):
     * counts consecutive failed password attempts so `locked_until` can be set
     * automatically, complementing the existing per-IP route throttle.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('locked_until');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('failed_login_attempts');
        });
    }
};
