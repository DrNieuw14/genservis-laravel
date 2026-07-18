<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementPlanItemLog extends Model
{
    protected $fillable = [
        'plan_id',
        'material_name',
        'action',
        'reason',
        'performed_by',
    ];

    public function plan()
    {
        return $this->belongsTo(ProcurementPlan::class, 'plan_id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
