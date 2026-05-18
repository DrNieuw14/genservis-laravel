<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialLog extends Model
{
    protected $fillable = [
        'material_id',
        'user_id',
        'action',
        'quantity',
        'remarks',
    ];

    // 📦 Related Material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // 👤 Related User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}