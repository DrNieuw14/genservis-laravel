<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'program_id',
        'year_level',
        'section_letter',
        'school_year',
        'semester',
        'number_of_students',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    // e.g. "BSIT 1A" — matches the real schedule sheets' section labels.
    public function getLabelAttribute(): string
    {
        return trim($this->program->code . ' ' . $this->year_level . $this->section_letter);
    }
}
