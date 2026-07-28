<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreUtilizationEntry extends Model
{
    protected $fillable = [
        'pre_allocation_line_id',
        'amount',
        'entry_date',
        'note',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date',
    ];

    public function allocationLine()
    {
        return $this->belongsTo(PreAllocationLine::class, 'pre_allocation_line_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
