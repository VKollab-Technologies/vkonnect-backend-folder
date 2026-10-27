<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Soft-deleted users must not hold a unique slot forever.
 *
 * 2026_07_24_000001 added plain unique indexes on users.vkollab_id and
 * (users.role, users.email). Users are soft-deleted, so a deleted row keeps
 * occupying its slot: once HR deleted an alumnus, that person's email could
 * never register again and their EMP ID could never be reissued. In practice
 * this surfaced as a raw
 *   SQLSTATE[23000] UNIQUE constraint failed: users.role, users.email
 * during self-registration, from a deleted account the user could not see.
 *
 * Fixed so uniqueness only considers live rows.
 *
 * MySQL has no partial indexes, so uniqueness-over-live-rows is expressed as:
 * a VIRTUAL generated column that projects the value only while the row is
 * live and NULL once it is trashed, with the unique index over THAT column.
 * MySQL's unique indexes ignore NULLs, so deleted rows stop competing for the
 * slot — the same thing a partial index buys on engines that support one.
 *
 * This branch is not optional polish. An earlier revision let MySQL fall
 * through to the plain unique indexes and argued the app-level guard in
 * AuthService::assertEmailAvailableForAlumni was enough. It is not: the guard
 * turns a crash into a readable error, but it cannot make a deleted row
 * release its slot. Live data already holds rows that only coexist because
 * the index is partial, and a plain unique index would refuse to store them.
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

        Schema::table('users', function ($table) {
            $table->dropUnique('users_vkollab_id_unique_idx');
            $table->dropUnique('users_role_email_unique_idx');
        });

        DB::statement(
            'CREATE UNIQUE INDEX users_vkollab_id_unique_idx ON users (vkollab_id) WHERE deleted_at IS NULL'
        );
        DB::statement(
            'CREATE UNIQUE INDEX users_role_email_unique_idx ON users (role, email) WHERE deleted_at IS NULL'
        );
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

        DB::statement('DROP INDEX IF EXISTS users_vkollab_id_unique_idx');
        DB::statement('DROP INDEX IF EXISTS users_role_email_unique_idx');

        Schema::table('users', function ($table) {
            $table->unique('vkollab_id', 'users_vkollab_id_unique_idx');
            $table->unique(['role', 'email'], 'users_role_email_unique_idx');
        });
    }

    /**
     * The MySQL equivalent of the partial indexes above.
     *
     * `<col>_live` mirrors <col> while deleted_at IS NULL and is NULL
     * otherwise. Unique indexes over the mirror therefore police live rows
     * only. VIRTUAL, not STORED: it is computed on read and carried by the
     * secondary index alone, so this adds no row storage and needs no table
     * rebuild.
     *
     * (role, email_live) stays a two-column index rather than one concatenated
     * key — `role` is already a real column, and pairing it with the mirror
     * sidesteps separator-collision and key-length questions entirely.
     */
    private function applyMysqlEquivalent(): void
    {
        foreach (['vkollab_id', 'email'] as $column) {
            $this->addLiveMirror('users', $column);
        }

        $this->dropIndexIfExists('users', 'users_vkollab_id_unique_idx');
        $this->dropIndexIfExists('users', 'users_role_email_unique_idx');

        DB::statement('CREATE UNIQUE INDEX users_vkollab_id_unique_idx ON users (vkollab_id_live)');
        DB::statement('CREATE UNIQUE INDEX users_role_email_unique_idx ON users (role, email_live)');
    }

    private function revertMysqlEquivalent(): void
    {
        $this->dropIndexIfExists('users', 'users_vkollab_id_unique_idx');
        $this->dropIndexIfExists('users', 'users_role_email_unique_idx');

        foreach (['vkollab_id', 'email'] as $column) {
            if (Schema::hasColumn('users', $column.'_live')) {
                DB::statement('ALTER TABLE users DROP COLUMN '.$column.'_live');
            }
        }

        Schema::table('users', function ($table) {
            $table->unique('vkollab_id', 'users_vkollab_id_unique_idx');
            $table->unique(['role', 'email'], 'users_role_email_unique_idx');
        });
    }

    /**
     * The mirror copies the source column's exact declared type. Read from
     * information_schema rather than hardcoded, so a later length change to
     * the source column cannot silently truncate the indexed copy.
     */
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
