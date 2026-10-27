<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Re-adds the per-alumnus Service Request switch.
 *
 * The column was dropped when Service Request became a static placeholder: a
 * switch that governs nothing is worse than no switch, because an administrator
 * turns it off and the feature stays visible. It comes back now that the
 * feature has a real three-tier gate (platform flag → this switch → permission)
 * and the nav entry actually responds to it.
 *
 * Nullable timestamp, NULL meaning on, matching every other alumni capability
 * column. The platform flag above it is off by default, so nothing is exposed
 * until a Super Admin opts in.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'service_requests_disabled_at')) {
                $table->timestamp('service_requests_disabled_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'service_requests_disabled_at')) {
                $table->dropColumn('service_requests_disabled_at');
            }
        });
    }
};
