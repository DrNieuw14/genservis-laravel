<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicMedicine extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'unit',
        'current_stock',
        'quantity_received',
        'expiration_date',
        'notes',
    ];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function dispensingRecords()
    {
        return $this->hasMany(HealthConsultationMedicine::class);
    }

    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }

    public function isExpiringSoon(): bool
    {
        return $this->expiration_date && $this->expiration_date->isBefore(now()->addDays(90));
    }

    public function isExpired(): bool
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }
}
