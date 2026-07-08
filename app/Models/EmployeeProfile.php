<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $fillable = [

        'personnel_id',

        'birthdate',

        'gender',

        'civil_status',

        'nationality',

        'religion',

        'blood_type',

        'photo',

    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}