<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreAllocationLine extends Model
{
    protected $fillable = [
        'pre_id',
        'fund_source',
        'ppa',
        'allotment_class',
        'uacs_code',
        'description',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function programReceiptExpenditure()
    {
        return $this->belongsTo(ProgramReceiptExpenditure::class, 'pre_id');
    }

    public function utilizationEntries()
    {
        return $this->hasMany(PreUtilizationEntry::class);
    }
}
