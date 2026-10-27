<?php

use App\Support\PanHash;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * M2 of 3 — encrypt existing PAN values and compute their blind index.
 *
 * Safe to re-run. Each row is classified before it is touched: a value
 * matching the PAN format is plaintext and gets migrated; anything else is
 * already ciphertext and is left alone. A ciphertext can never match
 * /^[A-Z]{5}[0-9]{4}[A-Z]$/, so the two cases cannot be confused, and a
 * decrypt attempt backs the check up rather than relying on the regex alone.
 *
 * Runs before M3 deliberately. Adding the unique index first would mean
 * writing hashes into an indexed column mid-flight, where a genuine duplicate
 * would abort the migration halfway through rather than being reported.
 *
 * Deliberately raw DB queries, not Eloquent: the model casts encrypt on write
 * and decrypt on read, so reading through the model here would try to decrypt
 * plaintext and throw before this migration ever ran.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        foreach (['alumni_masters', 'alumni_profiles'] as $table) {
            $migrated = 0;
            $skipped = 0;

            DB::table($table)->whereNotNull('pan')->orderBy('id')->chunkById(200, function ($rows) use ($table, &$migrated, &$skipped) {
                foreach ($rows as $row) {
                    $raw = (string) $row->pan;

                    if (! $this->isPlaintextPan($raw)) {
                        // Already migrated. Backfill a missing hash anyway --
                        // an interrupted earlier run could have written the
                        // ciphertext without the hash.
                        if ($row->pan_hash === null) {
                            try {
                                DB::table($table)->where('id', $row->id)
                                    ->update(['pan_hash' => PanHash::make(Crypt::decryptString($raw))]);
                            } catch (\Throwable) {
                                // Undecryptable and not plaintext: leave it and
                                // let the verification step report it rather
                                // than guessing at what it is.
                            }
                        }
                        $skipped++;

                        continue;
                    }

                    $normalised = strtoupper(trim($raw));

                    DB::transaction(function () use ($table, $row, $normalised) {
                        DB::table($table)->where('id', $row->id)->update([
                            'pan' => Crypt::encryptString($normalised),
                            'pan_hash' => PanHash::make($normalised),
                        ]);
                    });

                    $migrated++;
                }
            });

            echo "  {$table}: {$migrated} encrypted, {$skipped} already done".PHP_EOL;
        }
    }

    /**
     * Reversing this would decrypt PAN back into the clear.
     *
     * Left as a no-op on purpose: a rollback that silently un-encrypts
     * personal data is a worse outcome than a rollback that refuses. Restore
     * from the dump taken before M2 if this genuinely has to be undone.
     */
    public function down(): void
    {
        //
    }

    private function isPlaintextPan(string $value): bool
    {
        return (bool) preg_match('/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/', trim($value));
    }
};
