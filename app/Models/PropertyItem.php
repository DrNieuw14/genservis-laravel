<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyItem extends Model
{
    const CONDITIONS = ['Good', 'Damaged', 'For Repair', 'Unserviceable'];

    protected $fillable = [
        'room_id',
        'property_name',
        'property_number',
        'description',
        'quantity',
        'unit_value',
        'date_acquired',
        'condition',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'date_acquired' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function totalValue(): float
    {
        return $this->quantity * (float) $this->unit_value;
    }

    public function conditionBadgeClass(): string
    {
        return match ($this->condition) {
            'Good' => 'bg-green-100 text-green-700',
            'Damaged' => 'bg-orange-100 text-orange-700',
            'For Repair' => 'bg-yellow-100 text-yellow-700',
            'Unserviceable' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
