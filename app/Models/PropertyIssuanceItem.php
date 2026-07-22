<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyIssuanceItem extends Model
{
    protected $fillable = [
        'property_issuance_id',
        'property_item_id',
        'property_name',
        'property_number',
        'unit',
        'quantity',
        'unit_cost',
        'date_acquired',
        'estimated_useful_life',
    ];

    protected $casts = [
        'date_acquired' => 'date',
    ];

    public function issuance()
    {
        return $this->belongsTo(PropertyIssuance::class, 'property_issuance_id');
    }

    public function propertyItem()
    {
        return $this->belongsTo(PropertyItem::class);
    }

    public function photos()
    {
        return $this->hasMany(PropertyIssuancePhoto::class, 'property_issuance_item_id');
    }

    public function totalCost(): float
    {
        return $this->quantity * (float) $this->unit_cost;
    }
}
