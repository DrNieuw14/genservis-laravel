<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'position_name',
        'position_code',
        'sort_order',
        'description',
        'is_active',
    ];

    public function employmentTypes()
    {
        return $this->belongsToMany(
            EmploymentType::class,
            'employment_type_position'
        );
    }
}