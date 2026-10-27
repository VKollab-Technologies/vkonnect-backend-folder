<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Minimal reader used with Excel::toCollection — turns the uploaded sheet into a
 * collection of heading-keyed rows for the AlumniMasterImporter to process.
 */
class AlumniSheetReader implements WithHeadingRow
{
}
