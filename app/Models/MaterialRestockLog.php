<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRestockLog extends Model
{
    protected $fillable = [

        'material_id',
        'previous_stock',
        'added_stock',
        'new_stock',
        'supplier',
        'remarks',
        'restocked_by',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'restocked_by');
    }
}