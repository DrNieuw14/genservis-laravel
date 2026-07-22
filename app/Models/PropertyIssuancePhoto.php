<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyIssuancePhoto extends Model
{
    protected $fillable = [
        'property_issuance_id',
        'property_issuance_item_id',
        'path',
        'uploaded_by',
    ];

    public function issuance()
    {
        return $this->belongsTo(PropertyIssuance::class, 'property_issuance_id');
    }

    public function item()
    {
        return $this->belongsTo(PropertyIssuanceItem::class, 'property_issuance_item_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
