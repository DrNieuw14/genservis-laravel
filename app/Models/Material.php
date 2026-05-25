<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
    'name',
    'quantity',
    'category_id',
    'department_id',
    'unit_id',
    'created_by'
    ];

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

    // 📜 Material Logs
    public function logs()
    {
        return $this->hasMany(MaterialLog::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function requestItems()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }
}