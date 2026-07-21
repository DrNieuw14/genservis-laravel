<?php

namespace App\Http\Controllers;

use App\Models\JobRequest;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\Personnel;
use App\Models\UtilitySchedule;
use App\Events\NewNotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UtilityScheduleController extends Controller
{
    // 📅 Weekly duty roster — Mark builds/edits the schedule here
    public function index(Request $request)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        $data = $this->weekData($request);

        $data['jobRequests'] = JobRequest::whereIn('status', ['approved', 'assigned', 'work_done'])
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'reference_no', 'nature_of_request']);

        return view('utility_schedule.index', $data);
    }

    public function print(Request $request)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        return view('utility_schedule.print', $this->weekData($request));
    }

    private function weekData(Request $request): array
    {
        $weekParam = $request->query('week');

        $weekStart = $weekParam
            ? Carbon::parse($weekParam)->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);

        $weekEnd = (clone $weekStart)->endOfWeek(Carbon::SUNDAY);

        $weekDays = collect(range(0, 6))->map(fn ($i) => (clone $weekStart)->addDays($i));

        $staff = Personnel::utilityStaff()
            ->with('positionRecord')
            ->orderBy('fullname')
            ->get();

        $entries = UtilitySchedule::whereIn('personnel_id', $staff->pluck('id'))
            ->whereBetween('schedule_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->with('jobRequest')
            ->get();

        // Keyed by "personnelId_Y-m-d" so the grid view can look up a cell's
        // entries directly instead of filtering the whole collection per cell.
        $entriesByCell = $entries->groupBy(
            fn ($entry) => $entry->personnel_id . '_' . $entry->schedule_date->format('Y-m-d')
        );

        return [
            'staff' => $staff,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'weekDays' => $weekDays,
            'entriesByCell' => $entriesByCell,
        ];
    }

    // Creates one entry per day in [schedule_date, schedule_date_end] (the
    // latter defaults to schedule_date, i.e. a single day) — same task,
    // shift, location, and notes on every day. Days the staff member is
    // already scheduled on are skipped rather than overwritten.
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        $validated = $this->validateEntry($request);

        // Sort rather than require a strict order — the "Start Date" field
        // is pre-filled from whichever day's + Add button was clicked, so a
        // range typed in the other direction (e.g. End Date earlier than
        // the pre-filled Start Date) is still a valid range, just reversed.
        $rawStart = Carbon::parse($validated['schedule_date']);
        $rawEnd = !empty($validated['schedule_date_end'])
            ? Carbon::parse($validated['schedule_date_end'])
            : $rawStart->copy();

        $startDate = $rawStart->lte($rawEnd) ? $rawStart : $rawEnd;
        $endDate = $rawStart->lte($rawEnd) ? $rawEnd : $rawStart;

        $entryData = collect($validated)->except(['schedule_date', 'schedule_date_end'])->toArray();

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $createdEntries = collect();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

            $alreadyScheduled = UtilitySchedule::where('personnel_id', $validated['personnel_id'])
                ->whereDate('schedule_date', $date)
                ->exists();

            if ($alreadyScheduled) {
                continue;
            }

            $createdEntries->push(
                UtilitySchedule::create($entryData + [
                    'schedule_date' => $date->toDateString(),
                    'created_by' => Auth::id(),
                ])
            );
        }

        if ($createdEntries->isEmpty()) {
            return back()->with('error', 'That staff member already has an entry on every date in this range.');
        }

        $dateRangeLabel = $startDate->isSameDay($endDate)
            ? $startDate->format('M d, Y')
            : $startDate->format('M d') . ' – ' . $endDate->format('M d, Y');

        $this->notifyWorker(
            $createdEntries->first(),
            'New Schedule Assignment',
            (Auth::user()->fullname ?? Auth::user()->username)
                . ' scheduled you for "' . $createdEntries->first()->task . '" on ' . $dateRangeLabel . '.'
        );

        $count = $createdEntries->count();
        $skipped = $totalDays - $count;

        $message = ($count === 1 && $totalDays === 1)
            ? 'Schedule entry added.'
            : "Schedule added for {$count} of {$totalDays} day(s)"
                . ($skipped > 0 ? " — {$skipped} already scheduled and skipped" : '') . '.';

        // Informational only — doesn't block the schedule from being
        // created, same "flag it, don't stop the user" approach as the
        // overtime detection on check-out. Mark decides what to do next.
        $leaveConflictDays = $createdEntries->filter(
            fn ($entry) => LeaveRequest::hasApprovedLeaveOn($entry->personnel_id, $entry->schedule_date)
        )->count();

        if ($leaveConflictDays > 0) {
            $message .= " ⚠️ {$leaveConflictDays} of those day(s) overlap this person's approved leave.";
        }

        return back()->with('success', $message);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        $entry = UtilitySchedule::findOrFail($id);

        // Editing always targets this one entry — a range only applies
        // when creating new entries via store().
        $validated = collect($this->validateEntry($request))->except('schedule_date_end')->toArray();

        // Mark can correct attendance directly (e.g. a worker forgot to tap
        // check-in but was actually on site) — self check-in/out is the
        // primary source of truth, this is just the override path.
        $attendanceOverride = $request->validate([
            'time_in' => 'nullable|date',
            'time_out' => 'nullable|date',
            'overtime_reason' => 'nullable|string|max:255',
        ]);

        $entry->fill($validated);
        $entry->time_in = $attendanceOverride['time_in'] ?? null;
        $entry->time_out = $attendanceOverride['time_out'] ?? null;
        $entry->overtime_reason = $attendanceOverride['overtime_reason'] ?? null;
        $entry->overtime_minutes = $entry->computeOvertimeMinutes();
        $entry->save();

        $this->notifyWorker(
            $entry,
            'Schedule Updated',
            (Auth::user()->fullname ?? Auth::user()->username)
                . ' updated your schedule for "' . $entry->task . '" on '
                . $entry->schedule_date->format('M d, Y') . '.'
        );

        return back()->with('success', 'Schedule entry updated.');
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        $entry = UtilitySchedule::with('personnel.user')->findOrFail($id);

        $task = $entry->task;
        $date = $entry->schedule_date->format('M d, Y');
        $workerUser = $entry->personnel->user;

        $entry->delete();

        if ($workerUser) {

            $notif = Notification::create([
                'user_id' => $workerUser->id,
                'type' => 'utility_schedule',
                'title' => 'Schedule Entry Removed',
                'url' => route('utility-schedule.my', [], false),
                'message' =>
                    (Auth::user()->fullname ?? Auth::user()->username)
                    . ' removed your scheduled task "' . $task . '" on ' . $date . '.',
                'is_read' => 0,
            ]);

            event(new NewNotificationEvent($notif));
        }

        return back()->with('success', 'Schedule entry removed.');
    }

    // 🔁 Copies every entry in the given week 7 days forward, skipping any
    // personnel/date slot that's already scheduled in the target week.
    public function duplicateWeek(Request $request)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'source_week' => 'required|date',
        ]);

        $sourceStart = Carbon::parse($validated['source_week'])->startOfWeek(Carbon::MONDAY);
        $sourceEnd = (clone $sourceStart)->endOfWeek(Carbon::SUNDAY);
        $targetStart = (clone $sourceStart)->addDays(7);

        $sourceEntries = UtilitySchedule::whereBetween(
            'schedule_date',
            [$sourceStart->toDateString(), $sourceEnd->toDateString()]
        )->get();

        $copied = 0;

        foreach ($sourceEntries as $entry) {

            $newDate = (clone $entry->schedule_date)->addDays(7);

            $alreadyScheduled = UtilitySchedule::where('personnel_id', $entry->personnel_id)
                ->whereDate('schedule_date', $newDate)
                ->exists();

            if ($alreadyScheduled) {
                continue;
            }

            $newEntry = UtilitySchedule::create([
                'personnel_id' => $entry->personnel_id,
                'schedule_date' => $newDate,
                'shift_start' => $entry->shift_start,
                'shift_end' => $entry->shift_end,
                'task' => $entry->task,
                'location' => $entry->location,
                'notes' => $entry->notes,
                'created_by' => Auth::id(),
            ]);

            $this->notifyWorker(
                $newEntry,
                'New Schedule Assignment',
                (Auth::user()->fullname ?? Auth::user()->username)
                    . ' scheduled you for "' . $newEntry->task . '" on '
                    . $newEntry->schedule_date->format('M d, Y') . '.'
            );

            $copied++;
        }

        return redirect()
            ->route('utility-schedule.index', ['week' => $targetStart->toDateString()])
            ->with('success', "Duplicated {$copied} entr" . ($copied === 1 ? 'y' : 'ies') . " to the next week.");
    }

    // 🧰 A worker's (e.g. Rony, Aldrin) own schedule — Utility & Maintenance
    // Staff only, not every personnel/supervisor account (a duty roster
    // only applies to this specific pool).
    public function mySchedule()
    {
        $personnel = Auth::user()->personnel;

        if (!$personnel || !Personnel::utilityStaff()->where('id', $personnel->id)->exists()) {
            abort(403);
        }

        // Recent past + everything upcoming — not the full unbounded
        // history, so this doesn't grow into a huge list over time. Past
        // entries older than a week fall off; use the Attendance Report
        // (Mark's view) for anything further back.
        $entries = UtilitySchedule::where('personnel_id', $personnel->id)
            ->where('schedule_date', '>=', now()->subDays(7))
            ->with('jobRequest')
            ->orderBy('schedule_date')
            ->get();

        return view('utility_schedule.my-schedule', compact('entries'));
    }

    // ✅ Trust-based self check-in — no proof required, matches how
    // lightly the rest of this module is scoped.
    public function checkIn($id)
    {
        $entry = UtilitySchedule::findOrFail($id);

        $this->authorizeOwnEntry($entry);

        $result = $entry->performCheckIn();

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function checkOut(Request $request, $id)
    {
        $entry = UtilitySchedule::findOrFail($id);

        $this->authorizeOwnEntry($entry);

        $validated = $request->validate([
            'overtime_reason' => 'nullable|string|max:255',
        ]);

        $result = $entry->performCheckOut($validated['overtime_reason'] ?? null);

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    private function authorizeOwnEntry(UtilitySchedule $entry): void
    {
        $personnel = Auth::user()->personnel;

        if (!$personnel || $entry->personnel_id !== $personnel->id) {
            abort(403);
        }
    }

    // 📊 Attendance Report — present/late/no-show/incomplete counts and
    // total overtime, for Mark's own reporting/records (same pattern as
    // Job Request Report).
    public function attendanceReport(Request $request)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        return view('utility_schedule.attendance_report', $this->attendanceReportData($request));
    }

    public function attendanceReportPrint(Request $request)
    {
        if (!Auth::user()->hasPermission('manage-utility-schedule')) {
            abort(403);
        }

        return view('utility_schedule.attendance_report_print', $this->attendanceReportData($request));
    }

    private function attendanceReportData(Request $request): array
    {
        $dateFrom = $request->date('date_from');
        $dateTo = $request->date('date_to');
        $personnelId = $request->input('personnel_id');

        $staff = Personnel::utilityStaff()->orderBy('fullname')->get();

        $entries = UtilitySchedule::whereIn('personnel_id', $staff->pluck('id'))
            ->with('personnel')
            ->when($dateFrom, fn ($q) => $q->whereDate('schedule_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('schedule_date', '<=', $dateTo))
            ->when($personnelId, fn ($q) => $q->where('personnel_id', $personnelId))
            ->orderByDesc('schedule_date')
            ->get();

        $statusCounts = $entries->countBy(fn ($entry) => $entry->attendanceStatus());

        return [
            'entries' => $entries,
            'staff' => $staff,
            'dateFrom' => $dateFrom?->format('Y-m-d'),
            'dateTo' => $dateTo?->format('Y-m-d'),
            'personnelId' => $personnelId,
            'presentCount' => $statusCounts->get('present', 0),
            'lateCount' => $statusCounts->get('late', 0),
            'noShowCount' => $statusCounts->get('no_show', 0),
            'incompleteCount' => $statusCounts->get('incomplete', 0),
            'onLeaveCount' => $statusCounts->get('on_leave', 0),
            'totalOvertimeMinutes' => $entries->sum('overtime_minutes'),
        ];
    }

    private function validateEntry(Request $request): array
    {
        return $request->validate([
            'personnel_id' => [
                'required',
                'exists:personnel,id',
                function ($attribute, $value, $fail) {
                    if (!Personnel::utilityStaff()->where('id', $value)->exists()) {
                        $fail('That person is not part of the Utility & Maintenance Staff pool.');
                    }
                },
            ],
            'schedule_date' => 'required|date',
            'schedule_date_end' => 'nullable|date',
            'shift_start' => 'nullable|date_format:H:i',
            'shift_end' => 'nullable|date_format:H:i|after:shift_start',
            'task' => 'required|string|max:200',
            'location' => 'nullable|string|max:150',
            'notes' => 'nullable|string|max:1000',
            'job_request_id' => 'nullable|exists:job_requests,id',
        ], [
            'task.required' => 'Please describe the task for this schedule entry.',
            'shift_end.after' => 'Shift end must be after shift start.',
        ]);
    }

    private function notifyWorker(UtilitySchedule $entry, string $title, string $message): void
    {
        $workerUser = Personnel::find($entry->personnel_id)?->user;

        if (!$workerUser) {
            return;
        }

        $notif = Notification::create([
            'user_id' => $workerUser->id,
            'type' => 'utility_schedule',
            'title' => $title,
            'url' => route('utility-schedule.my', [], false),
            'message' => $message,
            'is_read' => 0,
        ]);

        event(new NewNotificationEvent($notif));
    }
}
