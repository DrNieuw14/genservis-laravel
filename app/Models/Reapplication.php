<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reapplication extends Model
{
    const MATCHED = 'matched';
    const NAME_MISMATCH = 'name_mismatch';
    const NOT_FOUND = 'not_found';

    /**
     * First/Second Choice text (as this Google Form actually writes each
     * program) => the short code used everywhere else in this app
     * (`ProgramRanking::PROGRAM_SHORT_CODES`). Not a simple case-fold of
     * that constant — this form phrases things differently ("Business
     * Administration MAJOR IN Marketing Management" vs the roster's
     * "BUSINESS ADMINISTRATION-MARKETING MANAGEMENT", and "MATH" vs
     * "MATHEMATICS") — confirmed against the real distinct First Choice
     * values on the actual file before hardcoding this.
     */
    const CHOICE_TO_PROGRAM_CODE = [
        'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY' => 'BSIT',
        'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT' => 'BSHM',
        'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN MARKETING MANAGEMENT' => 'BSBA-MM',
        'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN HUMAN RESOURCE MANAGEMENT' => 'BSBA-HRM',
        'BACHELOR OF SECONDARY EDUCATION MAJOR IN ENGLISH' => 'BSE-ENGLISH',
        'BACHELOR OF SECONDARY EDUCATION MAJOR IN SCIENCE' => 'BSE-SCIENCE',
        'BACHELOR OF SECONDARY EDUCATION MAJOR IN MATH' => 'BSE-MATH',
        'BACHELOR OF SCIENCE IN COMPUTER ENGINEERING' => 'BSCpE',
        'BACHELOR OF SCIENCE IN COMPUTER SCIENCE' => 'BSCS',
        'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY MAJOR IN ELECTRONICS TECHNOLOGY' => 'BSIndT',
    ];

    protected $fillable = [
        'admission_year_id',
        'email',
        'surname',
        'first_name',
        'middle_name',
        'suffix',
        'address',
        'campus',
        'program_applied_for',
        'control_number',
        'track',
        'strand',
        'first_choice',
        'second_choice',
        'admission_applicant_id',
        'match_status',
        'is_duplicate',
    ];

    protected $casts = [
        'is_duplicate' => 'boolean',
    ];

    public function year()
    {
        return $this->belongsTo(AdmissionYear::class, 'admission_year_id');
    }

    public function applicant()
    {
        return $this->belongsTo(AdmissionApplicant::class, 'admission_applicant_id');
    }

    public function fullName(): string
    {
        return trim(implode(' ', array_filter([$this->first_name, $this->middle_name, $this->surname, $this->suffix])));
    }

    public function matchStatusLabel(): string
    {
        return match ($this->match_status) {
            self::MATCHED => '✅ Matched',
            self::NAME_MISMATCH => '⚠ Name Mismatch',
            self::NOT_FOUND => '❌ Not in Roster',
            default => $this->match_status,
        };
    }

    public static function programCodeForChoice(?string $choiceText): ?string
    {
        if (!$choiceText) {
            return null;
        }

        return self::CHOICE_TO_PROGRAM_CODE[strtoupper(trim($choiceText))] ?? null;
    }

    // The exact text this form uses for a given program's short code —
    // the reverse of CHOICE_TO_PROGRAM_CODE, used to query first_choice/
    // second_choice (which are stored in the form's own Title Case, not
    // uppercase) when filtering by program.
    public static function choiceTextForProgramCode(string $programCode): ?string
    {
        $flipped = array_flip(self::CHOICE_TO_PROGRAM_CODE);

        return $flipped[$programCode] ?? null;
    }
}
