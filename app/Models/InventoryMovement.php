<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
   
    protected $fillable = [

        'material_id',
        'performed_by',
        'movement_type',
        'quantity',
        'previous_stock',
        'new_stock',
        'remarks',

    ];


    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}