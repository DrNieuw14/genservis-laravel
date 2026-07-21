<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyConservationActivity extends Model
{
    protected $fillable = [
        'energy_conservation_report_id',
        'activity_date',
        'activity',
        'participants',
        'remarks',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function report()
    {
        return $this->belongsTo(EnergyConservationReport::class, 'energy_conservation_report_id');
    }
}
