<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    const MATCHED = 'matched';
    const NAME_MISMATCH = 'name_mismatch';
    const NOT_FOUND = 'not_found';

    protected $fillable = [
        'exam_session_id',
        'admission_applicant_id',
        'code',
        'examinee_name',
        'raw_score',
        'grade',
        'percentile_rank',
        'remarks',
        'match_status',
    ];

    public function session()
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id');
    }

    public function applicant()
    {
        return $this->belongsTo(AdmissionApplicant::class, 'admission_applicant_id');
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
