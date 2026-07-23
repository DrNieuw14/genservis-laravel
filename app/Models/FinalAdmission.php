<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalAdmission extends Model
{
    protected $fillable = [
        'admission_year_id',
        'program_code',
        'program_name',
        'admission_applicant_id',
        'added_by',
        'notes',
    ];

    public function year()
    {
        return $this->belongsTo(AdmissionYear::class, 'admission_year_id');
    }

    public function applicant()
    {
        return $this->belongsTo(AdmissionApplicant::class, 'admission_applicant_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
