<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\DtrSubmission;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\Personnel;
use App\Models\User;
use App\Models\UtilitySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UtilityDtrController extends Controller
{
    // 📅 Staff + month picker — the DTR itself is generated from existing
    // Attendance/Overtime/Leave data, there's nothing new to create here.
    public function index()
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        $staff = Personnel::utilityStaff()->orderBy('fullname')->get();

        return view('utility_dtr.index', compact('staff'));
    }

    public function show(Request $request, $personnelId)
    {
        $this->authorizeDtrManager();

        $personnel = Personnel::utilityStaff()->findOrFail($personnelId);

        return view('utility_dtr.show', $this->dtrDataForPersonnel($request, $personnel));
    }

    public function print(Request $request, $personnelId)
    {
        $this->authorizeDtrManager();

        $personnel = Personnel::utilityStaff()->findOrFail($personnelId);

        return view('utility_dtr.print', $this->dtrDataForPersonnel($request, $personnel));
    }

    // GSO (manage-utility-schedule) or HR (approve-dtr) correcting a day's
    // credited hours — e.g. a forgot-to-clock-out entry that raw-computes
    // to an implausible 12+ hrs, or hand-adjusting a late/undertime day.
    // An empty value clears the override and reverts to the auto-computed
    // default rather than storing a blank/zero.
    public function updateCreditedHours(Request $request, $personnelId)
    {
        $this->authorizeDtrManager();

        $personnel = Personnel::utilityStaff()->findOrFail($personnelId);

        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'credited_hours' => 'nullable|numeric|min:0|max:24',
        ]);

        $entry = UtilitySchedule::where('personnel_id', $personnel->id)
            ->whereDate('schedule_date', $validated['schedule_date'])
            ->first();

        if (!$entry) {
            return back()->with('error', 'No attendance entry exists for that date to edit.');
        }

        $entry->update(['credited_hours' => $validated['credited_hours'] ?? null]);

        return back()->with('success', 'Credited hours updated for ' . \Illuminate\Support\Carbon::parse($validated['schedule_date'])->format('M d, Y') . '.');
    }

    private function authorizeDtrManager(): void
    {
        $user = Auth::user();

        if (!$user->hasPermission('manage-utility-schedule') && !$user->hasPermission('approve-dtr')) {
            abort(403);
        }
    }

    /**
     * Self-service — a utility employee reviewing (and verifying) their own
     * DTR for a chosen date range, the first stage of the
     * Employee -> Mark -> HR pipeline.
     */
    public function myDtr(Request $request)
    {
        $personnel = $this->ownUtilityPersonnelOrAbort();

        return view('utility_dtr.my', $this->dtrDataForPersonnel($request, $personnel));
    }

    public function verify(Request $request)
    {
        $personnel = $this->ownUtilityPersonnelOrAbort();

        [$periodStart, $periodEnd] = $this->resolvePeriodFromVerifyRequest($request);

        $submission = DtrSubmission::firstOrNew([
            'personnel_id' => $personnel->id,
            'period_start' => $periodStart->toDateString(),
            'period_end' => $periodEnd->toDateString(),
        ]);

        if ($submission->exists && $submission->status !== 'draft') {
            return back()->with('error', 'This DTR period has already been verified.');
        }

        $submission->fill([
            'month' => $periodStart->format('Y-m'),
            'status' => 'employee_verified',
            'employee_verified_at' => now(),
            'rejected_at' => null,
            'rejected_by' => null,
            'rejected_stage' => null,
            'rejection_reason' => null,
        ])->save();

        $this->notify(
            User::withPermission('manage-utility-schedule')->get(),
            'dtr',
            'DTR Verified',
            $personnel->fullname . ' verified their DTR for ' . $submission->periodLabel() . '.',
            route('utility-dtr.show', ['personnelId' => $personnel->id, 'start_date' => $periodStart->toDateString(), 'end_date' => $periodEnd->toDateString()], false)
        );

        return back()->with('success', 'DTR verified and sent to the General Services Officer for checking.');
    }

    // Mark's "checking" stage — only meaningful once the employee has
    // verified. Forwards to HR for final approval.
    public function check(Request $request, $personnelId)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        $personnel = Personnel::utilityStaff()->findOrFail($personnelId);
        $submission = $this->findSubmissionOrFail($request, $personnel);

        if (!$submission || $submission->status !== 'employee_verified') {
            return back()->with('error', 'This DTR has not been verified by the employee yet.');
        }

        $submission->update([
            'status' => 'mark_checked',
            'mark_checked_at' => now(),
            'mark_checked_by' => Auth::id(),
        ]);

        $this->notify(
            User::withPermission('approve-dtr')->get(),
            'dtr',
            'DTR Ready for Approval',
            $personnel->fullname . "'s DTR for " . $submission->periodLabel() . ' has been checked and needs HR approval.',
            route('utility-dtr.hr.pending', [], false)
        );

        $this->notifyPersonnelOwner($personnel, 'dtr', 'DTR Checked', 'Your DTR for ' . $submission->periodLabel() . ' was checked and forwarded to HR for approval.');

        return back()->with('success', 'DTR checked and forwarded to HR for approval.');
    }

    // HR's list of DTRs waiting on their final approval.
    public function pendingApprovals()
    {
        if (!Auth::user()->hasPermission('approve-dtr')) {
            abort(403);
        }

        $submissions = DtrSubmission::with('personnel', 'markChecker')
            ->where('status', 'mark_checked')
            ->orderBy('mark_checked_at')
            ->get();

        return view('utility_dtr.hr_pending', compact('submissions'));
    }

    public function approve(Request $request, $personnelId)
    {
        if (!Auth::user()->hasPermission('approve-dtr')) {
            abort(403);
        }

        $personnel = Personnel::utilityStaff()->findOrFail($personnelId);
        $submission = $this->findSubmissionOrFail($request, $personnel);

        if (!$submission || $submission->status !== 'mark_checked') {
            return back()->with('error', 'This DTR has not been checked by the General Services Officer yet.');
        }

        $submission->update([
            'status' => 'hr_approved',
            'hr_approved_at' => now(),
            'hr_approved_by' => Auth::id(),
        ]);

        $this->notifyPersonnelOwner($personnel, 'dtr', 'DTR Approved', 'Your DTR for ' . $submission->periodLabel() . ' has been approved by HR.');

        $this->notify(
            User::withPermission('manage-utility-schedule')->get(),
            'dtr',
            'DTR Approved',
            $personnel->fullname . "'s DTR for " . $submission->periodLabel() . ' has been approved by HR.',
            route('utility-dtr.show', ['personnelId' => $personnel->id, 'start_date' => $submission->period_start->toDateString(), 'end_date' => $submission->period_end->toDateString()], false)
        );

        return back()->with('success', 'DTR approved.');
    }

    // Shared by both stages — who's allowed depends on the DTR's current
    // status: employee_verified can only be rejected by Mark (send back to
    // draft before it ever reaches HR), mark_checked can only be rejected
    // by HR.
    public function reject(Request $request, $personnelId)
    {
        $personnel = Personnel::utilityStaff()->findOrFail($personnelId);

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $submission = $this->findSubmissionOrFail($request, $personnel);

        if (!$submission || !in_array($submission->status, ['employee_verified', 'mark_checked'])) {
            return back()->with('error', 'There is nothing pending to reject for this DTR.');
        }

        if ($submission->status === 'employee_verified' && !Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        if ($submission->status === 'mark_checked' && !Auth::user()->hasPermission('approve-dtr')) {
            abort(403);
        }

        $stage = $submission->status === 'employee_verified' ? 'mark' : 'hr';
        $periodLabel = $submission->periodLabel();

        $submission->update([
            'status' => 'draft',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejected_stage' => $stage,
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        $this->notifyPersonnelOwner(
            $personnel,
            'dtr',
            'DTR Sent Back for Correction',
            'Your DTR for ' . $periodLabel . ' was sent back' . ($validated['rejection_reason'] ? ': ' . $validated['rejection_reason'] : '.')
        );

        return back()->with('success', 'DTR sent back to the employee for correction.');
    }

    private function ownUtilityPersonnelOrAbort(): Personnel
    {
        $personnel = Auth::user()->personnel;

        if (!$personnel || !Personnel::utilityStaff()->where('id', $personnel->id)->exists()) {
            abort(403, 'This page is only for Utility & Maintenance Staff.');
        }

        return $personnel;
    }

    // Mark's/HR's actions always act on an exact previously-verified
    // period, passed as start_date/end_date hidden fields sourced from the
    // pending DtrSubmission itself — never re-derived from a generic month
    // picker, so they can't accidentally act on the wrong range.
    private function findSubmissionOrFail(Request $request, Personnel $personnel): ?DtrSubmission
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        return DtrSubmission::where('personnel_id', $personnel->id)
            ->where('period_start', $request->input('start_date'))
            ->where('period_end', $request->input('end_date'))
            ->first();
    }

    private function resolvePeriodFromVerifyRequest(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return [
            Carbon::parse($validated['start_date'])->startOfDay(),
            Carbon::parse($validated['end_date'])->startOfDay(),
        ];
    }

    private function notify($users, string $type, string $title, string $message, ?string $url = null): void
    {
        foreach ($users as $user) {

            $notif = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'url' => $url ?? '',
                'is_read' => 0,
            ]);

            event(new NewNotificationEvent($notif));
        }
    }

    private function notifyPersonnelOwner(Personnel $personnel, string $type, string $title, string $message): void
    {
        if (!$personnel->user_id) {
            return;
        }

        $this->notify(
            [User::find($personnel->user_id)],
            $type,
            $title,
            $message,
            route('utility-dtr.my', [], false)
        );
    }

    private function dtrDataForPersonnel(Request $request, Personnel $personnel): array
    {
        // A specific start_date/end_date (e.g. from a pending submission's
        // own period, or the employee's own range picker) always wins;
        // falling back to a full calendar month is just the default for
        // casual browsing when no specific range was requested.
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $periodStart = Carbon::parse($request->input('start_date'))->startOfDay();
            $periodEnd = Carbon::parse($request->input('end_date'))->startOfDay();
        } else {
            $month = $request->input('month', now()->format('Y-m'));
            $periodStart = Carbon::parse($month . '-01')->startOfMonth();
            $periodEnd = $periodStart->copy()->endOfMonth();
        }

        $entries = UtilitySchedule::where('personnel_id', $personnel->id)
            ->whereBetween('schedule_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->orderBy('schedule_date')
            ->get()
            ->keyBy(fn ($entry) => $entry->schedule_date->toDateString());

        // Approved leave overlapping this period, for the actual leave type
        // label on the printed form (e.g. "SICK LEAVE") instead of a
        // generic "ON LEAVE".
        $approvedLeaves = LeaveRequest::where('personnel_id', $personnel->id)
            ->where('status', 'Approved')
            ->where('start_date', '<=', $periodEnd->toDateString())
            ->where('end_date', '>=', $periodStart->toDateString())
            ->get();

        $days = collect();

        for ($date = $periodStart->copy(); $date->lte($periodEnd); $date->addDay()) {

            $entry = $entries->get($date->toDateString());
            $status = $entry?->attendanceStatus();

            // Rows with no real attendance to print get a merged label
            // instead of blank cells — the day name for a genuinely
            // unscheduled day (matches the reference DTR: "FRIDAY",
            // "SATURDAY", "SUNDAY" for off days), or the leave type for an
            // approved-leave day. A day with NO schedule entry at all can
            // still be an approved-leave day (leave filed for a date that
            // was never scheduled to begin with) — checked directly here
            // rather than only via $entry->attendanceStatus(), which can
            // only detect on_leave when an entry actually exists.
            $rowLabel = null;

            if (!$entry) {

                $leave = $approvedLeaves->first(fn ($l) => $date->between($l->start_date, $l->end_date));

                if ($leave) {
                    $status = 'on_leave';
                    $rowLabel = strtoupper($leave->leave_type);
                } else {
                    $rowLabel = strtoupper($date->format('l'));
                }

            } elseif ($status === 'on_leave') {
                $leave = $approvedLeaves->first(fn ($l) => $date->between($l->start_date, $l->end_date));
                $rowLabel = $leave ? strtoupper($leave->leave_type) : 'ON LEAVE';
            }

            // Raw undertime from actual clock times — only used to derive
            // the SUGGESTED default hours below, never shown directly.
            $rawUndertimeMinutes = $rowLabel ? null : $this->undertimeMinutes($entry);
            $defaultHours = $this->defaultCreditedHours($entry, $status, $rawUndertimeMinutes);
            $creditedHours = $entry?->credited_hours ?? $defaultHours;

            // The Undertime column actually shown (both on this page and
            // the CS Form 48 print) is always derived FROM the final
            // credited hours (default or GSO/HR-overridden), against the
            // standard 8-hr day — so editing a day's credited hours to 8
            // also zeroes its printed undertime, instead of the print
            // continuing to show the old raw clocked figure.
            $undertimeMinutes = $rowLabel
                ? null
                : ($creditedHours !== null
                    ? max(0, (int) round((UtilitySchedule::STANDARD_WORKDAY_HOURS - $creditedHours) * 60))
                    : $rawUndertimeMinutes);

            $days->push([
                'date' => $date->copy(),
                'entry' => $entry,
                'status' => $status,
                'rowLabel' => $rowLabel,
                // Approved leave and unscheduled days are authorized/not
                // applicable, not undertime.
                'undertimeMinutes' => $undertimeMinutes,
                'defaultHours' => $defaultHours,
                'creditedHours' => $creditedHours,
            ]);
        }

        $presentDays = $days->whereIn('status', ['present', 'late'])->count();
        $lateDays = $days->where('status', 'late')->count();
        $absentDays = $days->where('status', 'no_show')->count();
        $leaveDays = $days->where('status', 'on_leave')->count();

        // Credited hours (8-hr standard day minus undertime, 0 for a
        // no-show, 8 for approved leave, or a GSO/HR manual override) —
        // NOT the raw time_in-to-time_out span, which could read an
        // implausible 12+ hrs on a forgot-to-clock-out day. This only
        // drives the interactive "Hours Worked" summary here; the official
        // CS Form 48 print view keeps showing the raw undertime figures
        // exactly as recorded, since that's the physical form's own record
        // of actual clock times.
        $totalWorkedHours = round($days->sum('creditedHours'), 2);

        $totalOvertimeMinutes = $entries->sum('overtime_minutes');
        $totalUndertimeMinutes = $days->sum('undertimeMinutes');

        // "Official hours" line on the printed form — only shown when every
        // scheduled entry this period actually shares one shift, since
        // Utility Scheduling allows shift times to vary day to day and CS
        // Form 48 assumes one fixed regular schedule.
        $distinctShifts = $entries
            ->filter(fn ($entry) => $entry->shift_start && $entry->shift_end)
            ->map(fn ($entry) => $entry->shift_start . '-' . $entry->shift_end)
            ->unique();

        $officialHours = $distinctShifts->count() === 1
            ? Carbon::parse($entries->first()->shift_start)->format('g:i A') . ' – ' . Carbon::parse($entries->first()->shift_end)->format('g:i A')
            : null;

        $submission = DtrSubmission::where('personnel_id', $personnel->id)
            ->where('period_start', $periodStart->toDateString())
            ->where('period_end', $periodEnd->toDateString())
            ->first()
            ?? new DtrSubmission([
                'personnel_id' => $personnel->id,
                'month' => $periodStart->format('Y-m'),
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'status' => 'draft',
            ]);

        // Same month -> "JULY 1-31, 2026"; spans two months -> full dates
        // on both ends.
        $monthRangeLabel = $periodStart->isSameMonth($periodEnd)
            ? strtoupper($periodStart->format('F')) . ' ' . $periodStart->format('j') . '-' . $periodEnd->format('j') . ', ' . $periodStart->format('Y')
            : strtoupper($periodStart->format('F j')) . ' - ' . strtoupper($periodEnd->format('F j, Y'));

        return [
            'personnel' => $personnel,
            'month' => $periodStart->format('Y-m'),
            'monthStart' => $periodStart,
            'monthEnd' => $periodEnd,
            'monthLabel' => $periodStart->format('F Y'),
            'monthRangeLabel' => $monthRangeLabel,
            'days' => $days,
            'presentDays' => $presentDays,
            'lateDays' => $lateDays,
            'absentDays' => $absentDays,
            'leaveDays' => $leaveDays,
            'totalWorkedHours' => $totalWorkedHours,
            'totalOvertimeHours' => round($totalOvertimeMinutes / 60, 2),
            'totalUndertimeMinutes' => $totalUndertimeMinutes,
            'officialHours' => $officialHours,
            'submission' => $submission,
        ];
    }

    // CS Form 48's "Undertime" column — minutes short of the scheduled
    // shift for that day. A no-show (scheduled but never checked in) counts
    // the full shift as undertime; a completed shift's shortfall is
    // scheduled duration minus actual worked duration, floored at zero so
    // working late/overtime never shows as negative undertime.
    private function undertimeMinutes(?UtilitySchedule $entry): ?int
    {
        if (!$entry || !$entry->shift_start || !$entry->shift_end) {
            return null;
        }

        $scheduledMinutes = Carbon::parse($entry->shift_start)->diffInMinutes(Carbon::parse($entry->shift_end));

        if (!$entry->time_in) {
            return $scheduledMinutes;
        }

        if (!$entry->time_out) {
            return null;
        }

        $workedMinutes = $entry->time_in->diffInMinutes($entry->time_out);

        return max(0, (int) round($scheduledMinutes - $workedMinutes));
    }

    // Default credited hours before any GSO/HR override — 8-hr standard
    // workday minus undertime (covers both late arrival and early
    // departure, since undertimeMinutes already nets scheduled vs actual
    // worked duration), 0 for a no-show, 8 for approved leave. Falls back
    // to the raw clocked span only when there's no shift_start/shift_end to
    // measure undertime against at all. Days not yet finished (scheduled,
    // still checked in) return null — nothing to credit yet.
    private function defaultCreditedHours(?UtilitySchedule $entry, ?string $status, ?int $undertimeMinutes): ?float
    {
        if ($status === 'on_leave') {
            return UtilitySchedule::STANDARD_WORKDAY_HOURS;
        }

        if ($status === 'no_show') {
            return 0.0;
        }

        if (!in_array($status, ['present', 'late', 'incomplete'])) {
            return null;
        }

        if ($undertimeMinutes !== null) {
            return round(max(0, UtilitySchedule::STANDARD_WORKDAY_HOURS - ($undertimeMinutes / 60)), 2);
        }

        if ($entry?->time_in && $entry->time_out) {
            return round($entry->time_in->diffInMinutes($entry->time_out) / 60, 2);
        }

        return null;
    }
}
