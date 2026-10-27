<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — Alumni Master roster.
 *
 * The authoritative list of people eligible to register as alumni, imported by
 * the Super Admin from an Excel/CSV file. Self-registration is validated against
 * this roster by Employee ID OR PAN. Once a record is claimed by a registration
 * it is linked to the resulting user account.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_masters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employee_id', 40)->unique();
            $table->string('pan', 15)->nullable()->unique();
            $table->string('name', 160);
            $table->string('email', 160)->nullable()->index();
            $table->string('designation', 120)->nullable();
            $table->string('department', 120)->nullable()->index();
            $table->string('organization', 160)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('location', 120)->nullable();
            $table->date('joined_on')->nullable();
            $table->date('exited_on')->nullable();
            $table->string('status', 20)->default('active'); // active | inactive
            // Claim tracking — set when an alumnus registers against this record.
            $table->foreignUuid('claimed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('claimed_at')->nullable();
            $table->foreignUuid('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Carry the verified PAN onto the alumnus profile once claimed.
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->string('pan', 15)->nullable()->after('employee_id');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->dropColumn('pan');
        });
        Schema::dropIfExists('alumni_masters');
    }
};
