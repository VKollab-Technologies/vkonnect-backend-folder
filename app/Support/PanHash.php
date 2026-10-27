<?php

namespace App\Support;

use RuntimeException;

/**
 * Blind index for PAN.
 *
 * The `pan` column is encrypted with Laravel's non-deterministic cast: the
 * same PAN produces different ciphertext every time, which is what makes it
 * safe and also what makes `WHERE pan = ?` useless. Uniqueness and lookup
 * move here instead -- a deterministic HMAC that can be indexed and compared
 * while revealing nothing about the value.
 *
 * Keyed by PAN_HASH_KEY rather than APP_KEY on purpose. APP_KEY is designed
 * to be rotated: APP_PREVIOUS_KEYS lets old ciphertext keep decrypting while
 * new writes use the new key. A blind index has no such mechanism -- rotating
 * the key that produced it would leave every stored hash unmatchable, so PAN
 * uniqueness would silently stop detecting duplicates and PAN-based
 * activation would stop finding anyone. Separating them means APP_KEY stays
 * rotatable without touching this.
 *
 * Changing PAN_HASH_KEY requires recomputing every pan_hash in the database.
 */
final class PanHash
{
    /**
     * Deterministic hash of a normalised PAN, or null for an absent one.
     *
     * Normalisation happens here rather than at the call sites so a lookup
     * and the write it is matching against can never disagree about case or
     * whitespace.
     */
    public static function make(?string $pan): ?string
    {
        $pan = trim((string) $pan);
        if ($pan === '') {
            return null;
        }

        return hash_hmac('sha256', strtoupper($pan), self::key());
    }

    private static function key(): string
    {
        $key = (string) config('app.pan_hash_key');

        if ($key === '') {
            // Fail loudly. A missing key would otherwise produce a valid-looking
            // hash from an empty secret, and every environment would compute a
            // different one -- uniqueness would appear to work and would not.
            throw new RuntimeException(
                'PAN_HASH_KEY is not set. PAN uniqueness and PAN-based activation depend on it.'
            );
        }

        return str_starts_with($key, 'base64:')
            ? (string) base64_decode(substr($key, 7), true)
            : $key;
    }
}
