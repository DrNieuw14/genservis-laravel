<?php

namespace App\Imports;

use App\Models\AdmissionApplicant;
use App\Models\ProgramRanking;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Imports the "LIST OF EXAMINEES" master sheet from the real consolidated
 * Raw Data workbook — one row per examinee across every exam date, already
 * carrying their Program and Address (unlike the per-session results
 * sheets, which don't have Program at all). The workbook's other 10 sheets
 * are just this same master list filtered/sorted per program (confirmed by
 * cross-checking their Codes against the master before building this) —
 * only the master sheet needs importing; per-program grouping is derived
 * in the app instead of re-importing 10 redundant tabs.
 *
 * No PASSED/FAILED marker exists anywhere in the source file — 'remarks'
 * is carried through as-is (currently always blank) rather than computed,
 * per explicit user decision to hold off on a passing cutoff for now.
 */
class ProgramRankingImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public int $imported = 0;
    public int $matched = 0;
    public int $nameMismatch = 0;
    public int $notFound = 0;

    public function __construct(private int $admissionYearId)
    {
    }

    /**
     * Without this, Laravel Excel processes every sheet in the workbook —
     * the 10 per-program tabs are the same data as this master list, just
     * filtered/sorted, so importing them too would double-count every
     * examinee. Only sheet index 0 ("LIST OF EXAMINEES") gets read.
     */
    public function sheets(): array
    {
        return [0 => $this];
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
            $matchStatus = ProgramRanking::NOT_FOUND;
            $this->notFound++;
        } elseif ($this->namesAgree($examineeName, $applicant->family_name)) {
            $matchStatus = ProgramRanking::MATCHED;
            $this->matched++;
        } else {
            $matchStatus = ProgramRanking::NAME_MISMATCH;
            $this->nameMismatch++;
        }

        $ranking = ProgramRanking::create([
            'admission_year_id' => $this->admissionYearId,
            'code' => $rawCode ?: null,
            'examinee_name' => $examineeName,
            'program' => $this->repairMojibake(trim((string) ($row['program'] ?? ''))) ?: null,
            'address' => $this->repairMojibake(trim((string) ($row['address'] ?? ''))) ?: null,
            'raw_score' => is_numeric($row['t'] ?? null) ? (int) $row['t'] : null,
            'grade' => is_numeric($row['grade'] ?? null) ? (float) $row['grade'] : null,
            'percentile_rank' => $row['pr'] ?? null,
            'remarks' => trim((string) ($row['remarks'] ?? '')) ?: null,
            'admission_applicant_id' => $applicant?->id,
            'match_status' => $matchStatus,
        ]);

        $this->imported++;

        return $ranking;
    }

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
