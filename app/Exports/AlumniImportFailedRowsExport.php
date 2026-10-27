<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * Downloadable "failed records" report for one import batch — row number,
 * VKollab ID, and the reason(s) each row was rejected.
 */
class AlumniImportFailedRowsExport implements FromArray, WithHeadings
{
    /** @param array<int, array{row:int, vkollabId:?string, reasons:array}> $failedRows */
    public function __construct(private readonly array $failedRows) {}

    public function headings(): array
    {
        return ['Row', 'EMP ID', 'Validation Issues'];
    }

    public function array(): array
    {
        return array_map(fn ($f) => [
            $f['row'] ?? '',
            $f['vkollabId'] ?? '',
            implode('; ', $f['reasons'] ?? []),
        ], $this->failedRows);
    }
}
