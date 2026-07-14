<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    protected $table = 'employee_educations';

    protected $fillable = [
        'personnel_id',
        'education_level',
        'school_name',
        'degree_course',
        'highest_level',
        'year_graduated',
        'from_year',
        'to_year',
        'honors',
        'units_earned',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}