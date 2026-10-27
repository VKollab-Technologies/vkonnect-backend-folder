<?php

namespace App\Imports;

use App\Models\AlumniMaster;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

/**
 * Imports the Alumni Master roster from an uploaded Excel/CSV file.
 *
 * Expected header row (case/spacing-insensitive): EMP ID, PAN, Name, Email,
 * Designation, Department, Organization, Phone, Location, Joined On, Exited On,
 * Status. Rows are upserted by EMP ID. Claim state is never overwritten.
 *
 * Results are accumulated for a per-row report returned to the caller.
 */
class AlumniMasterImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public int $created = 0;
    public int $updated = 0;

    /** @var array<int, array{row:int, vkollabId:?string, reason:string}> */
    public array $skipped = [];

    public function __construct(private readonly ?string $importedBy = null) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            // +2: header row is row 1, collection is 0-indexed from row 2.
            $rowNumber = $index + 2;
            $r = $this->normaliseKeys($row->toArray());

            $vkollabId = AlumniMaster::normalizeVkollabId($this->pick($r, ['vkollab_id', 'vkollabid', 'employee_id', 'employeeid', 'emp_id', 'employee']));
            $name = trim((string) $this->pick($r, ['name', 'full_name', 'employee_name']));

            if (! $vkollabId && ! $name) {
                continue; // genuinely blank row — ignore silently
            }
            if (! $vkollabId) {
                $this->skip($rowNumber, null, 'Missing EMP ID');
                continue;
            }
            if (! $name) {
                $this->skip($rowNumber, $vkollabId, 'Missing Name');
                continue;
            }

            $pan = AlumniMaster::normalizePan($this->pick($r, ['pan', 'pan_number', 'pan_no']));
            if ($pan !== null && ! preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]$/', $pan)) {
                $this->skip($rowNumber, $vkollabId, "Invalid PAN format ({$pan})");
                continue;
            }

            $attributes = array_filter([
                'pan' => $pan,
                'name' => $name,
                'email' => $this->cleanEmail($this->pick($r, ['email', 'email_address', 'personal_email'])),
                'designation' => $this->str($this->pick($r, ['designation', 'title', 'role'])),
                'department' => $this->str($this->pick($r, ['department', 'dept'])),
                'organization' => $this->str($this->pick($r, ['organization', 'organisation', 'company', 'current_organization'])),
                'phone' => $this->str($this->pick($r, ['phone', 'mobile', 'contact'])),
                'location' => $this->str($this->pick($r, ['location', 'city'])),
                'joined_on' => $this->date($this->pick($r, ['joined_on', 'date_of_joining', 'doj', 'joining_date'])),
                'exited_on' => $this->date($this->pick($r, ['exited_on', 'date_of_exit', 'dol', 'exit_date'])),
                'status' => $this->status($this->pick($r, ['status'])),
            ], fn ($v) => $v !== null);

            try {
                $existing = AlumniMaster::withTrashed()->where('vkollab_id', $vkollabId)->first();

                if ($existing) {
                    if ($existing->trashed()) {
                        $existing->restore();
                    }
                    $existing->fill($attributes);
                    if ($this->importedBy) {
                        $existing->imported_by = $this->importedBy;
                    }
                    $existing->save();
                    $this->updated++;
                } else {
                    AlumniMaster::create(array_merge($attributes, [
                        'vkollab_id' => $vkollabId,
                        'status' => $attributes['status'] ?? 'active',
                        'imported_by' => $this->importedBy,
                    ]));
                    $this->created++;
                }
            } catch (Throwable $e) {
                $this->skip($rowNumber, $vkollabId, 'Database error: '.$e->getMessage());
            }
        }
    }

    public function summary(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'skippedCount' => count($this->skipped),
            'processed' => $this->created + $this->updated,
            'skipped' => array_slice($this->skipped, 0, 100), // cap detail payload
        ];
    }

    // ---- helpers -------------------------------------------------------------

    /** Normalise header keys to lower snake_case. */
    private function normaliseKeys(array $row): array
    {
        $out = [];
        foreach ($row as $k => $v) {
            $key = strtolower(trim((string) $k));
            $key = preg_replace('/[^a-z0-9]+/', '_', $key);
            $out[trim($key, '_')] = $v;
        }

        return $out;
    }

    private function pick(array $row, array $keys): mixed
    {
        foreach ($keys as $k) {
            if (array_key_exists($k, $row) && $row[$k] !== null && $row[$k] !== '') {
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

    private function status(mixed $v): ?string
    {
        $v = strtolower(trim((string) $v));
        if ($v === '') {
            return null;
        }

        return in_array($v, ['inactive', 'disabled', 'exited', 'retired'], true) ? 'inactive' : 'active';
    }

    private function date(mixed $v): ?string
    {
        if ($v === null || $v === '') {
            return null;
        }
        try {
            // Excel stores dates as numeric serials.
            if (is_numeric($v)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $v))->toDateString();
            }

            return Carbon::parse((string) $v)->toDateString();
        } catch (Throwable) {
            return null;
        }
    }

    private function skip(int $row, ?string $vkollabId, string $reason): void
    {
        $this->skipped[] = ['row' => $row, 'vkollabId' => $vkollabId, 'reason' => $reason];
    }
}
