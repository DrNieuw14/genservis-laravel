<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramReceiptExpenditure extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'total_projected_income',
        'status',
        'prepared_by',
        'remarks',
    ];

    protected $casts = [
        'total_projected_income' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function allocations()
    {
        return $this->hasMany(PreAllocation::class, 'pre_id');
    }

    public function allocationLines()
    {
        return $this->hasMany(PreAllocationLine::class, 'pre_id');
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function getTotalExpensesAttribute()
    {
        return $this->allocations->sum(
            fn ($allocation) => $allocation->personal_services
                + $allocation->mooe
                + $allocation->capital_outlay
                + $allocation->infrastructure
        );
    }

    /**
     * Procurable ceiling (MOOE + Capital Outlay) for a given PPA, summed across
     * both funding sources — this is what a PPMP's tagged items reconcile against.
     * Personal Services/Infrastructure are excluded since PPMP items never draw from them.
     */
    public function procurableCeilingFor(string $ppa): float
    {
        return (float) $this->allocations
            ->where('ppa', $ppa)
            ->sum(fn ($allocation) => $allocation->mooe + $allocation->capital_outlay);
    }

    /**
     * Finer-grained ceiling: this PPA's allowance for one specific UACS code
     * (MOOE + Capital Outlay lines only — Personal Services is never procured).
     */
    public function procurableCeilingForUacs(string $ppa, string $uacsCode): float
    {
        return (float) $this->allocationLines
            ->where('ppa', $ppa)
            ->where('uacs_code', $uacsCode)
            ->where('allotment_class', '!=', 'Personal Services')
            ->sum('amount');
    }
}
