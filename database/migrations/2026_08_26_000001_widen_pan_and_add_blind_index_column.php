<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * M1 of 3 — make room for encrypted PAN and add the blind index column.
 *
 * `pan` becomes TEXT because an AES-256 ciphertext is ~200 characters and
 * will not fit varchar(15). `pan_hash` is the deterministic HMAC that
 * uniqueness and lookup move onto (M3 puts the index there).
 *
 * The generated `pan_live` column and its unique index have to go first:
 * MySQL refuses to alter a column that a generated column depends on. M3
 * rebuilds both against pan_hash, preserving the same "soft-deleted rows do
 * not block re-registration" guarantee.
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

        foreach (['alumni_masters', 'alumni_profiles'] as $table) {
            DB::statement("ALTER TABLE {$table} MODIFY pan TEXT NULL");

            if (! $this->hasColumn($table, 'pan_hash')) {
                // Nullable: a roster row without a PAN is legitimate, and a
                // NOT NULL column would need a sentinel that the unique index
                // would then collide on.
                DB::statement("ALTER TABLE {$table} ADD COLUMN pan_hash CHAR(64) NULL AFTER pan");
                DB::statement("CREATE INDEX {$table}_pan_hash_idx ON {$table} (pan_hash)");
            }
        }
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

        foreach (['alumni_masters', 'alumni_profiles'] as $table) {
            $this->dropIndexIfExists($table, "{$table}_pan_hash_idx");
            if ($this->hasColumn($table, 'pan_hash')) {
                DB::statement("ALTER TABLE {$table} DROP COLUMN pan_hash");
            }
            DB::statement("ALTER TABLE {$table} MODIFY pan VARCHAR(15) NULL");
        }

        // Restore the original plaintext mirror + index.
        DB::statement('ALTER TABLE alumni_masters ADD COLUMN pan_live VARCHAR(15) AS (CASE WHEN deleted_at IS NULL THEN pan END) VIRTUAL');
        DB::statement('CREATE UNIQUE INDEX alumni_masters_pan_unique_idx ON alumni_masters (pan_live)');
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
