<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramQuota extends Model
{
    protected $fillable = [
        'admission_year_id',
        'program_code',
        'program_name',
        'sections',
        'students_per_section',
        'updated_by',
    ];

    public function year()
    {
        return $this->belongsTo(AdmissionYear::class, 'admission_year_id');
    }

    public function quota(): int
    {
        return $this->sections * $this->students_per_section;
    }

    public function isSet(): bool
    {
        return $this->quota() > 0;
    }
}
