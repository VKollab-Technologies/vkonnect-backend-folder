<?php

namespace App\Support;

use App\Models\DocumentCategory;
use App\Models\User;

/**
 * Generates the enterprise document filename convention:
 * EMPID_DOCUMENTCODE_YEAR (e.g. "EMP1234_FORM16_2025") — applied at upload
 * time server-side, never taken from the user's uploaded file name. Used for
 * both the on-disk storage name and the name served back on view/download.
 *
 * The YEAR segment is omitted entirely (not defaulted to the upload year)
 * for documents with no financial year on file — PAN, Aadhaar, Full & Final
 * Settlement, and Experience Letter are one-time documents where a year
 * would misleadingly imply a period the document doesn't have. Where a
 * financial year like "2024-2025" exists, the END year (2025) is used as the
 * single year segment.
 */
class DocumentFilename
{
    /**
     * Base name (no extension) — e.g. "EMP1234_FORM16_2025" or "EMP1234_PAN".
     *
     * `$documentName` overrides the category's code for catch-all categories.
     * "Personal Documents" holds many different documents (PAN, Aadhaar, and
     * whatever else HR adds), so naming every one of them
     * EMP1234_PERSONAL_DOCS would make them indistinguishable both on disk and
     * in the alumnus's vault. When HR names the document, that name becomes
     * the DOCNAME segment instead.
     */
    public static function build(
        User $owner,
        DocumentCategory $category,
        ?string $financialYear,
        ?string $documentName = null,
        ?int $periodMonth = null,
    ): string {
        return self::buildFor(
            $owner->alumniProfile?->vkollab_id ?? $owner->vkollab_id,
            $category,
            $financialYear,
            $documentName,
            $periodMonth,
        );
    }

    /**
     * Same convention, keyed by EMP ID rather than an account.
     *
     * HR can file a document against a roster record before the alumnus has
     * registered, and there is no User to read the EMP ID from in that case —
     * only the AlumniMaster row.
     */
    /**
     * `$category` is nullable because a document can outlive its category: a
     * Super Admin may delete a non-empty category, which detaches the documents
     * rather than destroying them. Replacing the file on one of those must
     * still produce a sensible name, not a TypeError.
     */
    public static function buildFor(
        ?string $vkollabId,
        ?DocumentCategory $category,
        ?string $financialYear,
        ?string $documentName = null,
        ?int $periodMonth = null,
    ): string {
        $empId = $vkollabId ?: 'UNASSIGNED';
        $empId = strtoupper(preg_replace('/[^A-Za-z0-9]+/', '', $empId));

        $code = $documentName !== null && trim($documentName) !== ''
            ? self::codeFromName($documentName)
            : ($category?->filename_code ?? ($category ? self::codeFromName($category->name) : 'UNCATEGORIZED'));

        $segments = [$empId, $code];

        // Monthly documents (Salary Slips) carry MM_YYYY with the CALENDAR
        // year the month actually falls in, not the financial year string.
        // A payslip filed as 05 in FY 2025-2026 is May 2025; one filed as 02
        // in the same FY is February 2026, and naming both "2025" or both
        // "2026" would put two different months under one label.
        //
        // Falls through to the annual form when no month is recorded, so the
        // documents uploaded before this existed keep the names they have.
        if ($periodMonth !== null && ($calendarYear = self::calendarYear($financialYear, $periodMonth))) {
            $segments[] = str_pad((string) $periodMonth, 2, '0', STR_PAD_LEFT);
            $segments[] = $calendarYear;
        } elseif ($year = self::year($financialYear)) {
            $segments[] = $year;
        }

        return implode('_', $segments);
    }

    /**
     * The calendar year a month falls in, given its financial year.
     *
     * The Indian financial year runs April to March, so months 4-12 belong to
     * its first year and months 1-3 to its second.
     */
    public static function calendarYear(?string $financialYear, ?int $month): ?string
    {
        if (! $financialYear || $month === null || $month < 1 || $month > 12) {
            return null;
        }

        if (! preg_match('/^(\d{4})-(\d{4})$/', $financialYear, $m)) {
            return null;
        }

        return $month >= 4 ? $m[1] : $m[2];
    }

    /** The end year of a "2024-2025"-shaped financial year, or null if absent/malformed. */
    public static function year(?string $financialYear): ?string
    {
        if ($financialYear && preg_match('/^\d{4}-(\d{4})$/', $financialYear, $m)) {
            return $m[1];
        }

        return null;
    }

    /** Fallback machine-friendly code derived from a category's display name. */
    public static function codeFromName(string $name): string
    {
        $code = strtoupper(trim(preg_replace('/[^A-Za-z0-9]+/', '_', $name), '_'));

        return $code !== '' ? $code : 'DOCUMENT';
    }
}
