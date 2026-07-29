<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\FacultyProfile;
use App\Models\Personnel;
use App\Models\Program;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ClassScheduleController extends Controller
{
    // Half-hour slots, 7:00 AM to 7:00 PM — matches the real schedule's own
    // granularity (every class starts/ends on a :00 or :30 boundary).
    private function timeSlots(): array
    {
        $slots = [];
        $t = Carbon::createFromTime(7, 0);
        $end = Carbon::createFromTime(19, 0);

        while ($t->lt($end)) {
            $slots[] = $t->format('H:i:s');
            $t->addMinutes(30);
        }

        return $slots;
    }

    /**
     * Builds the row-by-day grid shared by all three views (section/room/
     * faculty) — each cell is null (skip, covered by an earlier rowspan),
     * ['type' => 'empty'], or ['type' => 'entry', 'entry' => ..., 'rowspan' => N].
     * A rowspan-aware HTML table can't be built by looping the collection
     * directly, since a 90-minute class needs to skip the two half-hour
     * rows after it for that day only, while other days keep rendering
     * normally in the same <tr>.
     */
    private function buildGrid($entries, array $days): array
    {
        $slots = $this->timeSlots();
        $entriesByDaySlot = $entries->groupBy(fn ($e) => $e->day_of_week . '_' . $e->start_time);

        $skip = array_fill_keys($days, 0);
        $grid = [];

        foreach ($slots as $slot) {

            $row = [];

            foreach ($days as $day) {

                if ($skip[$day] > 0) {
                    $row[$day] = null;
                    $skip[$day]--;
                    continue;
                }

                $entry = $entriesByDaySlot->get($day . '_' . $slot)?->first();

                if ($entry) {

                    $minutes = (strtotime($entry->end_time) - strtotime($entry->start_time)) / 60;
                    $rowspan = max(1, (int) round($minutes / 30));

                    $row[$day] = ['type' => 'entry', 'entry' => $entry, 'rowspan' => $rowspan];
                    $skip[$day] = $rowspan - 1;

                } else {

                    $row[$day] = ['type' => 'empty'];

                }

            }

            $grid[] = ['slot' => $slot, 'cells' => $row];

        }

        return $grid;
    }

    // --- Section view (the main workspace Joseph builds the schedule in) ---

    public function index(Request $request)
    {
        if (! Auth::user()->hasPermission('view-class-schedule')) {
            abort(403);
        }

        $programs = Program::orderBy('code')->get();

        $sectionsQuery = Section::with('program')->orderBy('program_id')->orderBy('year_level')->orderBy('section_letter');

        if ($request->filled('program_id')) {
            $sectionsQuery->where('program_id', $request->query('program_id'));
        }

        if ($request->filled('year_level')) {
            $sectionsQuery->where('year_level', $request->query('year_level'));
        }

        $sections = $sectionsQuery->get();

        $sectionId = $request->query('section_id', $sections->first()?->id);
        $section = ($sectionId && $sections->contains('id', (int) $sectionId))
            ? Section::with('program')->find($sectionId)
            : null;

        $grid = null;
        $subjectLoad = null;

        if ($section) {
            $entries = ClassSchedule::where('section_id', $section->id)
                ->with(['subject', 'faculty'])
                ->get();

            $grid = $this->buildGrid($entries, ClassSchedule::DAYS);

            $subjectLoad = $entries->groupBy('subject_id')->map(function ($rows) {
                return [
                    'subject' => $rows->first()->subject,
                    'days' => $rows->pluck('day_of_week')->unique()->implode(', '),
                ];
            })->values();
        }

        $subjects = Subject::orderBy('code')->get();
        $faculty = Personnel::orderBy('fullname')->get();

        // Room has no catalog/FK (see Section note above — real room names
        // are too inconsistent to force into a rigid list), so this is only
        // a typing aid: suggest rooms already in use, but any new value
        // typed here still saves fine.
        $rooms = ClassSchedule::whereNotNull('room')->distinct()->orderBy('room')->pluck('room');

        return view('class_schedule.index', compact(
            'programs',
            'sections',
            'section',
            'grid',
            'subjectLoad',
            'subjects',
            'faculty',
            'rooms'
        ));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $this->validateEntry($request);

        $conflicts = ClassSchedule::findConflicts(
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time'],
            $validated['section_id'],
            $validated['personnel_id'] ?? null,
            $validated['room'] ?? null
        );

        if ($conflicts->isNotEmpty()) {
            return back()->withInput()->with('error', $this->conflictMessage($conflicts));
        }

        ClassSchedule::create($validated + ['created_by' => Auth::id()]);

        return back()->with('success', 'Class schedule entry added.');
    }

    public function update(Request $request, ClassSchedule $classSchedule)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $this->validateEntry($request);

        $conflicts = ClassSchedule::findConflicts(
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time'],
            $validated['section_id'],
            $validated['personnel_id'] ?? null,
            $validated['room'] ?? null,
            $classSchedule->id
        );

        if ($conflicts->isNotEmpty()) {
            return back()->withInput()->with('error', $this->conflictMessage($conflicts));
        }

        $classSchedule->update($validated);

        return back()->with('success', 'Class schedule entry updated.');
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $classSchedule->delete();

        return back()->with('success', 'Class schedule entry removed.');
    }

    /**
     * Live "is this room/faculty actually free?" check for the Add/Edit
     * Class modal — same findConflicts() logic the real save uses, just
     * queried ahead of time so the user sees it before clicking Save
     * instead of only after a rejected submit.
     */
    public function checkAvailability(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'day_of_week' => 'required|in:' . implode(',', ClassSchedule::DAYS),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'section_id' => 'nullable|exists:sections,id',
            'personnel_id' => 'nullable|exists:personnel,id',
            'room' => 'nullable|string|max:50',
            'ignore_id' => 'nullable|integer',
        ]);

        $conflicts = ClassSchedule::findConflicts(
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time'],
            $validated['section_id'] ?? null,
            $validated['personnel_id'] ?? null,
            $validated['room'] ?? null,
            $validated['ignore_id'] ?? null
        );

        return response()->json([
            'clear' => $conflicts->isEmpty(),
            'conflicts' => $conflicts->map(fn ($c) => [
                'section' => $c->section->label,
                'subject' => $c->subject->code,
                'time' => $c->time_range_label,
                'room' => $c->room,
                'faculty' => $c->faculty_label,
            ])->values(),
        ]);
    }

    private function conflictMessage($conflicts): string
    {
        $first = $conflicts->first();

        return '⚠ Conflict: ' . $first->section->label . ' already has ' . $first->subject->code
            . ' (' . $first->day_of_week . ', ' . $first->time_range_label . ')'
            . ($first->faculty ? ' with ' . $first->faculty->fullname : '')
            . ($first->room ? ' in room ' . $first->room : '') . '.';
    }

    private function validateEntry(Request $request): array
    {
        return $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'personnel_id' => 'nullable|exists:personnel,id',
            'room' => 'nullable|string|max:50',
            'day_of_week' => 'required|in:' . implode(',', ClassSchedule::DAYS),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
    }

    // --- Room view (read-only, matches the ROOM SCHEDULE sheet) ---

    public function roomView(Request $request)
    {
        if (! Auth::user()->hasPermission('view-class-schedule')) {
            abort(403);
        }

        $rooms = ClassSchedule::whereNotNull('room')->distinct()->orderBy('room')->pluck('room');

        $room = $request->query('room', $rooms->first());

        $grid = null;

        if ($room) {
            $entries = ClassSchedule::where('room', $room)
                ->with(['subject', 'section.program', 'faculty'])
                ->get();

            $grid = $this->buildGrid($entries, ClassSchedule::DAYS);
        }

        return view('class_schedule.room_view', compact('rooms', 'room', 'grid'));
    }

    // --- Faculty view (read-only + profile fields, matches the per-department "FACULTY CLASS SCHEDULE" sheets) ---

    /**
     * The faculty picker lists two kinds of identity: real linked Personnel
     * (key "personnel:{id}") and plain-text transcribed names with no
     * Personnel match (key "name:{name}", e.g. bulk-imported instructors
     * like "MR DALISAY" — see ClassScheduleImportSeeder). Both are real
     * people with a real teaching load; only the first kind has an
     * employee record to hang a profile/stats off of.
     */
    public function facultyView(Request $request)
    {
        if (! Auth::user()->hasPermission('view-class-schedule')) {
            abort(403);
        }

        $programs = Program::orderBy('code')->get();
        $programId = $request->query('program_id');
        $yearLevel = $request->query('year_level');

        // Program/year-level filter narrows to faculty who actually have a
        // class in a section matching that program/year — a profile with no
        // schedule entries has no program to belong to, so it drops out once
        // either filter is applied (matches by-course-load, not by roster).
        if ($programId || $yearLevel) {

            $sectionFilter = fn ($q) => $q->when($programId, fn ($q2) => $q2->where('program_id', $programId))
                ->when($yearLevel, fn ($q2) => $q2->where('year_level', $yearLevel));

            $personnelOptions = Personnel::whereHas('classSchedules.section', $sectionFilter)
                ->orderBy('fullname')
                ->get()
                ->map(fn ($p) => ['key' => 'personnel:' . $p->id, 'label' => $p->fullname]);

            $nameOptions = ClassSchedule::whereNull('personnel_id')
                ->whereNotNull('faculty_name')
                ->whereHas('section', $sectionFilter)
                ->distinct()
                ->orderBy('faculty_name')
                ->pluck('faculty_name')
                ->map(fn ($name) => ['key' => 'name:' . $name, 'label' => $name . ' (unlinked)']);

        } else {

            $personnelOptions = Personnel::whereHas('classSchedules')
                ->orWhereHas('facultyProfile')
                ->orderBy('fullname')
                ->get()
                ->map(fn ($p) => ['key' => 'personnel:' . $p->id, 'label' => $p->fullname]);

            $nameOptions = ClassSchedule::whereNull('personnel_id')
                ->whereNotNull('faculty_name')
                ->distinct()
                ->orderBy('faculty_name')
                ->pluck('faculty_name')
                ->map(fn ($name) => ['key' => 'name:' . $name, 'label' => $name . ' (unlinked)']);

        }

        $facultyList = $personnelOptions->concat($nameOptions)->sortBy('label')->values();

        $key = $request->query('faculty', $facultyList->first()['key'] ?? null);

        if ($key && ! $facultyList->contains('key', $key)) {
            $key = $facultyList->first()['key'] ?? null;
        }

        $grid = null;
        $subjectLoad = null;
        $profile = null;
        $facultyLabel = null;
        $personnel = null;
        $numberOfPreparations = null;
        $totalContactHours = null;

        if ($key) {

            if (str_starts_with($key, 'personnel:')) {

                $personnel = Personnel::find((int) substr($key, strlen('personnel:')));
                $facultyLabel = $personnel?->fullname;
                $entries = $personnel
                    ? ClassSchedule::where('personnel_id', $personnel->id)->with(['subject', 'section.program'])->get()
                    : collect();

                if ($personnel) {
                    $profile = FacultyProfile::firstOrNew(['personnel_id' => $personnel->id]);
                }

            } else {

                $name = substr($key, strlen('name:'));
                $facultyLabel = $name;
                $entries = ClassSchedule::where('faculty_name', $name)->with(['subject', 'section.program'])->get();

            }

            $grid = $this->buildGrid($entries, ClassSchedule::DAYS);

            $subjectLoad = $entries->groupBy(fn ($e) => $e->subject_id . '_' . $e->section_id)->map(function ($rows) {
                $first = $rows->first();
                return [
                    'subject' => $first->subject,
                    'section' => $first->section,
                ];
            })->values();

            $numberOfPreparations = $entries->pluck('subject_id')->unique()->count();
            $totalContactHours = round($entries->sum(
                fn ($e) => (strtotime($e->end_time) - strtotime($e->start_time)) / 3600
            ), 2);

        }

        $allPersonnel = Personnel::orderBy('fullname')->get();

        return view('class_schedule.faculty_view', compact(
            'programs',
            'facultyList',
            'key',
            'facultyLabel',
            'personnel',
            'grid',
            'subjectLoad',
            'profile',
            'numberOfPreparations',
            'totalContactHours',
            'allPersonnel'
        ));
    }

    /**
     * Joseph confirms a plain-text transcribed name (e.g. "MR DALISAY")
     * really is a specific real Personnel record. Bulk-updates every
     * ClassSchedule row carrying that exact faculty_name to point at the
     * real employee instead — the one-time fix for the "unlinked" gap left
     * by the bulk import (which deliberately never auto-matched names, to
     * avoid linking the wrong real person).
     */
    public function linkFacultyName(Request $request)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'faculty_name' => 'required|string',
            'personnel_id' => 'required|exists:personnel,id',
        ]);

        $count = ClassSchedule::where('faculty_name', $validated['faculty_name'])
            ->whereNull('personnel_id')
            ->update(['personnel_id' => $validated['personnel_id']]);

        $personnel = Personnel::find($validated['personnel_id']);

        return redirect()
            ->route('class-schedule.faculty', ['faculty' => 'personnel:' . $personnel->id])
            ->with('success', "Linked {$count} schedule entr" . ($count === 1 ? 'y' : 'ies') . " from \"{$validated['faculty_name']}\" to {$personnel->fullname}.");
    }

    public function updateFacultyProfile(Request $request, Personnel $personnel)
    {
        if (! Auth::user()->hasPermission('manage-class-schedule')) {
            abort(403);
        }

        $validated = $request->validate([
            'highest_educational_attainment' => 'nullable|string|max:255',
            'consultation_schedule' => 'nullable|string',
            'designation' => 'nullable|string|max:255',
            'research' => 'nullable|string',
            'extension' => 'nullable|string',
        ]);

        FacultyProfile::updateOrCreate(
            ['personnel_id' => $personnel->id],
            $validated
        );

        return back()->with('success', 'Faculty profile updated.');
    }
}
