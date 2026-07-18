<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UtilitySchedule extends Model
{
    // Minutes of leeway after shift_start/shift_end before a check-in
    // counts as "late" or a check-out counts as overtime — avoids flagging
    // ordinary tap-timing imprecision as a real discrepancy.
    const GRACE_MINUTES = 15;

    protected $fillable = [
        'personnel_id',
        'schedule_date',
        'shift_start',
        'shift_end',
        'task',
        'location',
        'notes',
        'job_request_id',
        'created_by',
        'time_in',
        'time_out',
        'overtime_minutes',
        'overtime_reason',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Trust-based attendance, computed from time_in/time_out rather than
     * stored — no one manually classifies a day as "late" or "no show".
     *
     * - scheduled: day hasn't happened yet, no check-in
     * - no_show: day already passed, never checked in
     * - checked_in: checked in today (or a future-dated entry someone
     *   checked into early), hasn't checked out yet
     * - incomplete: checked in, day already passed, never checked out
     * - late: checked in more than GRACE_MINUTES after shift_start
     * - present: checked in (on time) and checked out
     */
    public function attendanceStatus(): string
    {
        $isPastDay = $this->schedule_date->lt(Carbon::today());

        if (!$this->time_in) {
            return $isPastDay ? 'no_show' : 'scheduled';
        }

        $isLate = false;

        if ($this->shift_start) {
            $graceDeadline = Carbon::parse($this->schedule_date->toDateString() . ' ' . $this->shift_start)
                ->addMinutes(self::GRACE_MINUTES);

            $isLate = $this->time_in->gt($graceDeadline);
        }

        if (!$this->time_out) {
            if ($isPastDay) {
                return 'incomplete';
            }

            return $isLate ? 'late' : 'checked_in';
        }

        return $isLate ? 'late' : 'present';
    }

    public static function attendanceStatusLabel(string $status): array
    {
        return match ($status) {
            'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-gray-100 text-gray-600'],
            'no_show' => ['label' => '❌ No Show', 'class' => 'bg-red-100 text-red-700'],
            'checked_in' => ['label' => '🟢 Checked In', 'class' => 'bg-blue-100 text-blue-700'],
            'incomplete' => ['label' => '⚠️ Incomplete', 'class' => 'bg-yellow-100 text-yellow-700'],
            'late' => ['label' => '⏰ Late', 'class' => 'bg-orange-100 text-orange-700'],
            'present' => ['label' => '✅ Present', 'class' => 'bg-green-100 text-green-700'],
            default => ['label' => $status, 'class' => 'bg-gray-100 text-gray-600'],
        };
    }

    /**
     * Minutes worked past shift_end (beyond the grace period). Null when
     * there's nothing to measure against — no time_out yet, or the entry
     * has no shift_end to compare to (shift_end is optional).
     */
    public function computeOvertimeMinutes(): ?int
    {
        if (!$this->time_out || !$this->shift_end) {
            return null;
        }

        $shiftEnd = Carbon::parse($this->schedule_date->toDateString() . ' ' . $this->shift_end);
        $graceDeadline = $shiftEnd->copy()->addMinutes(self::GRACE_MINUTES);

        if ($this->time_out->lte($graceDeadline)) {
            return 0;
        }

        return $graceDeadline->diffInMinutes($this->time_out);
    }
}
