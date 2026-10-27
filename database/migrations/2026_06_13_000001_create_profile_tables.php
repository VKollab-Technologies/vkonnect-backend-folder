<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — profile tables (1–1 with users per realm).
 * See docs/02-database-schema.md §3.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('employee_id', 40)->nullable()->index();
            $table->string('designation', 120)->nullable();
            $table->string('department', 120)->nullable()->index();
            $table->string('current_organization', 160)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('location', 120)->nullable();
            $table->date('joined_on')->nullable();
            $table->date('exited_on')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedTinyInteger('profile_completion')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_admins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('employee_id', 40)->nullable();
            $table->string('department', 120)->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_admins');
        Schema::dropIfExists('alumni_profiles');
    }
};
