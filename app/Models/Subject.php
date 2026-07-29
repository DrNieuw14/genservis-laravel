<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'code',
        'title',
        'lecture_units',
        'lab_units',
    ];

    protected $casts = [
        'lecture_units' => 'decimal:1',
        'lab_units' => 'decimal:1',
    ];

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function getTotalUnitsAttribute(): float
    {
        return (float) $this->lecture_units + (float) $this->lab_units;
    }
}
