<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    protected $fillable = [
    'user_id',
    'status',
    'purpose',
    'remarks'
    ];

    // 🔗 Relationships

    public function items()
    {
        return $this->hasMany(MaterialRequestItem::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}