<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $fillable = [
        'pr_number',
        'plan_id',
        'pr_date',
        'purpose',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'remarks',
    ];

    protected $casts = [
        'pr_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function plan()
    {
        return $this->belongsTo(ProcurementPlan::class, 'plan_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
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

    public function getTotalCostAttribute()
    {
        return $this->items()->sum('total_cost');
    }

    /**
     * Whether this PR counts as "utilized" against a PRE ceiling —
     * Approved is the obligation point (money is spoken for), not just
     * Completed (fully delivered/paid), per explicit user decision.
     */
    public function countsAsUtilized(): bool
    {
        return in_array($this->status, ['Approved', 'Completed']);
    }
}
