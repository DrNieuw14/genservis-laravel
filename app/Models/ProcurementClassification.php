<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementClassification extends Model
{
    protected $fillable = [
        'part',
        'main_category',
        'sub_category_a',
        'sub_category_b',
        'sub_category_c',
        'code',
        'uacs_code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'classification_id');
    }
}