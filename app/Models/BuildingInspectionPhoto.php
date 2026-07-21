<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingInspectionPhoto extends Model
{
    protected $fillable = [
        'building_inspection_item_id',
        'path',
        'uploaded_by',
    ];

    public function item()
    {
        return $this->belongsTo(BuildingInspectionItem::class, 'building_inspection_item_id');
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
