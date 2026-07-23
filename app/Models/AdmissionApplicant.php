<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplicant extends Model
{
    protected $fillable = [
        'admission_year_id',
        'control_number',
        'given_name',
        'middle_name',
        'family_name',
        'suffix',
        'date_of_birth',
        'sex',
        'house_no',
        'street',
        'barangay',
        'municipality',
        'municipality_income_class',
        'province',
        'zip_code',
        'campus',
        'program',
        'email',
        'contact_number',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function year()
    {
        return $this->belongsTo(AdmissionYear::class, 'admission_year_id');
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'admission_applicant_id');
    }

    public function finalAdmission()
    {
        return $this->hasOne(FinalAdmission::class, 'admission_applicant_id');
    }

    public function fullName(): string
    {
        $parts = array_filter([
            $this->given_name,
            $this->middle_name,
            $this->family_name,
            $this->suffix,
        ]);

        return implode(' ', $parts);
    }

    public function fullAddress(): string
    {
        $parts = array_filter([
            $this->house_no,
            $this->street,
            $this->barangay,
            $this->municipality,
            $this->province,
            $this->zip_code,
        ]);

        return implode(', ', $parts);
    }
}
