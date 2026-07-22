<?php

namespace App\Imports;

use App\Models\AdmissionApplicant;
use App\Models\ExamResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Imports one exam session's "Master List of Examinees" sheet (real CvSU
 * Admission Testing format: title/blank rows, then the real header on row
 * 6 — "Name of Examinee", "Code", "[T]", "Grade", "PR", "Remarks").
 *
 * The cross-check the user asked for: "Code" on this sheet is the same
 * person's Control Number from the Applicant Roster, zero-padded to 6
 * digits (verified against the real April 21 sheet before this was built —
 * every sampled code matched a real roster row by name). Each result row
 * is looked up against admission_applicants for the same AdmissionYear;
 * the match is recorded on the row itself (matched / name_mismatch /
 * not_found) rather than silently dropping anything that doesn't match —
 * an examinee who can't be found in the roster is exactly the kind of
 * discrepancy Admission Testing staff need to see, not lose.
 */
class ExamResultImport implements ToModel, WithHeadingRow
{
    public int $imported = 0;
    public int $matched = 0;
    public int $nameMismatch = 0;
    public int $notFound = 0;

    public function __construct(private int $examSessionId, private int $admissionYearId)
    {
    }

    public function headingRow(): int
    {
        return 6;
    }

    public function model(array $row)
    {
        $examineeName = $this->repairMojibake(trim((string) ($row['name_of_examinee'] ?? '')));

        if ($examineeName === '') {
            return null;
        }

        $rawCode = trim((string) ($row['code'] ?? ''));
        $controlNumber = $rawCode !== '' ? (string) (int) $rawCode : null;

        $applicant = null;

        if ($controlNumber !== null) {
            $applicant = AdmissionApplicant::where('admission_year_id', $this->admissionYearId)
                ->where('control_number', $controlNumber)
                ->first();
        }

        if (!$applicant) {
            $matchStatus = ExamResult::NOT_FOUND;
            $this->notFound++;
        } elseif ($this->namesAgree($examineeName, $applicant->family_name)) {
            $matchStatus = ExamResult::MATCHED;
            $this->matched++;
        } else {
            $matchStatus = ExamResult::NAME_MISMATCH;
            $this->nameMismatch++;
        }

        $result = ExamResult::create([
            'exam_session_id' => $this->examSessionId,
            'admission_applicant_id' => $applicant?->id,
            'code' => $rawCode ?: null,
            'examinee_name' => $examineeName,
            'raw_score' => is_numeric($row['t'] ?? null) ? (int) $row['t'] : null,
            'grade' => is_numeric($row['grade'] ?? null) ? (float) $row['grade'] : null,
            'percentile_rank' => $row['pr'] ?? null,
            'remarks' => trim((string) ($row['remarks'] ?? '')) ?: null,
            'match_status' => $matchStatus,
        ]);

        $this->imported++;

        return $result;
    }

    // The sheet's name is "SURNAME, GIVEN MIDDLE_INITIAL" — just checking the
    // family name appears in it is enough to catch a code pointing at the
    // wrong person, without needing to fully parse the sheet's name format.
    // Whitespace is stripped before comparing — "DELA CRUZ" vs "DELACRUZ"
    // is the same real surname spelled two common ways, not a mismatch;
    // a genuine typo (e.g. "CABBALLERO" vs "CABALLERO") still won't match.
    private function namesAgree(string $sheetName, string $rosterFamilyName): bool
    {
        if ($rosterFamilyName === '') {
            return false;
        }

        $normalize = fn (string $s) => preg_replace('/\s+/', '', strtoupper($s));

        return str_contains($normalize($sheetName), $normalize($rosterFamilyName));
    }

    private function repairMojibake(string $value): string
    {
        if (mb_strpos($value, "\u{00C3}") === false) {
            return $value;
        }

        $fixed = @mb_convert_encoding($value, 'Windows-1252', 'UTF-8');

        if (!$fixed || $fixed === $value || !mb_check_encoding($fixed, 'UTF-8')) {
            return $value;
        }

        return $fixed;
    }
}
