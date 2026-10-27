<?php

namespace App\Exports;

use App\Support\PanHash;

use App\Models\AlumniMaster;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Exports the full Alumni Master roster (optionally filtered) — every column
 * on the record, including the fields HR can only set individually (not via
 * the bulk import template), so nothing collected is ever missing from a
 * full export.
 */
class AlumniMasterExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array{search?:string, status?:string, claimed?:string}  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return AlumniMaster::query()
            ->when($this->filters['search'] ?? null, function (Builder $q, $term) {
                $q->where(fn ($s) => $s
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('vkollab_id', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    // PAN is encrypted, so LIKE cannot reach it. A whole PAN
                    // still finds its record through the blind index; a
                    // partial one no longer can, which the placeholder says.
                    ->when(
                        preg_match('/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/', (string) $term),
                        fn ($p) => $p->orWhere('pan_hash', PanHash::make((string) $term)),
                    ));
            })
            ->when($this->filters['status'] ?? null, fn (Builder $q, $s) => $q->where('status', $s))
            ->when(($this->filters['claimed'] ?? null) === 'yes', fn (Builder $q) => $q->whereNotNull('claimed_at'))
            ->when(($this->filters['claimed'] ?? null) === 'no', fn (Builder $q) => $q->whereNull('claimed_at'))
            ->orderBy('vkollab_id');
    }

    public function headings(): array
    {
        return [
            'EMP ID', 'Full Name', 'Personal Email', 'Mobile Number', 'PAN Number',
            'Department', 'Designation', 'Date of Joining', 'Last Working Date',
            'Current Company', 'Current Designation', 'Location', 'DOB', 'Employment Status',
            'Last Working Location', 'Band', 'Supervisor Name', 'Supervisor Email', 'UAN Number', 'PF Number',
            'Registered', 'Registered At',
        ];
    }

    /** @param  AlumniMaster  $row */
    public function map($row): array
    {
        return [
            $row->vkollab_id,
            $row->name,
            $row->email,
            $row->phone,
            $row->pan,
            $row->department,
            $row->designation,
            $row->joined_on?->toDateString(),
            $row->exited_on?->toDateString(),
            $row->organization,
            $row->current_designation,
            $row->location,
            $row->dob?->toDateString(),
            $row->status,
            $row->last_working_location,
            $row->band,
            $row->supervisor_name,
            $row->supervisor_email,
            $row->uan_number,
            $row->pf_number,
            $row->claimed_at ? 'Yes' : 'No',
            $row->claimed_at?->toDateTimeString(),
        ];
    }
}
