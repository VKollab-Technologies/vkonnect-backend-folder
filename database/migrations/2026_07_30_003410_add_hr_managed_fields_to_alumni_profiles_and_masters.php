<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * New fields for the "My Information" redesign — all HR-managed, never
 * alumni-editable. Added to both alumni_masters (so HR can set them before
 * the alumnus even activates) and alumni_profiles (the live, post-activation
 * record); AuthService::activateFromMaster() carries them across, same as
 * designation/department/phone/etc. already do.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni_masters', function (Blueprint $table) {
            $table->string('last_working_location', 160)->nullable()->after('exited_on');
            $table->string('band', 40)->nullable()->after('last_working_location');
            $table->string('supervisor_name', 160)->nullable()->after('band');
            $table->string('uan_number', 20)->nullable()->after('supervisor_name');
            $table->string('pf_number', 40)->nullable()->after('uan_number');
        });

        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->string('last_working_location', 160)->nullable()->after('exited_on');
            $table->string('band', 40)->nullable()->after('last_working_location');
            $table->string('supervisor_name', 160)->nullable()->after('band');
            $table->string('uan_number', 20)->nullable()->after('supervisor_name');
            $table->string('pf_number', 40)->nullable()->after('uan_number');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_masters', function (Blueprint $table) {
            $table->dropColumn(['last_working_location', 'band', 'supervisor_name', 'uan_number', 'pf_number']);
        });

        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->dropColumn(['last_working_location', 'band', 'supervisor_name', 'uan_number', 'pf_number']);
        });
    }
};
