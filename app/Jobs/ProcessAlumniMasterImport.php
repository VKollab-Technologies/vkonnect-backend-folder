<?php

namespace App\Jobs;

use App\Models\AlumniImportBatch;
use App\Models\AlumniMaster;
use App\Models\AuditLog;
use App\Services\AlumniMasterImporter;
use App\Services\NotificationService;
use App\Support\Disks;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Processes a staged Alumni Master upload on the queue (`database` connection
 * here) — keeps the request
 * thread free and scales to large files. Updates the batch record with the
 * outcome (created/updated/skipped/failed) and the failed rows, then audits it.
 */
class ProcessAlumniMasterImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800; // 30 min for very large files
    public int $tries = 1;

    public function __construct(
        public string $batchId,
        public string $relativePath,
        public string $mode,
        public ?string $importedBy,
        public ?string $importedByName,
        public ?string $ip,
        public ?string $importedByRole = null,
    ) {}

    public function handle(AlumniMasterImporter $importer, NotificationService $notifications): void
    {
        $batch = AlumniImportBatch::find($this->batchId);
        if (! $batch) {
            return;
        }

        try {
            $absolute = Disks::imports()->path($this->relativePath);
            $rows = $importer->readRows($absolute);
            $result = $importer->process($rows, $this->mode, false, $this->importedBy);

            $batch->update([
                'status' => 'completed',
                'total_rows' => $result['total'],
                'created_count' => $result['created'],
                'updated_count' => $result['updated'],
                'skipped_count' => $result['skipped'],
                'failed_count' => $result['failed'],
                'failed_rows' => array_slice($result['failedRows'], 0, 1000),
                'completed_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => $this->importedBy,
                'actor_name' => $this->importedByName,
                'actor_role' => $this->importedByRole,
                'action' => 'alumni_master.imported',
                'module' => 'alumni',
                'subject_id' => $batch->id,
                'meta' => [
                    'file' => $batch->original_filename,
                    'mode' => $this->mode,
                    'created' => $result['created'],
                    'updated' => $result['updated'],
                    'skipped' => $result['skipped'],
                    'failed' => $result['failed'],
                ],
                'ip_address' => $this->ip,
                'created_at' => now(),
            ]);

            $notifications->notifyRoles(
                ['super_admin'],
                'alumni',
                'Alumni Master Imported',
                "{$this->importedByName} imported {$batch->original_filename}: {$result['created']} created, {$result['updated']} updated, {$result['failed']} failed.",
                '/super-admin/alumni-master',
            );
        } catch (Throwable $e) {
            $batch->update(['status' => 'failed', 'error' => $e->getMessage(), 'completed_at' => now()]);
            throw $e;
        } finally {
            Disks::imports()->delete($this->relativePath);
        }
    }

    public function failed(?Throwable $e): void
    {
        AlumniImportBatch::where('id', $this->batchId)->where('status', 'processing')
            ->update([
                'status' => 'failed',
                'error' => $this->partialProgressNote($e?->getMessage() ?? 'Import failed.'),
                'completed_at' => now(),
            ]);
        Disks::imports()->delete($this->relativePath);
    }

    /**
     * Always warn that rows may already exist.
     *
     * An import is written row by row, so a failure never means "changed
     * nothing" — a message implying a clean rollback would send someone
     * re-importing over live data.
     *
     * A process that is KILLED rather than thrown out of (an OOM, a worker
     * restart, or a client disconnect under QUEUE_CONNECTION=sync) runs none
     * of the handlers here at all — not catch, not finally, not failed().
     * `imports:prune` is the backstop for that case, and it writes this same
     * wording. A shutdown handler was tried and rejected: it fires during
     * teardown, when the container and DB connection may already be gone, so
     * it throws in exactly the circumstances it was meant to cover.
     */
    private function partialProgressNote(string $reason): string
    {
        return $reason.' Some rows may already have been imported; check the roster before re-running.';
    }
}
