<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementPlanItem extends Model
{
    use HasFactory;

    protected $fillable = [

        'plan_id',

        'material_id',

        'material_name',

        'description',

        'unit_id',

        'estimated_unit_cost',

        'q1',
        'q2',
        'q3',
        'q4',

        'annual_quantity',

        'annual_cost',

        'priority',

        'procurement_method',

        'source_of_fund',

        'remarks',
    ];

    protected $casts = [
        'estimated_unit_cost' => 'decimal:2',
        'annual_cost' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function plan()
    {
        return $this->belongsTo(ProcurementPlan::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function calculateTotals()
    {
        $this->annual_quantity =
            $this->q1 +
            $this->q2 +
            $this->q3 +
            $this->q4;

        $this->annual_cost =
            $this->annual_quantity *
            $this->estimated_unit_cost;
    }
}