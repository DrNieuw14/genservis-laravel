<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequestItem extends Model
{
    protected $fillable = [
        'request_id',
        'material_id',
        'quantity'
    ];

    public function request()
    {
        return $this->belongsTo(MaterialRequest::class, 'request_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}