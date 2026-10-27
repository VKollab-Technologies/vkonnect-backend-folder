<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets HR upload a document before the alumnus has registered.
 *
 * Documents previously required a `user_id`, which only exists once someone
 * activates their account. HR had no way to prepare an exiting employee's
 * vault in advance — the recipient simply did not appear in the picker.
 *
 * A document is now owned by EITHER a live account (`user_id`) or a roster
 * record (`alumni_master_id`). On activation the roster-owned documents are
 * re-pointed at the new account (AuthService::activateFromMaster), so the
 * alumnus signs in and finds their vault already populated.
 *
 * `user_id` becomes nullable to allow the roster case.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignUuid('alumni_master_id')->nullable()->after('user_id')
                ->constrained('alumni_masters')->cascadeOnDelete();
            $table->index('alumni_master_id');
        });

        // Roster-owned documents have no account, so user_id must be nullable.
        // This used to branch per driver; both branches did exactly the same
        // thing, so it is now the single call it always was.
        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Roster-owned documents have no account to fall back to, so they are
        // removed rather than left violating the restored NOT NULL.
        \App\Models\Document::withTrashed()->whereNull('user_id')->forceDelete();

        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('alumni_master_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('user_id')->nullable(false)->change();
        });
    }
};
