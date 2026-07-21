<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    const ROOM_TYPES = [
        'Lecture Room',
        'Laboratory Room',
        'Faculty Room',
        'Office',
        'Library',
        'Conference Room',
        'Storage Room',
        'Other',
    ];

    protected $fillable = [
        'room_name',
        'room_type',
        'building',
        'department_id',
        'description',
        'is_active',
        'created_by',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function propertyItems()
    {
        return $this->hasMany(PropertyItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function totalValue(): float
    {
        return $this->propertyItems->sum(fn ($item) => $item->quantity * (float) $item->unit_value);
    }
}
