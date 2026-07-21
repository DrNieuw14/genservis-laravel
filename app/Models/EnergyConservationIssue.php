<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyConservationIssue extends Model
{
    protected $fillable = [
        'energy_conservation_report_id',
        'issue_concern',
        'action_taken',
        'recommendation',
    ];

    public function report()
    {
        return $this->belongsTo(EnergyConservationReport::class, 'energy_conservation_report_id');
    }
}
