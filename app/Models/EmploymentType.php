<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentType extends Model
{
    protected $table = 'employment_types';

    protected $fillable = [
        'name',
        'employee_prefix',
        'is_active',
    ];

    public function positions()
    {
        return $this->belongsToMany(
            Position::class,
            'employment_type_position'
        );
    }
}