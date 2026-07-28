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

    /**
     * Actual utilization against one UACS+PPA line — Purchase Request item
     * costs (PRs that have reached the obligation point, Approved or
     * Completed, never Draft/Rejected) PLUS manually logged entries against
     * that same line (for spending that never goes through a PR at all —
     * Personal Services always, but also plenty of real MOOE lines like
     * utility bills or service contracts). This is "money actually spoken
     * for," distinct from the PPMP's merely-planned amount. Kept in sync
     * with the equivalent logic in ProgramReceiptExpenditureController —
     * that controller computes this per-row itself (to get the drill-down
     * detail list in the same pass), this method is the standalone version
     * for any other caller.
     */
    public function utilizedForUacs(string $ppa, string $uacsCode): float
    {
        $prTotal = (float) PurchaseRequestItem::query()
            ->whereHas(
                'purchaseRequest',
                fn ($q) => $q->whereIn('status', ['Approved', 'Completed'])
            )
            ->whereHas(
                'planItem',
                fn ($q) => $q->where('ppa', $ppa)
                    ->whereHas(
                        'material.classification',
                        fn ($q2) => $q2->where('uacs_code', $uacsCode)
                    )
            )
            ->sum('total_cost');

        $manualTotal = (float) $this->allocationLines
            ->where('ppa', $ppa)
            ->where('uacs_code', $uacsCode)
            ->reduce(fn ($carry, $line) => $carry + $line->utilizationEntries->sum('amount'), 0.0);

        return $prTotal + $manualTotal;
    }
}
