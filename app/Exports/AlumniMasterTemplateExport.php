<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * Blank Alumni Master import template — the 10 essential fields HR enters at
 * roster-entry time, plus the 6 HR-managed "My Information" exit-record
 * fields (last working location, band, supervisor name/email, UAN, PF
 * number), plus one illustrative sample row. Everything else on the
 * AlumniMaster/User/AlumniProfile schema (current company, current
 * designation, location, etc.) stays nullable/fillable and is set later by
 * HR via the Alumni Control "Manage" dialog, once the alumnus has
 * activated — not collected here.
 */
class AlumniMasterTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'EMP ID', 'Full Name', 'Personal Email', 'Mobile Number', 'PAN Number',
            'Department', 'Designation', 'DOB', 'Date of Joining', 'Last Working Date',
            'Last Working Location', 'Band', 'Supervisor Name', 'Supervisor Email', 'UAN Number', 'PF Number',
        ];
    }

    public function array(): array
    {
        return [
            [
                'ALM2001', 'Asha Rao', 'asha.rao@example.com', '9000000000', 'ABCDE1234F',
                'SAP Technical', 'SAP ABAP Developer', '1992-07-15', '2019-04-01', '2024-03-31',
                'Bengaluru Office', 'B4', 'Priya Sharma', 'priya.sharma@vkollab.com', '100234567890', 'KA/BNG/00123/456',
            ],
        ];
    }
}
