<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    protected $fillable = [
        'purchase_request_id',
        'procurement_plan_item_id',
        'quantity_requested',
        'unit_cost',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function planItem()
    {
        return $this->belongsTo(ProcurementPlanItem::class, 'procurement_plan_item_id');
    }

    public function calculateTotal(): void
    {
        $this->total_cost = $this->quantity_requested * $this->unit_cost;
    }
}
