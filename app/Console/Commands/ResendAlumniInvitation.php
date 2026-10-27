<?php

namespace App\Console\Commands;

use App\Models\AlumniMaster;
use App\Models\AuditLog;
use App\Services\AlumniMasterImporter;
use Illuminate\Console\Command;

/**
 * Re-send the roster welcome/invitation email for one or more alumni.
 *
 * Exists because an import that dies part-way leaves records created but never
 * announced — batch 019fd916 wrote 15 rows and sent 14 invitations, so one
 * person was on the roster without ever being told they could register.
 *
 * Deliberately routed through AlumniMasterImporter::sendRecordWelcomeEmail(),
 * the same method the single-add controller and the bulk importer both call.
 * A one-off Mail::to() here would deliver the same message while skipping the
 * `email.invitation_sent` audit entry, so the send would be invisible to
 * exactly the reconciliation that discovered it was missing.
 */
class ResendAlumniInvitation extends Command
{
    protected $signature = 'alumni:resend-invitation
        {vkollabId?* : One or more Employee IDs; omit to target everyone with no invitation on record}
        {--dry-run : List who would be emailed without sending}';

    protected $description = 'Re-send the roster invitation email, audited like any other send';

    public function handle(AlumniMasterImporter $importer): int
    {
        $ids = $this->argument('vkollabId');
        $records = $ids
            ? AlumniMaster::whereIn('vkollab_id', array_map(
                fn (string $id) => AlumniMaster::normalizeVkollabId($id), $ids))->get()
            : $this->neverInvited();

        if ($records->isEmpty()) {
            $this->info('Nothing to send.');

            return self::SUCCESS;
        }

        foreach ($records as $record) {
            if (! $record->email) {
                $this->warn("  {$record->vkollab_id} — no email on file, skipped.");

                continue;
            }

            $this->line("  {$record->vkollab_id}  {$record->name}  <{$record->email}>");

            if (! $this->option('dry-run')) {
                $importer->sendRecordWelcomeEmail($record, 'resend');
            }
        }

        $this->info($this->option('dry-run') ? 'Dry run — nothing sent.' : 'Invitations queued.');

        return self::SUCCESS;
    }

    /**
     * Roster records with no `email.invitation_sent` audit entry — i.e. created
     * but never announced, which is the signature of an interrupted import.
     */
    private function neverInvited()
    {
        // A subquery rather than a relation: audit_logs is polymorphic and
        // AlumniMaster has no auditLogs relation, deliberately — the audit
        // trail is written to, never read from, by the models it describes.
        $invited = AuditLog::where('action', 'email.invitation_sent')
            ->where('subject_type', AlumniMaster::class)
            ->whereNotNull('subject_id')
            ->pluck('subject_id');

        return AlumniMaster::whereNull('claimed_by')
            ->whereNotNull('email')
            ->whereNotIn('id', $invited)
            ->get();
    }
}
