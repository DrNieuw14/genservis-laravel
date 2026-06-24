<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkinRequestItem extends Model
{
    protected $fillable = [
        'walkin_request_id',
        'material_id',
        'quantity',
        'unit',
        'remarks',
        'stock_before',
        'stock_after'
    ];

    public function request()
    {
        return $this->belongsTo(
            WalkinRequest::class,
            'walkin_request_id'
        );
    }

    public function material()
    {
        return $this->belongsTo(
            Material::class
        );
    }
}