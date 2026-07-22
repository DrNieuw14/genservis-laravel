<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramRanking extends Model
{
    const MATCHED = 'matched';
    const NAME_MISMATCH = 'name_mismatch';
    const NOT_FOUND = 'not_found';

    /**
     * Full program name (as it appears on the "Master List" sheet) => the
     * short code the same workbook already uses as its own per-program tab
     * name (BSE-MATH, BSBA-HRM, etc.) — taken directly from the real file,
     * not invented, so it matches what Admission Testing staff already
     * call each program.
     */
    const PROGRAM_SHORT_CODES = [
        'BACHELOR OF SECONDARY EDUCATION-MATHEMATICS' => 'BSE-MATH',
        'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION-HUMAN RESOURCE MANAGEMENT' => 'BSBA-HRM',
        'BACHELOR OF SECONDARY EDUCATION-ENGLISH' => 'BSE-ENGLISH',
        'BACHELOR OF SECONDARY EDUCATION-SCIENCE' => 'BSE-SCIENCE',
        'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT' => 'BSHM',
        'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION-MARKETING MANAGEMENT' => 'BSBA-MM',
        'BACHELOR OF SCIENCE IN COMPUTER ENGINEERING' => 'BSCpE',
        'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY-ELECTRONICS TECHNOLOGY' => 'BSIndT',
        'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY' => 'BSIT',
        'BACHELOR OF SCIENCE IN COMPUTER SCIENCE' => 'BSCS',
    ];

    protected $fillable = [
        'admission_year_id',
        'code',
        'examinee_name',
        'program',
        'address',
        'raw_score',
        'grade',
        'percentile_rank',
        'remarks',
        'admission_applicant_id',
        'match_status',
    ];

    public function year()
    {
        return $this->belongsTo(AdmissionYear::class, 'admission_year_id');
    }

    public function applicant()
    {
        return $this->belongsTo(AdmissionApplicant::class, 'admission_applicant_id');
    }

    public function shortProgramCode(): string
    {
        $program = trim((string) $this->program);

        return self::PROGRAM_SHORT_CODES[$program] ?? ($program ?: 'Unspecified');
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
}
