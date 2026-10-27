<?php

namespace App\Console\Commands;

use App\Models\AlumniImportBatch;
use App\Support\Disks;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Reaps the two kinds of debris a bulk import can leave behind.
 *
 * ProcessAlumniMasterImport already has try/catch/finally AND a failed()
 * handler, so an import that merely THROWS cleans up after itself. What it
 * cannot survive is the process disappearing: PHP-level handlers need the
 * script to keep running, and a killed worker, an OOM, or (under
 * QUEUE_CONNECTION=sync) a client disconnect terminating the request all skip
 * every one of them. The batch is then frozen at `processing` forever — the UI
 * polls it indefinitely — and its staged upload is never deleted.
 *
 * Abandoned validates leak by a second route entirely: validateUpload() stages
 * a file so the confirm step can read it, and deletes it only on the error
 * path. A validate the operator never confirms leaves its upload behind with
 * nothing left to reference it.
 *
 * Those files are alumni rosters — names, emails, PANs, phone numbers — so
 * this is a data-retention question before it is a disk-space one.
 */
class PruneStaleImports extends Command
{
    protected $signature = 'imports:prune
        {--batch-minutes=60 : Mark a batch stuck in `processing` beyond this as failed}
        {--file-hours=24 : Delete staged uploads older than this}
        {--dry-run : Report what would change without writing}';

    protected $description = 'Fail import batches stranded in `processing` and delete orphaned staged uploads';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $batchCutoff = now()->subMinutes((int) $this->option('batch-minutes'));
        $fileCutoff = now()->subHours((int) $this->option('file-hours'));

        $this->reapBatches($batchCutoff, $dryRun);
        $this->reapFiles($fileCutoff, $dryRun);

        if ($dryRun) {
            $this->info('Dry run — nothing written.');
        }

        return self::SUCCESS;
    }

    /**
     * A batch still `processing` long after any real import would have finished
     * did not finish — it died. Say so, rather than leaving the UI to poll a
     * status that will never change.
     */
    private function reapBatches(Carbon $cutoff, bool $dryRun): void
    {
        $stale = AlumniImportBatch::where('status', 'processing')
            ->where('created_at', '<', $cutoff)
            ->get();

        if ($stale->isEmpty()) {
            $this->line('No stranded batches.');

            return;
        }

        foreach ($stale as $batch) {
            $this->line(sprintf(
                '  %s  %s  started %s',
                substr($batch->id, 0, 8),
                $batch->original_filename,
                $batch->created_at->diffForHumans(),
            ));

            if ($dryRun) {
                continue;
            }

            $batch->update([
                'status' => 'failed',
                // Deliberately explicit that rows may have landed: an import is
                // written row by row, so "failed" here means "did not finish",
                // NOT "changed nothing". Anyone reconciling this needs to know
                // to check the roster rather than assume a clean rollback.
                'error' => 'Import did not complete — the process ended before finishing. '
                    .'Some rows may already have been imported; check the roster before re-running.',
                'completed_at' => now(),
            ]);
        }

        $this->warn(sprintf('%s stranded batch(es) marked failed.', $stale->count()));
    }

    /** Staged uploads with no import left to consume them. */
    private function reapFiles(Carbon $cutoff, bool $dryRun): void
    {
        $disk = Disks::imports();
        $deleted = 0;
        $bytes = 0;

        foreach ($disk->files('imports') as $path) {
            if (Carbon::createFromTimestamp($disk->lastModified($path))->gte($cutoff)) {
                continue; // young enough that a confirm step may still want it
            }

            $bytes += $disk->size($path);
            $deleted++;

            if (! $dryRun) {
                $disk->delete($path);
            }
        }

        $this->line($deleted === 0
            ? 'No orphaned staged uploads.'
            : sprintf('%d orphaned staged upload(s), %s KB.', $deleted, number_format($bytes / 1024, 1)));
    }
}
