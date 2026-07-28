<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyConservationReport extends Model
{
    // Fixed checklist from the template — Energy Conservation Measures
    // Implemented. Keys are what's stored in measures_implemented; labels
    // are what prints/displays.
    const MEASURES = [
        'lights_off' => 'Lights-Off Policy',
        'ac_management' => 'Air Conditioning Management',
        'natural_lighting' => 'Use of Natural Lighting',
        'shutdown_after_hours' => 'Shutdown of Computers and Equipment After Office Hours',
        'unplug_unused' => 'Unplugging of Unused Appliances',
        'preventive_maintenance' => 'Preventive Maintenance of Electrical Equipment',
        'info_campaign' => 'Energy Conservation Information Campaign',
    ];

    protected $fillable = [
        'report_month',
        'campus',
        'previous_month_bill',
        'current_month_bill',
        'previous_month_consumption',
        'current_month_consumption',
        'remarks_analysis',
        'measures_implemented',
        'other_measures',
        'summary_of_accomplishments',
        'status',
        'reviewed_by_name',
        'submitted_at',
        'created_by',
    ];

    protected $casts = [
        'measures_implemented' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function activities()
    {
        return $this->hasMany(EnergyConservationActivity::class)->orderBy('activity_date');
    }

    public function issues()
    {
        return $this->hasMany(EnergyConservationIssue::class);
    }

    public function attachments()
    {
        return $this->hasMany(EnergyConservationAttachment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function billDifference(): ?float
    {
        if ($this->previous_month_bill === null || $this->current_month_bill === null) {
            return null;
        }

        return $this->current_month_bill - $this->previous_month_bill;
    }

    public function billPercentChange(): ?float
    {
        if (!$this->previous_month_bill) {
            return null;
        }

        return round(($this->billDifference() / $this->previous_month_bill) * 100, 2);
    }

    public function consumptionDifference(): ?float
    {
        if ($this->previous_month_consumption === null || $this->current_month_consumption === null) {
            return null;
        }

        return $this->current_month_consumption - $this->previous_month_consumption;
    }

    public function consumptionPercentChange(): ?float
    {
        if (!$this->previous_month_consumption) {
            return null;
        }

        return round(($this->consumptionDifference() / $this->previous_month_consumption) * 100, 2);
    }

    // The electric bill's actual reading cycle doesn't align with the
    // calendar month — it runs the 22nd of the prior month through the
    // 21st of report_month (e.g. report_month '2026-07' → "22 Jun – 21 Jul
    // 2026"), matching the utility's real billing period. report_month
    // itself still just identifies which cycle this is, unchanged.
    public function periodStart(): \Illuminate\Support\Carbon
    {
        return $this->periodEnd()->subMonth()->addDay();
    }

    public function periodEnd(): \Illuminate\Support\Carbon
    {
        return \Illuminate\Support\Carbon::parse($this->report_month . '-21');
    }

    public function monthLabel(): string
    {
        $start = $this->periodStart();
        $end = $this->periodEnd();

        $startFormat = $start->year === $end->year ? 'd M' : 'd M Y';

        return $start->format($startFormat) . ' – ' . $end->format('d M Y');
    }

    // The report for the calendar month right before this one, if it
    // exists — used to carry "current month" figures forward as next
    // month's "previous month" figures instead of re-typing them.
    public function previousMonthReport(): ?self
    {
        $prevMonth = \Illuminate\Support\Carbon::parse($this->report_month . '-01')->subMonth()->format('Y-m');

        return static::where('report_month', $prevMonth)->first();
    }
}
