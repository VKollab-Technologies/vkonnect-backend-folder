<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Supervisor E-mail — a 6th HR-managed "My Information" field, same
 * treatment as last_working_location/band/supervisor_name/uan_number/
 * pf_number added earlier: never alumni-editable, settable only by HR via
 * Alumni Master / Alumni Control.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->string('supervisor_email', 160)->nullable()->after('supervisor_name');
        });
        Schema::table('alumni_masters', function (Blueprint $table) {
            $table->string('supervisor_email', 160)->nullable()->after('supervisor_name');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->dropColumn('supervisor_email');
        });
        Schema::table('alumni_masters', function (Blueprint $table) {
            $table->dropColumn('supervisor_email');
        });
    }
};
