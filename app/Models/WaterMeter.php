<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterMeter extends Model
{
    protected $fillable = [
        'label',
        'account_no',
        'service_no',
        'meter_no',
        'meter_brand',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bills()
    {
        return $this->hasMany(WaterBill::class)->orderByDesc('report_month');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function latestBill(): ?WaterBill
    {
        return $this->bills()->first();
    }
}
