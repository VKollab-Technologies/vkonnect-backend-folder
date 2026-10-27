<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Support\Disks;
use Illuminate\Console\Command;

/**
 * The inverse of documents:prune-orphans — rows whose file is gone.
 *
 * A row like this is worse than an orphaned file: it renders in the vault as a
 * real, downloadable document and then 404s when the alumnus clicks it. The
 * ones that prompted this were seeded .txt fixtures whose files were never
 * written, but the same state arises from a restore that missed the storage
 * directory, or a file removed outside the app.
 *
 * Soft-deletes rather than purges: the row is evidence that a document once
 * existed, which the audit trail may need, and a soft-delete already removes
 * it from every user-facing query.
 */
class PruneBrokenDocuments extends Command
{
    protected $signature = 'documents:prune-broken
        {--force : Actually remove the rows. Without this the command only reports.}';

    protected $description = 'Soft-delete document rows whose file is missing from storage';

    public function handle(): int
    {
        $disk = Disks::documents();

        // Only live rows: a soft-deleted row whose file is also gone is the
        // expected end state, not a fault.
        $broken = Document::query()
            ->get()
            ->filter(fn (Document $d) => ! $d->disk_path || ! $disk->exists($d->disk_path));

        $this->line(sprintf('live documents: %d   broken: %d', Document::count(), $broken->count()));

        if ($broken->isEmpty()) {
            $this->info('Every document row has a file behind it.');

            return self::SUCCESS;
        }

        $this->table(
            ['id', 'title', 'original_name', 'disk_path'],
            $broken->map(fn (Document $d) => [
                substr($d->id, 0, 8).'…',
                \Illuminate\Support\Str::limit($d->title, 30),
                $d->original_name,
                $d->disk_path ?: '(none)',
            ])->all(),
        );

        if (! $this->option('force')) {
            $this->comment('Dry run — nothing removed. Re-run with --force.');

            return self::SUCCESS;
        }

        foreach ($broken as $document) {
            $document->delete();
        }

        $this->info(sprintf('Soft-deleted %d broken document row(s).', $broken->count()));

        return self::SUCCESS;
    }
}
