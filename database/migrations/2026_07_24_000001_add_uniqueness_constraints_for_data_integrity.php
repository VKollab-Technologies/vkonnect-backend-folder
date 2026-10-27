<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Enterprise data-integrity hardening: DB-level constraints so duplicates are
 * impossible even if an API is called directly, bypassing app validation.
 *
 *  - users.vkollab_id: globally unique (the platform-wide sign-in identifier).
 *  - users.email: unique PER ROLE (not globally) — an alumnus and a Super
 *    Admin account are allowed to share an inbox, but two accounts in the
 *    SAME realm (e.g. two alumni) may not. This intentionally preserves the
 *    existing seeded demo data (Super Admin SA1001 and Alumni ALM1001 share
 *    an email today) rather than reversing that design.
 *  - alumni_masters.email: unique within the roster table.
 *
 * If this migration fails on an existing database, it means duplicate rows
 * already exist for one of these columns/scopes — resolve those records
 * first, then re-run.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('vkollab_id', 'users_vkollab_id_unique_idx');
            $table->unique(['role', 'email'], 'users_role_email_unique_idx');
        });

        Schema::table('alumni_masters', function (Blueprint $table) {
            $table->unique('email', 'alumni_masters_email_unique_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_vkollab_id_unique_idx');
            $table->dropUnique('users_role_email_unique_idx');
        });

        Schema::table('alumni_masters', function (Blueprint $table) {
            $table->dropUnique('alumni_masters_email_unique_idx');
        });
    }
};
