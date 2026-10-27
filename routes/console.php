<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Import debris reaper.
 *
 * Hourly rather than daily: a batch stranded at `processing` blocks the UI,
 * which polls it forever, so an operator should not wait a day to be told the
 * import died. The staged-file cutoff is separately generous (24h) because a
 * young file may still belong to a validate the operator is about to confirm.
 *
 * Defense in depth — the job cleans up after itself whenever it is alive to do
 * so. This exists for the cases where it is not: a killed worker, an OOM, or a
 * client disconnect under sync mode.
 */
Schedule::command('imports:prune')->hourly()->withoutOverlapping();

/*
 * Expired Sanctum tokens are never removed on their own.
 *
 * auth:sanctum rejects them, so a stale row is harmless to a request -- it
 * just accumulates. On this database 1,140 of 1,676 tokens were expired, and
 * the table only grows. 24 hours of grace past expiry so a token cannot
 * vanish from under a request that is still in flight.
 */
Schedule::command('sanctum:prune-expired --hours=24')->daily();
