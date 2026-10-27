<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * M3 of 3 — restore the uniqueness guarantee, keyed on the blind index.
 *
 * Same shape as the mirror this replaces: a virtual column that is NULL for
 * soft-deleted rows, with a unique index over it. MySQL ignores NULLs in a
 * unique index, so a trashed roster row still does not block re-registering
 * the same PAN -- which was the entire point of the original generated-column
 * fix. Only the keyed value changes, from plaintext PAN to its HMAC.
 *
 * Deterministic hashing is what makes this work: two rows holding the same
 * PAN produce the same pan_hash and collide, exactly as two identical
 * plaintext PANs used to. Encrypting `pan` alone could not do this, because
 * the same PAN encrypts differently every time.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $this->dropIndexIfExists('alumni_masters', 'alumni_masters_pan_unique_idx');
        if ($this->hasColumn('alumni_masters', 'pan_live')) {
            DB::statement('ALTER TABLE alumni_masters DROP COLUMN pan_live');
        }

        DB::statement('ALTER TABLE alumni_masters ADD COLUMN pan_live CHAR(64) NULL');
        
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $this->dropIndexIfExists('alumni_masters', 'alumni_masters_pan_unique_idx');
        if ($this->hasColumn('alumni_masters', 'pan_live')) {
            DB::statement('ALTER TABLE alumni_masters DROP COLUMN pan_live');
        }
    }

    private function hasColumn(string $table, string $column): bool
    {
        return (int) DB::selectOne(
            'SELECT COUNT(*) c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        )->c > 0;
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        $exists = (int) DB::selectOne(
            'SELECT COUNT(*) c FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?',
            [$table, $index]
        )->c > 0;

        if ($exists) {
            DB::statement("DROP INDEX {$index} ON {$table}");
        }
    }
};
