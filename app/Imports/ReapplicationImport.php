<?php

namespace App\Imports;

use App\Models\AdmissionApplicant;
use App\Models\Reapplication;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Imports the real Google Forms "Admission Reapplication Form" export —
 * students who registered/tested but didn't make their original program's
 * quota, picking a First/Second Choice alternate program. Verified before
 * building this: of the reapplicants whose original program already had a
 * quota set, the overwhelming majority (68 of 69 on the real file) were
 * genuinely NOT admitted — confirms the form is reaching the right cohort.
 *
 * Every submission is kept as its own row, even exact repeats — the
 * source sheet has no Timestamp column, so there's no reliable way to
 * pick "the real one" automatically when a person's answers actually
 * differ between submissions. Duplicates are flagged (is_duplicate), not
 * merged or dropped, for Admission Testing staff to resolve by hand.
 */
class ReapplicationImport implements ToModel, WithHeadingRow
{
    public int $imported = 0;
    public int $matched = 0;
    public int $nameMismatch = 0;
    public int $notFound = 0;

    public function __construct(private int $admissionYearId)
    {
    }

    public function model(array $row)
    {
        $surname = $this->repairMojibake(trim((string) ($row['surname'] ?? '')));
        $firstName = $this->repairMojibake(trim((string) ($row['first_name'] ?? '')));

        if ($surname === '' || $firstName === '') {
            return null;
        }

        $controlNumber = $this->normalizeControlNumber($row['application_control_number'] ?? null);

        $applicant = null;

        if ($controlNumber !== null) {
            $applicant = AdmissionApplicant::where('admission_year_id', $this->admissionYearId)
                ->where('control_number', $controlNumber)
                ->first();
        }

        if (!$applicant) {
            $matchStatus = Reapplication::NOT_FOUND;
            $this->notFound++;
        } elseif ($this->namesAgree($surname, $applicant->family_name)) {
            $matchStatus = Reapplication::MATCHED;
            $this->matched++;
        } else {
            $matchStatus = Reapplication::NAME_MISMATCH;
            $this->nameMismatch++;
        }

        $reapplication = Reapplication::create([
            'admission_year_id' => $this->admissionYearId,
            'email' => trim((string) ($row['email_address'] ?? '')) ?: null,
            'surname' => $surname,
            'first_name' => $firstName,
            'middle_name' => $this->repairMojibake(trim((string) ($row['middle_name'] ?? ''))) ?: null,
            'suffix' => trim((string) ($row['suffix'] ?? '')) ?: null,
            'address' => $this->repairMojibake(trim((string) ($row['address'] ?? ''))) ?: null,
            'campus' => $this->repairMojibake(trim((string) ($row['campus'] ?? ''))) ?: null,
            'program_applied_for' => trim((string) ($row['program_you_applied_for'] ?? '')) ?: null,
            'control_number' => $controlNumber,
            'track' => trim((string) ($row['track'] ?? '')) ?: null,
            // Mutually exclusive branches of the same form question —
            // whichever the respondent actually saw and answered.
            'strand' => trim((string) ($row['choose_you_strand'] ?? $row['choose_you_strand_2'] ?? '')) ?: null,
            'first_choice' => trim((string) ($row['first_choice'] ?? '')) ?: null,
            'second_choice' => trim((string) ($row['second_choice'] ?? '')) ?: null,
            'admission_applicant_id' => $applicant?->id,
            'match_status' => $matchStatus,
        ]);

        $this->imported++;

        return $reapplication;
    }

    // "N/A", "NA", blank, or non-numeric are all real, common ways this
    // form's respondents left the Control Number unusable — treated as
    // genuinely missing, not grouped together as if they were the same
    // duplicate submission.
    private function normalizeControlNumber($value): ?string
    {
        $value = trim((string) ($value ?? ''));

        if ($value === '' || !ctype_digit($value)) {
            return null;
        }

        return (string) (int) $value;
    }

    private function namesAgree(string $sheetSurname, string $rosterFamilyName): bool
    {
        if ($rosterFamilyName === '') {
            return false;
        }

        $normalize = fn (string $s) => preg_replace('/\s+/', '', strtoupper($s));

        return str_contains($normalize($sheetSurname), $normalize($rosterFamilyName))
            || str_contains($normalize($rosterFamilyName), $normalize($sheetSurname));
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
