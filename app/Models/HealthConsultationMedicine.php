<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthConsultationMedicine extends Model
{
    protected $fillable = [
        'health_consultation_id',
        'clinic_medicine_id',
        'medicine_name',
        'unit',
        'quantity',
        'notes',
        'dispensed_by',
    ];

    public function consultation()
    {
        return $this->belongsTo(HealthConsultation::class, 'health_consultation_id');
    }

    public function medicine()
    {
        return $this->belongsTo(ClinicMedicine::class, 'clinic_medicine_id');
    }

    public function dispenser()
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }
}
