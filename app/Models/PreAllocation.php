<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreAllocation extends Model
{
    protected $fillable = [
        'pre_id',
        'fund_source',
        'ppa',
        'personal_services',
        'mooe',
        'capital_outlay',
        'infrastructure',
    ];

    protected $casts = [
        'personal_services' => 'decimal:2',
        'mooe' => 'decimal:2',
        'capital_outlay' => 'decimal:2',
        'infrastructure' => 'decimal:2',
    ];

    public function programReceiptExpenditure()
    {
        return $this->belongsTo(ProgramReceiptExpenditure::class, 'pre_id');
    }

    public function getTotalAttribute()
    {
        return $this->personal_services
            + $this->mooe
            + $this->capital_outlay
            + $this->infrastructure;
    }
}
