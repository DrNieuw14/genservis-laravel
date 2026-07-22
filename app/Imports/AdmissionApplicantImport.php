<?php

namespace App\Imports;

use App\Models\AdmissionApplicant;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Imports the Admission Testing registration export for one AdmissionYear.
 * Two known real data problems drive the logic here (found reviewing the
 * actual CvSU Carmona 2026 export before this was built):
 *
 * 1. A row can have a corrupted Date of Birth (e.g. literal text
 *    "0003-04-30") that parses as a technically-valid date with an
 *    impossible year — these are skipped, not guessed at.
 * 2. The same person can appear twice under two different Control Numbers
 *    (a real double registration) — these are NOT skipped (each Control
 *    Number is still a distinct real submission), but flagged as a
 *    possible duplicate for manual review after import.
 *
 * Re-uploading a corrected file for the same year is expected — an
 * existing Control Number is updated in place (upsert) rather than
 * rejected or duplicated.
 */
class AdmissionApplicantImport implements ToModel, WithHeadingRow
{
    public array $skipped = [];
    public array $possibleDuplicates = [];
    public int $imported = 0;

    public function __construct(private int $admissionYearId)
    {
    }

    public function model(array $row)
    {
        $controlNumber = trim((string) ($row['control_number'] ?? ''));
        $familyName = trim((string) ($row['family_name'] ?? ''));

        if ($controlNumber === '' || $familyName === '') {
            $this->skipped[] = [
                'control_number' => $controlNumber ?: '(blank)',
                'reason' => 'Missing Control Number or Family Name.',
            ];

            return null;
        }

        $dob = $this->parseDateOfBirth($row['date_of_birth'] ?? null);

        if (!$dob) {
            $this->skipped[] = [
                'control_number' => $controlNumber,
                'reason' => 'Invalid or implausible Date of Birth ("' . ($row['date_of_birth'] ?? '') . '").',
            ];

            return null;
        }

        $givenName = $this->repairMojibake(trim((string) ($row['given_name'] ?? '')));
        $middleName = $this->repairMojibake(trim((string) ($row['middle_name'] ?? ''))) ?: null;
        $familyName = $this->repairMojibake($familyName);

        // Matched on given + middle + family name AND date of birth — dropping
        // middle name here (an earlier pass did) produced ~260 false-positive
        // "duplicates" out of 5,520 rows, since first+last name alone collides
        // by chance far more often than expected in a large real name pool.
        $existingByName = AdmissionApplicant::where('admission_year_id', $this->admissionYearId)
            ->where('given_name', $givenName)
            ->where('family_name', $familyName)
            ->when($middleName, fn ($q) => $q->where('middle_name', $middleName), fn ($q) => $q->whereNull('middle_name'))
            ->whereDate('date_of_birth', $dob)
            ->where('control_number', '!=', $controlNumber)
            ->first();

        if ($existingByName) {
            $this->possibleDuplicates[] = [
                'control_number' => $controlNumber,
                'matches_control_number' => $existingByName->control_number,
                'name' => trim("{$givenName} {$familyName}"),
            ];
        }

        $applicant = AdmissionApplicant::updateOrCreate(
            [
                'admission_year_id' => $this->admissionYearId,
                'control_number' => $controlNumber,
            ],
            [
                'given_name' => $givenName,
                'middle_name' => $middleName,
                'family_name' => $familyName,
                'suffix' => $this->repairMojibake(trim((string) ($row['suffix'] ?? ''))) ?: null,
                'date_of_birth' => $dob,
                'sex' => $row['sex'] ?? null,
                'house_no' => $this->repairMojibake($row['house_no'] ?? null),
                'street' => $this->repairMojibake($row['street'] ?? null),
                'barangay' => $this->repairMojibake($row['barangay'] ?? null),
                'municipality' => $this->repairMojibake($row['municipality'] ?? null),
                'municipality_income_class' => $row['municipality_income_class'] ?? null,
                'province' => $this->repairMojibake($row['province'] ?? null),
                'zip_code' => $row['zip_code'] ?? null,
                'campus' => $row['campus'] ?? null,
                'program' => $row['program'] ?? null,
                'email' => $row['email'] ?? null,
                'contact_number' => $this->normalizeContactNumber($row['contact_number'] ?? null),
            ]
        );

        $this->imported++;

        return $applicant;
    }

    private function parseDateOfBirth($value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        try {
            if ($value instanceof \DateTimeInterface) {
                $date = Carbon::instance($value);
            } elseif (is_numeric($value)) {
                // Excel/PhpSpreadsheet hands back the raw serial day-count
                // for date-formatted cells (e.g. 39548), not a PHP date —
                // this is the real reason nearly every row failed on the
                // first pass of this import before the conversion was added.
                $date = Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value));
            } else {
                $date = Carbon::parse((string) $value);
            }
        } catch (\Throwable $e) {
            return null;
        }

        if ($date->year < 1950 || $date->isFuture()) {
            return null;
        }

        return $date;
    }

    // Formatting noise only (spaces/dashes/parentheses) is normalized;
    // a number that's still the wrong length after that is left as-is —
    // clinic didn't ask for us to invent digits.
    private function normalizeContactNumber($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return preg_replace('/[\s\-().]/', '', trim((string) $value)) ?: null;
    }

    /**
     * The real 2026 export has "Ñ" (and other Latin-1-supplement letters)
     * baked in as mojibake — e.g. "NIÑA" arrives as "NIÃ‘A" — because
     * whatever system produced the source file re-encoded UTF-8 bytes
     * through Windows-1252 at some point before this ever reached us. This
     * reverses that exact corruption. Only triggers on the literal "Ã"
     * (U+00C3) signature byte, so it never touches genuinely clean text.
     */
    private function repairMojibake(?string $value): ?string
    {
        if ($value === null || mb_strpos($value, "\u{00C3}") === false) {
            return $value;
        }

        $fixed = @mb_convert_encoding($value, 'Windows-1252', 'UTF-8');

        if (!$fixed || $fixed === $value || !mb_check_encoding($fixed, 'UTF-8')) {
            return $value;
        }

        return $fixed;
    }
}
