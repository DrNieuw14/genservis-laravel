<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterBill extends Model
{
    protected $fillable = [
        'water_meter_id',
        'report_month',
        'previous_reading',
        'present_reading',
        'water_bill',
        'esf',
        'amount_after_due_date',
        'due_date',
        'meter_reader_name',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function meter()
    {
        return $this->belongsTo(WaterMeter::class, 'water_meter_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Always computed — never manually entered, same "trust the reading
    // difference" convention as Energy Conservation Report's consumption.
    public function usage(): ?float
    {
        if ($this->present_reading === null || $this->previous_reading === null) {
            return null;
        }

        return $this->present_reading - $this->previous_reading;
    }

    // vs the SAME meter's previous month's bill — the "did we save water"
    // comparison, same semantics as Energy Conservation Report's
    // consumptionDifference()/consumptionPercentChange().
    public function usageDifference(): ?float
    {
        $previous = $this->previousBill();

        if (!$previous || $this->usage() === null || $previous->usage() === null) {
            return null;
        }

        return $this->usage() - $previous->usage();
    }

    public function usagePercentChange(): ?float
    {
        $previous = $this->previousBill();

        if (!$previous || !$previous->usage()) {
            return null;
        }

        return round(($this->usageDifference() / $previous->usage()) * 100, 2);
    }

    public function totalDue(): ?float
    {
        if ($this->water_bill === null && $this->esf === null) {
            return null;
        }

        return (float) $this->water_bill + (float) $this->esf;
    }

    public function monthLabel(): string
    {
        return \Illuminate\Support\Carbon::parse($this->report_month . '-01')->format('F Y');
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast();
    }

    // The same meter's most recent bill before this one — used to
    // auto-carry the previous reading forward the same way Energy
    // Conservation Report carries last month's current figures forward.
    public function previousBill(): ?self
    {
        return static::where('water_meter_id', $this->water_meter_id)
            ->where('id', '!=', $this->id)
            ->where('report_month', '<', $this->report_month)
            ->orderByDesc('report_month')
            ->first();
    }
}
