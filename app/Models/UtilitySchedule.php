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
        'credited_hours',
    ];

    // Standard workday used to derive a day's default credited hours when
    // no manual override is set.
    const STANDARD_WORKDAY_HOURS = 8.0;

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
     * - on_leave: personnel has APPROVED leave covering this date, never
     *   checked in — checked before no_show/scheduled so an approved leave
     *   day never misreads as a missed shift
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

            if (LeaveRequest::hasApprovedLeaveOn($this->personnel_id, $this->schedule_date)) {
                return 'on_leave';
            }

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
            'on_leave' => ['label' => '🌴 On Leave', 'class' => 'bg-purple-100 text-purple-700'],
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

    /**
     * Shared by the self-service My Schedule page AND the public attendance
     * kiosk scanner — one source of truth for what "checking in" means,
     * regardless of how the request got here. Returns ['success' => bool,
     * 'message' => string] rather than throwing, since both callers just
     * need to relay the outcome to the user.
     */
    public function performCheckIn(): array
    {
        if ($this->schedule_date->isFuture()) {
            return [
                'success' => false,
                'message' => 'You can only check in once your scheduled date arrives (' . $this->schedule_date->format('M d, Y') . ').',
            ];
        }

        if (LeaveRequest::hasApprovedLeaveOn($this->personnel_id, $this->schedule_date)) {
            return ['success' => false, 'message' => 'You have approved leave on this date — check-in is not applicable.'];
        }

        if ($this->time_in) {
            return ['success' => false, 'message' => 'Already checked in for this schedule.'];
        }

        $this->update(['time_in' => now()]);

        return ['success' => true, 'message' => 'Checked in at ' . now()->format('g:i A') . '.'];
    }

    public function performCheckOut(?string $overtimeReason = null): array
    {
        if (!$this->time_in) {
            return ['success' => false, 'message' => 'Check in first before checking out.'];
        }

        if ($this->time_out) {
            return ['success' => false, 'message' => 'Already checked out for this schedule.'];
        }

        $this->time_out = now();
        $this->overtime_minutes = $this->computeOvertimeMinutes();
        $this->overtime_reason = $this->overtime_minutes > 0 ? $overtimeReason : null;
        $this->save();

        $message = 'Checked out at ' . now()->format('g:i A') . '.';

        if ($this->overtime_minutes > 0) {
            $message .= ' (' . $this->overtime_minutes . ' min overtime recorded)';
        }

        return ['success' => true, 'message' => $message];
    }

    // Reverts a check-out that was just performed by mistake (e.g. an
    // accidental second QR scan on the attendance kiosk). Not time-limited
    // server-side — the kiosk UI only offers this for a few seconds right
    // after the check-out happens, which is the actual guardrail.
    public function undoCheckOut(): array
    {
        if (!$this->time_out) {
            return ['success' => false, 'message' => 'This entry is not checked out.'];
        }

        $this->time_out = null;
        $this->overtime_minutes = null;
        $this->overtime_reason = null;
        $this->save();

        return ['success' => true, 'message' => 'Check-out undone — you are checked in again.'];
    }
}
