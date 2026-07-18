<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
    'name',
    'image',
    'quantity',
    'threshold',
    'category_id',
    'department_id',
    'unit_id',
    'classification_id',
    'created_by'
    ];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : null;
    }

    // 🔗 Relationships

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function classification()
    {
        return $this->belongsTo(
            ProcurementClassification::class,
            'classification_id'
        );
    }

    // 📜 Material Logs
    public function logs()
    {
        return $this->hasMany(MaterialLog::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 📜 Material Requests

    public function requestItems()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }

    public function walkinItems()
    {
        return $this->hasMany(WalkinRequestItem::class);
    }

   
}