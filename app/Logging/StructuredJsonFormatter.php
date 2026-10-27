<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

/**
 * JsonFormatter that hoists the request-identifying keys to the top level of
 * the emitted object.
 *
 * Monolog puts everything the app supplies inside `context`. Leaving
 * request_id / user_id there works, but every query has to know they are
 * nested, and a caller who logs their own `path` or `ip` key silently collides
 * with the request metadata. Hoisting keeps "which request was this" separate
 * from "what was being logged".
 *
 * Ordering is deliberate: datetime, level, request_id and user_id come first
 * so a line is identifiable from its opening characters when scanning a raw
 * file rather than querying it.
 */
class StructuredJsonFormatter extends JsonFormatter
{
    /** Context keys that describe the request rather than the event. */
    public const REQUEST_KEYS = ['request_id', 'user_id', 'role', 'method', 'path', 'ip'];

    /**
     * @return array<string, mixed>
     */
    protected function normalizeRecord(LogRecord $record): array
    {
        $normalized = parent::normalizeRecord($record);

        // Hoisted from `extra`, where RequestContextProcessor puts them —
        // never from `context`, which callers control and can collide with.
        //
        // Cast: Monolog normalizes an EMPTY extra/context to a stdClass so it
        // serializes as `{}` rather than `[]`, and array_key_exists() rejects
        // an object outright. Every log line written outside an HTTP request
        // hits that path.
        $extra = (array) ($normalized['extra'] ?? []);
        $promoted = [];

        foreach (self::REQUEST_KEYS as $key) {
            if (array_key_exists($key, $extra)) {
                $promoted[$key] = $extra[$key];
                unset($extra[$key]);
            }
        }

        $normalized['extra'] = $extra;

        return [
            'datetime' => $normalized['datetime'] ?? null,
            'level_name' => $normalized['level_name'] ?? null,
            'channel' => $normalized['channel'] ?? null,
            ...$promoted,
            // Everything Monolog produced, minus the keys just placed above.
            // Spread last so nothing from the original record is lost — this
            // is a reformat, not a filter.
            ...array_diff_key($normalized, array_flip(['datetime', 'level_name', 'channel'])),
        ];
    }
}
