<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Soft-deleted roster rows must not hold a unique slot forever.
 *
 * Same defect as 2026_07_31_000001 (which fixed it for `users`), on the other
 * soft-deleted table. AlumniMaster uses SoftDeletes, but vkollab_id / pan were
 * plain unique indexes counting trashed rows — so once a roster record was
 * deleted, that person's EMP ID and PAN could never be used again. Re-adding
 * them failed with "This EMP ID already exists" while the record was invisible
 * in every list, which is exactly how it was reported.
 *
 * Index names here are the ones actually on the table, not the ones the
 * schema builder would generate today: `alumni_masters_employee_id_unique`
 * predates the employee_id -> vkollab_id column rename and kept its original
 * name. DROP INDEX IF EXISTS keeps this safe on databases where an earlier
 * migration left a different shape behind.
 *
 * Partial indexes where the engine supports them; on MySQL the same semantics come from
 * VIRTUAL `<col>_live` mirror columns that go NULL once the row is trashed,
 * with the unique indexes over the mirrors. See 2026_07_31_000001 for why the
 * MySQL branch is required rather than falling back to plain unique indexes.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            $this->applyMysqlEquivalent();

            return;
        }

        if ($driver !== 'pgsql') {
            return;
        }

        foreach ([
            'alumni_masters_employee_id_unique',   // legacy name, indexes vkollab_id
            'alumni_masters_vkollab_id_unique',
            'alumni_masters_pan_unique',
            'alumni_masters_email_unique_idx',
        ] as $index) {
            DB::statement("DROP INDEX IF EXISTS {$index}");
        }

        DB::statement('CREATE UNIQUE INDEX alumni_masters_vkollab_id_unique_idx ON alumni_masters (vkollab_id) WHERE deleted_at IS NULL');
        DB::statement('CREATE UNIQUE INDEX alumni_masters_pan_unique_idx ON alumni_masters (pan) WHERE deleted_at IS NULL');
        DB::statement('CREATE UNIQUE INDEX alumni_masters_email_unique_idx ON alumni_masters (email) WHERE deleted_at IS NULL');
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            $this->revertMysqlEquivalent();

            return;
        }

        if ($driver !== 'pgsql') {
            return;
        }

        foreach ([
            'alumni_masters_vkollab_id_unique_idx',
            'alumni_masters_pan_unique_idx',
            'alumni_masters_email_unique_idx',
        ] as $index) {
            DB::statement("DROP INDEX IF EXISTS {$index}");
        }

        DB::statement('CREATE UNIQUE INDEX alumni_masters_employee_id_unique ON alumni_masters (vkollab_id)');
        DB::statement('CREATE UNIQUE INDEX alumni_masters_pan_unique ON alumni_masters (pan)');
    }

    /** Mirrors of vkollab_id / pan / email that go NULL once the row is trashed. */
    private function applyMysqlEquivalent(): void
    {
        foreach (['vkollab_id', 'pan', 'email'] as $column) {
            $this->addLiveMirror('alumni_masters', $column);
        }

        // Legacy name included: alumni_masters_employee_id_unique predates the
        // employee_id -> vkollab_id rename and kept its original name.
        foreach ([
            'alumni_masters_employee_id_unique',
            'alumni_masters_vkollab_id_unique',
            'alumni_masters_pan_unique',
            'alumni_masters_vkollab_id_unique_idx',
            'alumni_masters_pan_unique_idx',
            'alumni_masters_email_unique_idx',
        ] as $index) {
            $this->dropIndexIfExists('alumni_masters', $index);
        }

        DB::statement('CREATE UNIQUE INDEX alumni_masters_vkollab_id_unique_idx ON alumni_masters (vkollab_id_live)');
        DB::statement('CREATE UNIQUE INDEX alumni_masters_pan_unique_idx ON alumni_masters (pan_live)');
        DB::statement('CREATE UNIQUE INDEX alumni_masters_email_unique_idx ON alumni_masters (email_live)');
    }

    private function revertMysqlEquivalent(): void
    {
        foreach ([
            'alumni_masters_vkollab_id_unique_idx',
            'alumni_masters_pan_unique_idx',
            'alumni_masters_email_unique_idx',
        ] as $index) {
            $this->dropIndexIfExists('alumni_masters', $index);
        }

        foreach (['vkollab_id', 'pan', 'email'] as $column) {
            if (Schema::hasColumn('alumni_masters', $column.'_live')) {
                DB::statement('ALTER TABLE alumni_masters DROP COLUMN '.$column.'_live');
            }
        }

        DB::statement('CREATE UNIQUE INDEX alumni_masters_employee_id_unique ON alumni_masters (vkollab_id)');
        DB::statement('CREATE UNIQUE INDEX alumni_masters_pan_unique ON alumni_masters (pan)');
    }

    /** Copies the source column's declared type so the mirror cannot truncate. */
    private function addLiveMirror(string $table, string $column): void
    {
        if (Schema::hasColumn($table, $column.'_live')) {
            return;
        }

        $type = DB::scalar(
            'SELECT COLUMN_TYPE FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column],
        );

        DB::statement(
            "ALTER TABLE {$table} ADD COLUMN {$column}_live {$type}"
            ." GENERATED ALWAYS AS (CASE WHEN deleted_at IS NULL THEN {$column} END) VIRTUAL"
        );
    }

    /** MySQL has no DROP INDEX IF EXISTS, so the existence check is explicit. */
    private function dropIndexIfExists(string $table, string $index): void
    {
        $exists = DB::scalar(
            'SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?',
            [$table, $index],
        );

        if ($exists) {
            DB::statement("ALTER TABLE {$table} DROP INDEX {$index}");
        }
    }
};
