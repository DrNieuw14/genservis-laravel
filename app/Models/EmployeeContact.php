<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeContact extends Model
{
    protected $fillable = [

        'personnel_id',

        'primary_email',

        'alternate_email',

        'mobile_number',

        'telephone_number',

        'emergency_contact_person',

        'emergency_contact_number',

        'emergency_relationship',

    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}