<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Make the HR "Document Categories" switch opt-in.
 *
 * Every HR switch is a nullable `*_disabled_at`, so NULL means ON — which made
 * this one on for every HR admin the moment their account existed, with no
 * Super Admin decision anywhere in the chain. That is fine for routine work
 * but wrong for the category taxonomy, which is structural and shared across
 * all alumni: HR could delete a category no one had granted them.
 *
 * Backfills existing HR admins to OFF so the default matches new accounts.
 * Deliberately unconditional: an admin who was meant to have it gets it back
 * with one toggle in HR Control, whereas leaving accounts on would silently
 * preserve exactly the access this is closing.
 */
return new class extends Migration
{
    private const COLUMN = 'hr_document_categories_disabled_at';

    public function up(): void
    {
        DB::table('users')
            ->where('role', 'hr_admin')
            ->whereNull(self::COLUMN)
            ->update([self::COLUMN => now()]);
    }

    /**
     * Reversing restores the previous default (on for everyone), which is the
     * pre-migration state — not a judgement that it was correct.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('role', 'hr_admin')
            ->update([self::COLUMN => null]);
    }
};
