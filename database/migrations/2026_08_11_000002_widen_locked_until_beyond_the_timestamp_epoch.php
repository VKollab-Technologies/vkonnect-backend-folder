<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Widens `users.locked_until` from TIMESTAMP to DATETIME.
 *
 * MySQL's TIMESTAMP range ends at 2038-01-19 03:14:07 UTC; DATETIME reaches
 * year 9999. "the timestamp epoch" in the filename means that 2038 ceiling.
 *
 * An indefinite account lock must not depend on the year being under 2038.
 *
 * AlumniAccountService::setLocked writes `now()->addYears(100)` as its
 * "locked indefinitely" sentinel. `locked_until` was a MySQL TIMESTAMP, whose
 * range ends at 2038-01-19 — so that sentinel lands roughly a century past the
 * ceiling and the write is rejected outright:
 *
 *   SQLSTATE[22007]: 1292 Incorrect datetime value: '2126-08-11 12:26:51'
 *
 * The engine used before the MySQL migration had no such range and stored it
 * happily, which is why this only appeared once the suite ran against MySQL.
 * That was never behaviour worth preserving — it was an engine not enforcing
 * anything.
 *
 * DATETIME instead of TIMESTAMP: it reaches year 9999 and, unlike TIMESTAMP,
 * is not silently converted between the session and UTC time zones — which is
 * the behaviour you want for a stored application timestamp anyway.
 *
 * No data to backfill: no row in either database had locked_until set, so this
 * is a widening of an empty column.
 *
 * The partial-index restore below is not incidental. On engines that cannot
 * alter a column in place, Laravel rebuilds the whole table — and the rebuild
 * reinstates users_vkollab_id_unique_idx / users_role_email_unique_idx as
 * PLAIN unique indexes, silently dropping the partial predicate that
 * 2026_07_31_000001 added. That regresses the exact bug that migration exists
 * to fix: a soft-deleted account starts holding its email and EMP ID forever
 * again. MySQL alters in place, so it is unaffected — but any future
 * ->change() on `users` on such an engine must restore them the same way.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function ($table) {
            $table->dateTime('locked_until')->nullable()->change();
        });

        $this->restorePartialUniqueIndexes();
    }

    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->timestamp('locked_until')->nullable()->change();
        });

        $this->restorePartialUniqueIndexes();
    }

    /**
     * Put the soft-delete-aware uniqueness back after a table rebuild.
     *
     * MySQL needs nothing here: its equivalent lives in generated `*_live`
     * columns, and ALTER COLUMN does not disturb them.
     */
    private function restorePartialUniqueIndexes(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP INDEX IF EXISTS users_vkollab_id_unique_idx');
        DB::statement('DROP INDEX IF EXISTS users_role_email_unique_idx');

        DB::statement('CREATE UNIQUE INDEX users_vkollab_id_unique_idx ON users (vkollab_id) WHERE deleted_at IS NULL');
        DB::statement('CREATE UNIQUE INDEX users_role_email_unique_idx ON users (role, email) WHERE deleted_at IS NULL');
    }
};
