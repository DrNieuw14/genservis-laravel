@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📅 Utility Scheduling
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Weekly duty roster for Utility & Maintenance Staff.
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('utility-schedule.attendance-report') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                📊 Attendance Report
            </a>

            <a href="{{ route('utility-schedule.print', ['week' => $weekStart->toDateString()]) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

            <form method="POST" action="{{ route('utility-schedule.duplicate-week') }}"
                  onsubmit="return confirm('Copy every entry from this week into next week? Slots already scheduled next week will be skipped.')">
                @csrf
                <input type="hidden" name="source_week" value="{{ $weekStart->toDateString() }}">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    🔁 Duplicate to Next Week
                </button>
            </form>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- WEEK NAVIGATION -->
    <div class="flex items-center justify-between border rounded-lg p-4 bg-gray-50 mb-6">

        <a href="{{ route('utility-schedule.index', ['week' => $weekStart->copy()->subDays(7)->toDateString()]) }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            ← Previous Week
        </a>

        <span class="font-semibold text-lg text-gray-700">
            {{ $weekStart->format('M d') }} – {{ $weekEnd->format('M d, Y') }}
        </span>

        <a href="{{ route('utility-schedule.index', ['week' => $weekStart->copy()->addDays(7)->toDateString()]) }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            Next Week →
        </a>

    </div>

    <!-- ROSTER GRID -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full border-collapse">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left w-48">Staff</th>
                    @foreach($weekDays as $day)
                        <th class="p-3 text-center {{ $day->isToday() ? 'bg-green-100' : '' }}">
                            {{ $day->format('D') }}
                            <div class="text-xs font-normal text-gray-500">{{ $day->format('M d') }}</div>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($staff as $person)

                    <tr class="align-top">

                        <td class="p-3 font-semibold text-gray-700 bg-gray-50">
                            {{ $person->fullname }}
                            <div class="text-xs text-gray-500 font-normal">
                                {{ $person->positionRecord->position_name ?? '-' }}
                            </div>
                        </td>

                        @foreach($weekDays as $day)

                            @php
                                $cellKey = $person->id . '_' . $day->format('Y-m-d');
                                $cellEntries = $entriesByCell->get($cellKey, collect());
                            @endphp

                            <td class="p-2 border-l {{ $day->isToday() ? 'bg-green-50' : '' }}">

                                @foreach($cellEntries as $entry)

                                    @php
                                        $status = $entry->attendanceStatus();
                                        $statusInfo = \App\Models\UtilitySchedule::attendanceStatusLabel($status);
                                    @endphp

                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 mb-2 text-sm">

                                        <div class="font-semibold text-gray-800">{{ $entry->task }}</div>

                                        @if($entry->shift_start || $entry->shift_end)
                                            <div class="text-gray-500 text-xs">
                                                {{ $entry->shift_start ? \Illuminate\Support\Carbon::parse($entry->shift_start)->format('g:i A') : '?' }}
                                                –
                                                {{ $entry->shift_end ? \Illuminate\Support\Carbon::parse($entry->shift_end)->format('g:i A') : '?' }}
                                            </div>
                                        @endif

                                        @if($entry->location)
                                            <div class="text-gray-500 text-xs">📍 {{ $entry->location }}</div>
                                        @endif

                                        @if($entry->jobRequest)
                                            <div class="text-gray-500 text-xs">🛠️ {{ $entry->jobRequest->reference_no }}</div>
                                        @endif

                                        <div class="mt-1">
                                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $statusInfo['class'] }}">
                                                {{ $statusInfo['label'] }}
                                            </span>

                                            @if($entry->overtime_minutes > 0)
                                                <span class="text-xs text-orange-600 font-semibold">+{{ $entry->overtime_minutes }}m OT</span>
                                            @endif
                                        </div>

                                        <div class="flex gap-2 mt-1">

                                            <button type="button"
                                                class="text-blue-600 hover:underline text-xs"
                                                onclick='openScheduleModal("edit", {{ $entry->id }}, {{ $person->id }}, "{{ $day->format('Y-m-d') }}", {{ json_encode($entry->shift_start ? \Illuminate\Support\Carbon::parse($entry->shift_start)->format('H:i') : null) }}, {{ json_encode($entry->shift_end ? \Illuminate\Support\Carbon::parse($entry->shift_end)->format('H:i') : null) }}, {{ json_encode($entry->task) }}, {{ json_encode($entry->location) }}, {{ json_encode($entry->notes) }}, {{ json_encode($entry->job_request_id) }}, {{ json_encode(optional($entry->time_in)->format('Y-m-d\TH:i')) }}, {{ json_encode(optional($entry->time_out)->format('Y-m-d\TH:i')) }}, {{ json_encode($entry->overtime_reason) }})'>
                                                ✏️ Edit
                                            </button>

                                            <form method="POST" action="{{ route('utility-schedule.destroy', $entry->id) }}"
                                                  onsubmit="return confirm('Remove this schedule entry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-xs">
                                                    🗑 Remove
                                                </button>
                                            </form>

                                        </div>

                                    </div>

                                @endforeach

                                <button type="button"
                                    class="text-xs text-gray-500 hover:text-blue-600 border border-dashed rounded-lg w-full py-1"
                                    onclick='openScheduleModal("add", null, {{ $person->id }}, "{{ $day->format('Y-m-d') }}", null, null, "", "", "", null)'>
                                    + Add
                                </button>

                            </td>

                        @endforeach

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No Utility & Maintenance Staff on record yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- ADD/EDIT MODAL -->
<div id="scheduleModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center border-b px-6 py-4">

            <h2 id="scheduleModalTitle" class="text-xl font-bold">
                Add Schedule Entry
            </h2>

            <button type="button" onclick="closeScheduleModal()" class="text-gray-500 hover:text-red-600 text-xl">
                ✕
            </button>

        </div>

        <form id="scheduleForm" method="POST" action="{{ route('utility-schedule.store') }}">
            @csrf
            <input type="hidden" id="scheduleFormMethod" name="_method" value="POST">
            <input type="hidden" id="schedulePersonnelId" name="personnel_id">

            <div class="p-6 grid grid-cols-1 gap-4">

                <div>
                    <label class="block mb-1 font-semibold text-sm" id="scheduleStaffLabel">Staff</label>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm" id="scheduleDateLabel">Date</label>
                    <input type="text" id="scheduleDateRangeInput" readonly autocomplete="off"
                        class="w-full border rounded-lg p-3 cursor-pointer bg-white"
                        placeholder="Click to pick a date">
                    <input type="hidden" name="schedule_date" id="scheduleDate">
                    <input type="hidden" name="schedule_date_end" id="scheduleDateEnd">
                </div>

                <p id="scheduleRangeHint" class="text-sm font-medium -mt-2 hidden"></p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-semibold text-sm">Shift Start (optional)</label>
                        <input type="time" name="shift_start" id="scheduleShiftStart" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-sm">Shift End (optional)</label>
                        <input type="time" name="shift_end" id="scheduleShiftEnd" class="w-full border rounded-lg p-3">
                    </div>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Task</label>
                    <input type="text" name="task" id="scheduleTask" placeholder="e.g. Grounds maintenance - Building A" class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Location (optional)</label>
                    <input type="text" name="location" id="scheduleLocation" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Related Job Request (optional)</label>
                    <select name="job_request_id" id="scheduleJobRequest" class="w-full border rounded-lg p-3">
                        <option value="">— None —</option>
                        @foreach($jobRequests as $jr)
                            <option value="{{ $jr->id }}">{{ $jr->reference_no }} — {{ $jr->nature_of_request }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Notes (optional)</label>
                    <textarea name="notes" id="scheduleNotes" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>

                <div id="scheduleAttendanceSection" class="hidden border-t pt-4 mt-2">

                    <p class="text-xs font-bold text-gray-400 uppercase mb-3">
                        Attendance (override — worker normally checks in/out themselves)
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 font-semibold text-sm">Time In</label>
                            <input type="datetime-local" name="time_in" id="scheduleTimeIn" class="w-full border rounded-lg p-3">
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-sm">Time Out</label>
                            <input type="datetime-local" name="time_out" id="scheduleTimeOut" class="w-full border rounded-lg p-3">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block mb-1 font-semibold text-sm">Overtime Reason (optional)</label>
                        <input type="text" name="overtime_reason" id="scheduleOvertimeReason" maxlength="255" class="w-full border rounded-lg p-3">
                    </div>

                </div>

            </div>

            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeScheduleModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">
                    💾 Save
                </button>
            </div>

        </form>

    </div>

</div>

<!-- Flatpickr — interactive calendar range picker for the schedule modal -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: #2563eb;
        border-color: #2563eb;
    }

    .flatpickr-day.inRange {
        background: #dbeafe;
        border-color: #dbeafe;
        box-shadow: -5px 0 0 #dbeafe, 5px 0 0 #dbeafe;
    }

    .flatpickr-day.today {
        border-color: #2563eb;
    }

    .flatpickr-day:hover {
        background: #eff6ff;
    }

</style>

<script>

    const staffNames = {!! json_encode($staff->pluck('fullname', 'id')) !!};

    let scheduleDatePicker = null;

    function toISODate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    // Built from local Y/M/D parts (not `new Date(str)`) so the picker
    // never shifts a day off due to timezone parsing.
    function parseISODate(str) {
        const [y, m, d] = str.split('-').map(Number);
        return new Date(y, m - 1, d);
    }

    function initSchedulePicker(mode, initialDateStr) {

        if (scheduleDatePicker) {
            scheduleDatePicker.destroy();
        }

        const isRange = mode !== 'edit';

        document.getElementById('scheduleDate').value = initialDateStr ?? '';
        document.getElementById('scheduleDateEnd').value = '';

        scheduleDatePicker = flatpickr('#scheduleDateRangeInput', {
            mode: isRange ? 'range' : 'single',
            altInput: true,
            altFormat: 'M j, Y',
            dateFormat: 'Y-m-d',
            defaultDate: initialDateStr ? parseISODate(initialDateStr) : null,
            onChange: function (selectedDates) {

                const start = selectedDates[0] ? toISODate(selectedDates[0]) : '';
                const end = (isRange && selectedDates[1]) ? toISODate(selectedDates[1]) : '';

                document.getElementById('scheduleDate').value = start;
                document.getElementById('scheduleDateEnd').value = end;

                if (isRange) {
                    updateRangePreview();
                }
            },
        });
    }

    function openScheduleModal(mode, entryId, personnelId, dateStr, shiftStart, shiftEnd, task, location, notes, jobRequestId, timeIn, timeOut, overtimeReason) {

        document.getElementById('scheduleModalTitle').innerText = mode === 'edit' ? 'Edit Schedule Entry' : 'Add Schedule Entry';
        document.getElementById('scheduleStaffLabel').innerText = 'Staff: ' + (staffNames[personnelId] ?? '');
        document.getElementById('schedulePersonnelId').value = personnelId;
        document.getElementById('scheduleShiftStart').value = shiftStart ?? '';
        document.getElementById('scheduleShiftEnd').value = shiftEnd ?? '';
        document.getElementById('scheduleTask').value = task ?? '';
        document.getElementById('scheduleLocation').value = location ?? '';
        document.getElementById('scheduleNotes').value = notes ?? '';
        document.getElementById('scheduleJobRequest').value = jobRequestId ?? '';
        document.getElementById('scheduleTimeIn').value = timeIn ?? '';
        document.getElementById('scheduleTimeOut').value = timeOut ?? '';
        document.getElementById('scheduleOvertimeReason').value = overtimeReason ?? '';

        const form = document.getElementById('scheduleForm');
        const rangeHint = document.getElementById('scheduleRangeHint');
        const dateLabel = document.getElementById('scheduleDateLabel');
        const attendanceSection = document.getElementById('scheduleAttendanceSection');

        if (mode === 'edit') {
            form.action = '{{ url('/utility-schedule') }}/' + entryId;
            document.getElementById('scheduleFormMethod').value = 'PUT';
            dateLabel.innerText = 'Date';
            rangeHint.classList.add('hidden');
            attendanceSection.classList.remove('hidden');
        } else {
            form.action = '{{ route('utility-schedule.store') }}';
            document.getElementById('scheduleFormMethod').value = 'POST';
            dateLabel.innerText = 'Date (click a day, or drag across days for a range)';
            attendanceSection.classList.add('hidden');
        }

        initSchedulePicker(mode, dateStr);

        if (mode !== 'edit') {
            updateRangePreview();
        }

        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    function closeScheduleModal() {
        document.getElementById('scheduleModal').classList.add('hidden');
    }

    function formatDateForDisplay(dateStr) {
        const d = parseISODate(dateStr);
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    // Live feedback so it's obvious BEFORE saving how many days are about
    // to be created — updates as soon as a second day is clicked.
    function updateRangePreview() {

        const rangeHint = document.getElementById('scheduleRangeHint');
        const startVal = document.getElementById('scheduleDate').value;
        const endVal = document.getElementById('scheduleDateEnd').value;

        if (!startVal) {
            rangeHint.innerText = '';
            return;
        }

        if (!endVal || endVal === startVal) {
            rangeHint.className = 'text-sm font-medium -mt-2 text-gray-600';
            rangeHint.innerText = '📌 Will add 1 entry: ' + formatDateForDisplay(startVal);
            return;
        }

        const start = parseISODate(startVal);
        const end = parseISODate(endVal);
        const dayCount = Math.round((end - start) / 86400000) + 1;

        rangeHint.className = 'text-sm font-medium -mt-2 text-blue-700';
        rangeHint.innerText = '📌 Will add ' + dayCount + ' entries: '
            + formatDateForDisplay(startVal) + ' – ' + formatDateForDisplay(endVal);
    }

    document.getElementById('scheduleForm').addEventListener('submit', function (e) {
        if (!document.getElementById('scheduleDate').value) {
            e.preventDefault();
            alert('Please pick a date on the calendar first.');
        }
    });

</script>

@endsection
