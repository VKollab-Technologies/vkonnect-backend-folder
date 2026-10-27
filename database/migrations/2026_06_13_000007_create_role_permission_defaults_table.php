<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — role-level permission toggles for HR and Alumni.
 *
 * This is the source of truth for the Super Admin "Permissions" page switches.
 * It is intentionally separate from Spatie's role permissions: capabilities are
 * enforced from each user's DIRECT permissions (so individuals can be tuned),
 * while this table records the role-wide default + drives the bulk apply when a
 * Super Admin flips a switch.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permission_defaults', function (Blueprint $table) {
            $table->id();
            $table->string('role', 32);
            $table->string('permission', 64);
            $table->timestamps();
            $table->unique(['role', 'permission']);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permission_defaults');
    }
};
