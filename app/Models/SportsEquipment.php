<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsEquipment extends Model
{
    // Eloquent's pluralizer treats "Equipment" as uncountable and would
    // otherwise guess "sports_equipment" (no trailing s) as the table name.
    protected $table = 'sports_equipments';

    protected $fillable = [
        'name',
        'unit',
        'total_quantity',
        'image',
        'notes',
        'created_by',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : null;
    }

    public function borrows()
    {
        return $this->hasMany(SportsEquipmentBorrow::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Currently out on loan — only 'approved' counts, 'pending' hasn't
    // been handed over yet and 'returned'/'rejected' no longer hold stock.
    public function borrowedQuantity(): int
    {
        return (int) $this->borrows()
            ->where('status', 'approved')
            ->sum('quantity');
    }

    public function availableQuantity(): int
    {
        return $this->total_quantity - $this->borrowedQuantity();
    }
}
