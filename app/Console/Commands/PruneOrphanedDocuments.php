<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Support\Disks;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Deletes files under documents/ that no `documents` row points at.
 *
 * The upload path is not the leak — DocumentController::destroy and replace
 * both remove the file they orphan. Files accumulate from paths that drop the
 * OWNER rather than the document: deleting an alumnus soft-deletes the user
 * and leaves every file in their vault on disk with nothing left to reach it.
 * Test runs that wrote to the real disk instead of Storage::fake are the other
 * source.
 *
 * Deliberately conservative:
 *   - withTrashed(), so a soft-deleted document's file is never destroyed
 *     while the row could still be restored;
 *   - scoped to documents/ only, so resumes, avatars and staged imports are
 *     out of reach entirely;
 *   - dry-run by default — a command that permanently deletes files should
 *     have to be asked twice.
 */
class PruneOrphanedDocuments extends Command
{
    protected $signature = 'documents:prune-orphans
        {--force : Actually delete. Without this the command only reports.}
        {--quiet-list : Suppress the per-file listing}';

    protected $description = 'Remove document files on disk that no database row references';

    public function handle(): int
    {
        $disk = Disks::documents();

        // withTrashed: a soft-deleted row still owns its file.
        $referenced = Document::withTrashed()
            ->whereNotNull('disk_path')
            ->pluck('disk_path')
            ->map(fn (string $p) => str_replace('\\', '/', $p))
            ->flip();

        $onDisk = collect($disk->allFiles('documents'))
            ->map(fn (string $p) => str_replace('\\', '/', $p));

        $orphans = $onDisk->reject(fn (string $p) => $referenced->has($p))->values();

        $bytes = $orphans->sum(fn (string $p) => $disk->size($p));

        $this->line(sprintf(
            'files on disk: %d   referenced: %d   orphaned: %d (%s)',
            $onDisk->count(),
            $referenced->count(),
            $orphans->count(),
            $this->humanBytes($bytes),
        ));

        if ($orphans->isEmpty()) {
            $this->info('Nothing to prune.');

            return self::SUCCESS;
        }

        if (! $this->option('quiet-list')) {
            $orphans->take(20)->each(fn (string $p) => $this->line("  {$p}"));
            if ($orphans->count() > 20) {
                $this->line(sprintf('  … and %d more', $orphans->count() - 20));
            }
        }

        if (! $this->option('force')) {
            $this->comment('Dry run — nothing deleted. Re-run with --force to remove these.');

            return self::SUCCESS;
        }

        $deleted = 0;
        foreach ($orphans as $path) {
            if ($disk->delete($path)) {
                $deleted++;
            }
        }

        $this->pruneEmptyDirectories();

        $this->info(sprintf('Deleted %d file(s), reclaiming %s.', $deleted, $this->humanBytes($bytes)));

        return self::SUCCESS;
    }

    /**
     * Each vault is its own directory, so pruning a whole vault leaves an empty
     * one behind — 1,295 of them in the case that prompted this command.
     */
    private function pruneEmptyDirectories(): void
    {
        $disk = Disks::documents();
        $removed = 0;

        // Deepest first, so a parent emptied by its children is also caught.
        $directories = collect($disk->allDirectories('documents'))
            ->sortByDesc(fn (string $d) => substr_count($d, '/'));

        foreach ($directories as $directory) {
            if (empty($disk->allFiles($directory)) && empty($disk->allDirectories($directory))) {
                $disk->deleteDirectory($directory);
                $removed++;
            }
        }

        if ($removed > 0) {
            $this->line("Removed {$removed} empty directory(ies).");
        }
    }

    private function humanBytes(int $bytes): string
    {
        return $bytes >= 1048576
            ? round($bytes / 1048576, 2).' MB'
            : round($bytes / 1024, 1).' KB';
    }
}
