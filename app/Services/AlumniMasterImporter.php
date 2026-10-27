<?php

namespace App\Services;

use App\Support\PanHash;

use App\Imports\AlumniSheetReader;
use App\Mail\AlumniRecordWelcomeMail;
use App\Models\AlumniMaster;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

/**
 * The Alumni Master import engine. Validates and (optionally) persists alumni
 * roster rows from an uploaded sheet. Shared by the dry-run validation pass and
 * the queued import job, so the preview and the real import behave identically.
 *
 * Modes:
 *   update — upsert (update existing EMP IDs, create new)
 *   skip   — skip existing EMP IDs, create only new
 *   create — alias of skip (create only new records)
 */
class AlumniMasterImporter
{
    /** @return Collection<int, Collection> heading-keyed rows of the first sheet */
    public function readRows(string $absolutePath): Collection
    {
        $sheets = Excel::toCollection(new AlumniSheetReader, $absolutePath);

        return $sheets->first() ?? collect();
    }

    /**
     * Process rows. When $dryRun is true nothing is written — the result reflects
     * what WOULD happen, which drives the pre-import validation preview.
     *
     * @return array{total:int, created:int, updated:int, skipped:int, failed:int, failedRows:array}
     */
    public function process(Collection $rows, string $mode = 'update', bool $dryRun = true, ?string $importedBy = null): array
    {
        $mode = in_array($mode, ['update', 'skip', 'create'], true) ? $mode : 'update';

        // Preload existing keys for duplicate detection (one query each, scalable).
        $existingIds = AlumniMaster::withTrashed()->pluck('vkollab_id')->filter()
            ->mapWithKeys(fn ($v) => [strtoupper($v) => true])->all();
        // Live rows only, same reasoning as the email lookup below: a deleted
        // record must not reserve a PAN forever. The partial unique index frees
        // the slot at the database level, so counting trashed rows here rejected
        // imports the database would have accepted.
        //
        // $existingIds above deliberately keeps withTrashed(): it decides
        // create-vs-update rather than accept-vs-reject, and a trashed row is
        // still a row the importer may need to restore.
        // Keyed by the blind index, not the PAN. Two reasons: the PAN
        // column is ciphertext that differs per row for the same value,
        // and pluck() would decrypt every row on every import to build a
        // map the hash gives directly off an indexed column.
        $panOwner = AlumniMaster::whereNotNull('pan_hash')->pluck('vkollab_id', 'pan_hash')->all();
        // Live rows only, matching the manual form's unique rule. A deleted
        // record must not reserve an address forever: the partial unique index
        // already frees the slot at the database level, so counting trashed
        // rows here rejected imports the database would have accepted.
        $emailOwner = AlumniMaster::whereNotNull('email')->pluck('vkollab_id', 'email')->all();

        $seenId = $seenPan = $seenEmail = [];
        $created = $updated = $skipped = 0;
        $failedRows = [];
        $total = 0;
        if ($rows->count() > 0) {

            $firstRow = $this->normaliseKeys(
                is_array($rows->first())
                    ? $rows->first()
                    : $rows->first()->toArray()
            );

            if (
                !array_key_exists('emp_id', $firstRow) &&
                !array_key_exists('vkollab_id', $firstRow)
            ) {
               \App\Services\AuditLogger::log(
'alumni_import.validation_failed',
'alumni',
null,
[
'row' => 1,
'errors' => 'Invalid file uploaded. Please use the Alumni Import Template.',
],
null,
'failure'
);
                return [
                     'total' => 0,
                     'created' => 0,
                     'updated' => 0,
                     'skipped' => 0,
                     'failed' => 1,
                     'failedRows' => [
                         [
                             'row' => 1,
                             'vkollabId' => null,
                             'reasons' => [
                                 'Invalid file uploaded. Please use the Alumni Import 
          Template.'
                             ]
                         ]
                     ]
                  ];
              }
           }

        foreach ($rows as $index => $raw) {
            $r = $this->normaliseKeys(is_array($raw) ? $raw : $raw->toArray());

            $vkollabId = AlumniMaster::normalizeVkollabId($this->pick($r, ['vkollab_id', 'vkollabid', 'employee_id', 'employeeid', 'emp_id', 'employee']));
            $name = $this->str($this->pick($r, ['full_name', 'name', 'employee_name']));

            // Genuinely blank row — ignore (don't count).
            if (! $vkollabId && ! $name && ! $this->rowHasAnyValue($r)) {
                continue;
            }
            $total++;
            $rowNumber = $index + 2; // header is row 1

            $reasons = [];
            if (! $vkollabId) {
                $reasons[] = 'Missing EMP ID';
            }
            if (! $name) {
                $reasons[] = 'Missing Full Name';
            }
         

            $pan = AlumniMaster::normalizePan($this->pick($r, ['pan_number', 'pan', 'pan_no']));
            if (! $pan) {
                $reasons[]='Missing PAN Number';
            }
            if ($pan !== null && ! preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]$/', $pan)) {
                $reasons[] = "Invalid PAN format ({$pan})";
            }

            $email = $this->cleanEmail($this->pick($r, ['personal_email', 'email', 'email_address']));
            if (! $email){
                $reasons[]= 'Missing Email';
            }
            if ($email !== null && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $reasons[] = "Invalid email format ({$email})";
            }

            $phone = $this->phone($this->pick($r, ['mobile_number', 'mobile', 'phone', 'contact']), $reasons);
            if (! $phone) {
                $reasons[] = 'Missing Mobile Number';
            }
             $department = $this->str($this->pick($r, ['department', 'dept']));

if (! $department) {
    $reasons[] = 'Missing Department';
}

$designation = $this->str($this->pick($r, ['designation', 'title', 'role']));

if (! $designation) {
    $reasons[] = 'Missing Designation';
}

            $joinedOn = $this->date($this->pick($r, ['date_of_joining', 'joined_on', 'doj', 'joining_date']), 'Date of Joining', $reasons);
            $exitedOn = $this->date($this->pick($r, ['last_working_date', 'last_working_day', 'exited_on', 'date_of_exit', 'dol', 'exit_date']), 'Last Working Date', $reasons);
            $dob = $this->date($this->pick($r, ['dob', 'date_of_birth']), 'DOB', $reasons);
            if (! $dob) {
                $reasons[] = 'Missing DOB';
            }

            if (! $joinedOn) {
                $reasons[] = 'Missing Date of Joining';
             }

            if (! $exitedOn) {
                $reasons[] = 'Missing Last Working Date';
             }
            // Same date and UAN sanity the single-record form enforces. A bulk
            // upload that accepted what the form rejects would quietly become
            // the way bad data gets in.
            //
            // date() hands back an ISO string, not a Carbon instance, so the
            // ordering checks compare strings (safe for Y-m-d) and only the age
            // calculation parses.
            if ($dob !== null) {
                if ($dob >= Carbon::now()->toDateString()) {
                    $reasons[] = 'DOB must be in the past';
                } elseif (Carbon::parse($dob)->age < 18) {
                    $reasons[] = 'DOB makes the alumnus under 18 ('.Carbon::parse($dob)->age.') — check the year';
                }
            }

            if ($joinedOn !== null && $exitedOn !== null && $exitedOn < $joinedOn) {
                $reasons[] = 'Last Working Date is earlier than Date of Joining';
            }

            $uan = $this->str($this->pick($r, ['uan_number', 'uan']));
            if (! $uan) {
                $reasons[] = 'Missing UAN Number';
            }
             $pfNumber = $this->str(
    $this->pick($r, [
        'pf_number',
        'pf',
        'pf_account_number'
    ])
);

if (! $pfNumber) {
    $reasons[] = 'Missing PF Number';
}

elseif (! preg_match('/^[A-Z0-9]{22}$/', strtoupper($pfNumber))) {
    $reasons[] = "Invalid PF Number ({$pfNumber}) - must be exactly 22 alphanumeric characters";
}
            
            $location = $this->str($this->pick($r, [
                'last_working_location',
                'last_working_office',
                'exit_location'
             ]));

             if (! $location) {
                 $reasons[] = 'Missing Last Working Location';
             }

             $band = $this->str($this->pick($r, [
                 'band',
                 'grade'
              ]));

              if (! $band) {
                  $reasons[] = 'Missing Band';
              }

              $supervisorName = $this->str($this->pick($r, [
                  'supervisor_name',
                  'supervisor',
                  'manager_name'
               ]));

              if (! $supervisorName) {
                  $reasons[] = 'Missing Supervisor Name';
               }

               $supervisorEmail = $this->cleanEmail($this->pick($r, [
                   'supervisor_email',
                   'manager_email'
                ]));

               if (! $supervisorEmail) {
                   $reasons[] = 'Missing Supervisor Email';
                }
            if ($uan !== null && ! preg_match('/^[0-9]{12}$/', $uan)) {
                $reasons[] = "Invalid UAN ({$uan}) — must be 12 digits";
            }



            // Duplicate-within-file checks.
            if ($vkollabId) {
                if (isset($seenId[$vkollabId])) {
                    $reasons[] = 'Duplicate EMP ID in file';
                }
                $seenId[$vkollabId] = true;
            }
            if ($pan) {
                $panKey = PanHash::make($pan);
                if (isset($seenPan[$pan])) {
                    $reasons[] = 'Duplicate PAN in file';
                }
                $seenPan[$pan] = true;
                // PAN already belongs to a DIFFERENT record in the DB.
                if (isset($panOwner[$panKey]) && strtoupper((string) $panOwner[$panKey]) !== $vkollabId) {
                    $reasons[] = "PAN already used by {$panOwner[$panKey]}";
                }
            }
            if ($email) {
                if (isset($seenEmail[$email])) {
                    $reasons[] = 'Duplicate email in file';
                }
                $seenEmail[$email] = true;
                if (isset($emailOwner[$email]) && strtoupper((string) $emailOwner[$email]) !== $vkollabId) {
                    $reasons[] = "Email already used by {$emailOwner[$email]}";
                }
            }

            if ($reasons) {

    \App\Services\AuditLogger::log(
        'alumni_import.validation_failed',
        'alumni',
        null,
        [
            'row' => $rowNumber,
            'vkollabId' => $vkollabId,
            'errors' => implode(', ', $reasons),
        ],
        null,
        'failure'
    );

    $failedRows[] = [
        'row' => $rowNumber,
        'vkollabId' => $vkollabId,
        'reasons' => $reasons,
    ];

    continue;
}

            $exists = isset($existingIds[$vkollabId]);

            // Apply mode.
            if ($exists && $mode !== 'update') {
                $skipped++;
                continue;
            }

            // The 10 HR-entry fields (+ status), plus the 6 HR-managed "My
            // Information" exit-record fields (last working location, band,
            // supervisor name/email, UAN, PF number) are read from the sheet.
            // organization/current_designation/location are intentionally not
            // collected here — they stay nullable on AlumniMaster and are set
            // later by HR via the Alumni Control "Manage" dialog, once the
            // alumnus has activated. A legacy file that still has those
            // columns just has them silently ignored.
            $attributes = array_filter([
                'pan' => $pan,
                'name' => $name,
                'email' => $email,
                'designation' => $this->str($this->pick($r, ['designation', 'title', 'role'])),
                'department' => $this->str($this->pick($r, ['department', 'dept'])),
                'phone' => $phone,
                'dob' => $dob,
                'joined_on' => $joinedOn,
                'exited_on' => $exitedOn,
                'status' => $this->status($this->pick($r, ['employment_status', 'status'])),
                'last_working_location' => $this->str($this->pick($r, ['last_working_location', 'last_working_office', 'exit_location'])),
                'band' => $this->str($this->pick($r, ['band', 'grade'])),
                'supervisor_name' => $this->str($this->pick($r, ['supervisor_name', 'supervisor', 'reporting_manager', 'manager_name'])),
                'supervisor_email' => $this->cleanEmail($this->pick($r, ['supervisor_email', 'supervisor_e_mail', 'manager_email'])),
                'uan_number' => $uan,
                'pf_number' => $this->str($this->pick($r, ['pf_number', 'pf', 'pf_account_number'])),
            ], fn ($v) => $v !== null);

            if ($dryRun) {
                $exists ? $updated++ : $created++;
                continue;
            }

            try {
                if ($exists) {
                    $record = AlumniMaster::withTrashed()->where('vkollab_id', $vkollabId)->first();
                    if ($record->trashed()) {
                        $record->restore();
                    }
                    $record->fill($attributes);
                    if ($importedBy) {
                        $record->imported_by = $importedBy;
                    }
                    $record->save();
                    $updated++;
                } else {
                    $record = AlumniMaster::create(array_merge($attributes, [
                        'vkollab_id' => $vkollabId,
                        'status' => $attributes['status'] ?? 'active',
                        'imported_by' => $importedBy,
                    ]));
                    $created++;
                    $existingIds[$vkollabId] = true;
                    $this->sendRecordWelcomeEmail($record, 'import');
                }
            } catch (Throwable $e) {
                $failedRows[] = ['row' => $rowNumber, 'vkollabId' => $vkollabId, 'reasons' => ['Database error: '.$e->getMessage()]];
            }
        }

        return [
            'total' => $total,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'failed' => count($failedRows),
            'failedRows' => $failedRows,
        ];
    }

    /**
     * Queue the "new alumni record" welcome email (Workflow 4 — no OTP) to the
     * record's own `email` column, dynamically, and audit the send. Used by
     * both the single-add controller and this importer's bulk path. Never
     * throws — a mail failure must not fail record creation/import.
     */
    public function sendRecordWelcomeEmail(AlumniMaster $record, string $source): void
    {
        if (! $record->email) {
            return; // nothing to send to — no email on file for this record
        }

        $meta = ['email' => OtpService::maskEmail($record->email), 'source' => $source];

        try {
            Mail::to($record->email)->queue(new AlumniRecordWelcomeMail(
                name: $record->name,
                vkollabId: $record->vkollab_id,
                department: $record->department,
                designation: $record->designation,
                joinedOn: $record->joined_on?->format('d M Y'),
                exitedOn: $record->exited_on?->format('d M Y'),
            ));

            AuditLog::create([
                'user_id' => $record->imported_by,
                'action' => 'email.invitation_sent',
                'module' => 'alumni',
                'subject_type' => AlumniMaster::class,
                'subject_id' => $record->id,
                'meta' => $meta + ['status' => 'queued'],
                'created_at' => now(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Alumni record welcome mail dispatch failed: '.$e->getMessage());

            AuditLog::create([
                'user_id' => $record->imported_by,
                'action' => 'email.invitation_sent',
                'module' => 'alumni',
                'subject_type' => AlumniMaster::class,
                'subject_id' => $record->id,
                'meta' => $meta + ['status' => 'failed', 'error' => $e->getMessage()],
                'created_at' => now(),
            ]);
        }
    }

    // ---- helpers -------------------------------------------------------------

    private function normaliseKeys(array $row): array
    {
        $out = [];
        foreach ($row as $k => $v) {
            $key = preg_replace('/[^a-z0-9]+/', '_', strtolower(trim((string) $k)));
            $out[trim($key, '_')] = $v;
        }

        return $out;
    }

    private function rowHasAnyValue(array $row): bool
    {
        foreach ($row as $v) {
            if ($v !== null && trim((string) $v) !== '') {
                return true;
            }
        }

        return false;
    }

    private function pick(array $row, array $keys): mixed
    {
        foreach ($keys as $k) {
            if (array_key_exists($k, $row) && $row[$k] !== null && trim((string) $row[$k]) !== '') {
                return $row[$k];
            }
        }

        return null;
    }

    private function str(mixed $v): ?string
    {
        $v = trim((string) $v);

        return $v === '' ? null : $v;
    }

    private function cleanEmail(mixed $v): ?string
    {
        $v = strtolower(trim((string) $v));

        return $v === '' ? null : $v;
    }

    /**
     * Mobile number, held to the same standard as the manual Add Alumni form
     * (App\Rules\PhoneNumber): exactly ten digits.
     *
     * This used to accept 10-15 digits and STORE THE RAW STRING, so a bulk
     * import could put "+91 98765 43210" into a column the manual form would
     * have rejected outright — two doors into one field with different locks,
     * and the imported value then failed validation the next time anyone edited
     * that record by hand.
     *
     * Separators are stripped before counting, so spreadsheet formatting
     * ("98765-43210", "98765 43210") still imports; what is REJECTED is a value
     * that is not ten digits once punctuation is removed, including country
     * prefixes. What is STORED is always the bare ten digits, matching the
     * manual form's shape exactly.
     */
    private function phone(mixed $v, array &$reasons): ?string
    {
        $v = trim((string) $v);

        if ($v === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $v);

        if (strlen($digits) !== 10) {
            $reasons[] = "Invalid mobile number ({$v}) — enter 10 digits";

            return null;
        }

        return $digits;
    }

    private function status(mixed $v): ?string
    {
        $v = strtolower(trim((string) $v));
        if ($v === '') {
            return null;
        }

        return in_array($v, ['inactive', 'disabled', 'exited', 'retired', 'relieved', 'left'], true) ? 'inactive' : 'active';
    }

    /** Returns 'Y-m-d' on success, null if empty; pushes a reason if present-but-invalid. */
    private function date(mixed $v, string $label, array &$reasons): ?string
    {
        $v = trim((string) $v);
        if ($v === '') {
            return null;
        }
        try {
            if (is_numeric($v)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $v))->toDateString();
            }

            return Carbon::parse($v)->toDateString();
        } catch (Throwable) {
            $reasons[] = "Invalid date in {$label} ({$v})";

            return null;
        }
    }
}
