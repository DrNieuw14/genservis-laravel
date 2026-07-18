<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_number',
        'year',
        'department_id',
        'allocated_budget',
        'approved_budget',
        'remaining_budget',
        'status',
        'prepared_by',
        'reviewed_by',
        'approved_by',
        'remarks',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'allocated_budget' => 'decimal:2',
        'approved_budget' => 'decimal:2',
        'remaining_budget' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function items()
    {
        return $this->hasMany(ProcurementPlanItem::class, 'plan_id');
    }

    public function itemLogs()
    {
        return $this->hasMany(ProcurementPlanItemLog::class, 'plan_id');
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function getTotalPlannedCostAttribute()
    {
        return $this->items()->sum('annual_cost');
    }

    public function getTotalPlannedQuantityAttribute()
    {
        return $this->items()->sum('annual_quantity');
    }

    public function getRemainingBudgetComputedAttribute()
    {
        return $this->allocated_budget - $this->total_planned_cost;
    }

    public function getBudgetUtilizationPercentageAttribute()
    {
        if ($this->allocated_budget <= 0) {
            return 0;
        }

        return round(
            ($this->total_planned_cost / $this->allocated_budget) * 100,
            2
        );
    }
}