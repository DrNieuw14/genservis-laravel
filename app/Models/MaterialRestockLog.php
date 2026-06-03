<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRestockLog extends Model
{
    protected $fillable = [

    'material_id',
    'batch_no',
    'previous_stock',
    'added_stock',
    'quantity_remaining',
    'new_stock',
    'supplier',
    'invoice_no',

    'has_expiration',
    'expiration_date',

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