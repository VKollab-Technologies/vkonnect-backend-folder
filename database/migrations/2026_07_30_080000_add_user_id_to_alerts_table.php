<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-recipient ticker alerts.
 *
 * `audience` stays exactly as-is for broadcast rows (all / alumni / hr).
 * `user_id` is the new, narrower channel: when set, the alert belongs to that
 * one user and `audience` is ignored for it. Existing rows keep user_id NULL
 * and therefore keep behaving as broadcasts — no backfill needed.
 *
 * cascadeOnDelete, deliberately NOT nullOnDelete: nulling the column would
 * silently promote a private, single-recipient alert into a broadcast one
 * visible to an entire realm. Deleting the user must delete their alerts.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->foreignUuid('user_id')->nullable()->after('audience')
                ->constrained('users')->cascadeOnDelete();
            // The ticker query filters on these together on every poll.
            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_active']);
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
