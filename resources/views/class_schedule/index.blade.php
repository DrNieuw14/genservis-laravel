@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🗓 Class Scheduling
            </h2>
            <p class="text-gray-500 mt-1 text-lg">
                Build each section's weekly class schedule.
            </p>
        </div>

        <div class="flex gap-2 flex-wrap">

            <a href="{{ route('class-schedule.rooms') }}" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-4 py-2 rounded-lg font-semibold">
                🏫 Room View
            </a>

            <a href="{{ route('class-schedule.faculty') }}" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-4 py-2 rounded-lg font-semibold">
                🧑‍🏫 Faculty View
            </a>

            @if(auth()->user()->hasPermission('manage-class-schedule'))
            <a href="{{ route('sections.index') }}" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold">
                ⚙ Sections
            </a>
            <a href="{{ route('subjects.index') }}" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold">
                ⚙ Subjects
            </a>
            <a href="{{ route('programs.index') }}" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold">
                ⚙ Programs
            </a>
            @endif

        </div>

    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">{{ session('success') }}</div>
    @endif

    {{-- Conflict errors from Add/Edit Class always reopen inside the modal (see below) instead of a top banner, so the message sits next to the fields the user was just editing. --}}

    <!-- Section Picker -->
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
            <select name="program_id" class="border rounded px-4 py-2" onchange="this.form.submit()">
                <option value="">All Programs</option>
                @foreach($programs as $p)
                <option value="{{ $p->id }}" @selected(request('program_id') == $p->id)>{{ $p->code }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
            <select name="year_level" class="border rounded px-4 py-2" onchange="this.form.submit()">
                <option value="">All Years</option>
                @for ($y = 1; $y <= 6; $y++)
                <option value="{{ $y }}" @selected(request('year_level') == $y)>Year {{ $y }}</option>
                @endfor
            </select>
        </div>

        <div class="flex-1 min-w-[16rem] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">Section (<span id="sectionVisibleCount">{{ $sections->count() }}</span>)</label>

            <input type="text" id="sectionComboBox" autocomplete="off" placeholder="Type to search, e.g. IT"
                class="border rounded px-4 py-2 w-full"
                value="{{ $section ? $section->label . ' — ' . $section->semester . ', ' . $section->school_year : '' }}"
                oninput="filterSectionOptions()" onfocus="showSectionDropdown(); this.select()">

            <input type="hidden" name="section_id" id="sectionIdInput" value="{{ $section->id ?? '' }}">

            <div id="sectionDropdown" class="hidden absolute z-20 bg-white border rounded-lg shadow-lg mt-1 w-full max-h-72 overflow-y-auto">
                @foreach($sections as $s)
                <div class="section-option px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm"
                    data-id="{{ $s->id }}"
                    data-label="{{ $s->label }} — {{ $s->semester }}, {{ $s->school_year }}">
                    {{ $s->label }} — {{ $s->semester }}, {{ $s->school_year }}
                </div>
                @endforeach
            </div>
        </div>

    </form>

    <script>
        function filterSectionOptions()
        {
            const term = document.getElementById('sectionComboBox').value.trim().toLowerCase();
            const options = document.querySelectorAll('#sectionDropdown .section-option');
            let visible = 0;

            options.forEach(function (opt) {
                const match = opt.dataset.label.toLowerCase().includes(term);
                opt.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            document.getElementById('sectionVisibleCount').innerText = visible;
            showSectionDropdown();
        }

        function showSectionDropdown()
        {
            document.getElementById('sectionDropdown').classList.remove('hidden');
        }

        document.querySelectorAll('.section-option').forEach(function (opt) {
            opt.addEventListener('click', function () {
                document.getElementById('sectionIdInput').value = opt.dataset.id;
                document.getElementById('sectionComboBox').value = opt.dataset.label;
                document.getElementById('sectionDropdown').classList.add('hidden');
                opt.closest('form').submit();
            });
        });

        document.getElementById('sectionComboBox').addEventListener('keydown', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            const firstMatch = [...document.querySelectorAll('#sectionDropdown .section-option')]
                .find(function (opt) { return opt.style.display !== 'none'; });
            if (firstMatch) firstMatch.click();
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#sectionComboBox') && !e.target.closest('#sectionDropdown')) {
                document.getElementById('sectionDropdown').classList.add('hidden');
            }
        });
    </script>

    @if($section)

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-800">{{ $section->label }}</h3>

        @if(auth()->user()->hasPermission('manage-class-schedule'))
        <button type="button" onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">
            ➕ Add Class
        </button>
        @endif
    </div>

    <div class="overflow-x-auto border rounded-lg mb-8">

        <table class="w-full border-collapse text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left w-24">Time</th>
                    @foreach(\App\Models\ClassSchedule::DAYS as $day)
                    <th class="p-2 text-center">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($grid as $row)
                <tr class="align-top">
                    <td class="p-2 text-gray-500 whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($row['slot'])->format('g:iA') }}</td>

                    @foreach(\App\Models\ClassSchedule::DAYS as $day)
                        @php $cell = $row['cells'][$day]; @endphp
                        @if($cell === null)
                            {{-- covered by a previous rowspan --}}
                        @elseif($cell['type'] === 'empty')
                            <td class="p-2"></td>
                        @else
                            @php $entry = $cell['entry']; @endphp
                            <td rowspan="{{ $cell['rowspan'] }}" class="p-2 border-l bg-blue-50 align-top">
                                <div class="font-semibold text-blue-900">{{ $entry->subject->code }}</div>
                                <div class="text-xs text-gray-600">{{ $entry->room ?? 'TBA' }}</div>
                                <div class="text-xs text-gray-600">{{ $entry->faculty_label }}</div>

                                @if(auth()->user()->hasPermission('manage-class-schedule'))
                                <div class="mt-1 flex gap-2">
                                    <button type="button" onclick='openEditModal(@json($entry->id), @json($entry->subject_id), @json($entry->personnel_id), @json($entry->room), @json($entry->day_of_week), @json(substr($entry->start_time,0,5)), @json(substr($entry->end_time,0,5)))' class="text-blue-600 hover:underline text-xs">Edit</button>
                                    <form action="{{ route('class-schedule.destroy', $entry->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove this class entry?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs">Remove</button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

    <!-- Subject Load Summary -->
    @if($subjectLoad->isNotEmpty())
    <h3 class="text-lg font-bold text-gray-800 mb-3">Subjects</h3>
    <table class="min-w-full text-sm mb-6">
        <thead>
            <tr class="border-b bg-gray-50 text-left">
                <th class="px-3 py-2">Subject</th>
                <th class="px-3 py-2">Days</th>
                <th class="px-3 py-2 text-right">Lecture</th>
                <th class="px-3 py-2 text-right">Lab</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjectLoad as $row)
            <tr class="border-b">
                <td class="px-3 py-2 font-medium">{{ $row['subject']->code }}</td>
                <td class="px-3 py-2">{{ $row['days'] }}</td>
                <td class="px-3 py-2 text-right">{{ $row['subject']->lecture_units }}</td>
                <td class="px-3 py-2 text-right">{{ $row['subject']->lab_units }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @endif

</div>

<!-- Add/Edit Modal -->
<div id="classModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">

        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 id="classModalTitle" class="text-xl font-bold">Add Class</h3>
            <button type="button" onclick="closeClassModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>

        <form id="classForm" method="POST" action="{{ route('class-schedule.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="classFormMethod" value="POST">
            <input type="hidden" name="section_id" value="{{ $section->id ?? '' }}">
            <input type="hidden" name="editing_id" id="editingIdInput" value="{{ old('editing_id') }}">

            @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded text-sm">{{ session('error') }}</div>
            @endif

            <div>
                <label class="text-sm font-semibold">Subject</label>
                <select name="subject_id" id="subjectSelect" class="w-full border rounded mt-1" required>
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subj)
                    <option value="{{ $subj->id }}">{{ $subj->code }}@if($subj->title) — {{ $subj->title }} @endif</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold">Faculty (optional — leave blank for TBA)</label>
                <select name="personnel_id" id="facultySelect" class="w-full border rounded mt-1" onchange="scheduleAvailabilityCheck()">
                    <option value="">-- TBA --</option>
                    @foreach($faculty as $f)
                    <option value="{{ $f->id }}">{{ $f->fullname }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold">Room (optional)</label>
                <input type="text" name="room" id="roomInput" list="roomOptions" autocomplete="off"
                    placeholder="e.g. S-303, 401, TBA — or type a new room"
                    class="w-full border rounded mt-1" oninput="scheduleAvailabilityCheck()">
                <datalist id="roomOptions">
                    @foreach($rooms as $r)
                    <option value="{{ $r }}"></option>
                    @endforeach
                </datalist>
                <p class="text-xs text-gray-400 mt-1">Suggests rooms already in use — you can still type a brand-new room name.</p>
            </div>

            <div class="grid grid-cols-3 gap-3">

                <div>
                    <label class="text-sm font-semibold">Day</label>
                    <select name="day_of_week" id="dayOfWeekSelect" class="w-full border rounded mt-1" required onchange="scheduleAvailabilityCheck()">
                        @foreach(\App\Models\ClassSchedule::DAYS as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Start</label>
                    <input type="time" name="start_time" id="startTimeInput" class="w-full border rounded mt-1" required oninput="scheduleAvailabilityCheck()">
                </div>

                <div>
                    <label class="text-sm font-semibold">End</label>
                    <input type="time" name="end_time" id="endTimeInput" class="w-full border rounded mt-1" required oninput="scheduleAvailabilityCheck()">
                </div>

            </div>

            <div id="availabilityStatus" class="hidden text-sm rounded-lg px-3 py-2"></div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeClassModal()" class="px-4 py-2 border rounded">Cancel</button>
                <button type="submit" id="classSubmitButton" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">Save</button>
            </div>

        </form>

    </div>

</div>

<script>

    function openAddModal()
    {
        document.getElementById('classForm').reset();
        document.getElementById('editingIdInput').value = '';
        document.getElementById('classForm').action = "{{ route('class-schedule.store') }}";
        document.getElementById('classFormMethod').value = 'POST';
        document.getElementById('classModalTitle').innerText = 'Add Class';
        document.getElementById('classModal').classList.remove('hidden');
    }

    function openEditModal(id, subjectId, personnelId, room, day, startTime, endTime)
    {
        document.getElementById('classForm').reset();
        document.getElementById('editingIdInput').value = id;
        document.getElementById('subjectSelect').value = subjectId;
        document.getElementById('facultySelect').value = personnelId ?? '';
        document.getElementById('roomInput').value = room ?? '';
        document.getElementById('dayOfWeekSelect').value = day;
        document.getElementById('startTimeInput').value = startTime;
        document.getElementById('endTimeInput').value = endTime;

        document.getElementById('classForm').action = "{{ url('class-schedule') }}/" + id;
        document.getElementById('classFormMethod').value = 'PUT';
        document.getElementById('classModalTitle').innerText = 'Edit Class';
        document.getElementById('classModal').classList.remove('hidden');
        checkAvailability();
    }

    function closeClassModal()
    {
        document.getElementById('classModal').classList.add('hidden');
    }

    let availabilityTimer = null;

    function scheduleAvailabilityCheck()
    {
        clearTimeout(availabilityTimer);
        availabilityTimer = setTimeout(checkAvailability, 300);
    }

    function checkAvailability()
    {
        const day = document.getElementById('dayOfWeekSelect').value;
        const start = document.getElementById('startTimeInput').value;
        const end = document.getElementById('endTimeInput').value;
        const statusEl = document.getElementById('availabilityStatus');

        if (!day || !start || !end || end <= start) {
            statusEl.classList.add('hidden');
            return;
        }

        const params = new URLSearchParams({ day_of_week: day, start_time: start, end_time: end });

        const room = document.getElementById('roomInput').value.trim();
        if (room) params.set('room', room);

        const personnelId = document.getElementById('facultySelect').value;
        if (personnelId) params.set('personnel_id', personnelId);

        const sectionId = document.querySelector('input[name="section_id"]').value;
        if (sectionId) params.set('section_id', sectionId);

        const editingId = document.getElementById('editingIdInput').value;
        if (editingId) params.set('ignore_id', editingId);

        fetch("{{ route('class-schedule.check-availability') }}?" + params.toString())
            .then(function (r) { return r.json(); })
            .then(function (data) {
                statusEl.classList.remove('hidden');

                if (data.clear) {
                    statusEl.className = 'text-sm rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200';
                    statusEl.innerText = '✓ Room and faculty are clear at this day/time.';
                    return;
                }

                statusEl.className = 'text-sm rounded-lg px-3 py-2 bg-amber-50 text-amber-800 border border-amber-200';
                statusEl.innerHTML = '⚠ Already booked:<br>' + data.conflicts.map(function (c) {
                    return '• ' + c.section + ' / ' + c.subject + ' (' + c.time + ')'
                        + (c.room ? ' in room ' + c.room : '')
                        + (c.faculty ? ' with ' + c.faculty : '');
                }).join('<br>');
            })
            .catch(function () { statusEl.classList.add('hidden'); });
    }

    @if(session('error'))
    // A conflict rejected the last Add/Edit Class submission — reopen the
    // modal with the same inputs (via old()) and the error already visible,
    // instead of leaving the user to notice a banner and start over.
    (function () {
        const editingId = @json(old('editing_id'));

        if (editingId) {
            openEditModal(
                editingId,
                @json(old('subject_id')),
                @json(old('personnel_id')),
                @json(old('room')),
                @json(old('day_of_week')),
                @json(old('start_time')),
                @json(old('end_time'))
            );
        } else {
            openAddModal();
            document.getElementById('subjectSelect').value = @json(old('subject_id'));
            document.getElementById('facultySelect').value = @json(old('personnel_id'));
            document.getElementById('roomInput').value = @json(old('room'));
            document.getElementById('dayOfWeekSelect').value = @json(old('day_of_week'));
            document.getElementById('startTimeInput').value = @json(old('start_time'));
            document.getElementById('endTimeInput').value = @json(old('end_time'));
        }

        checkAvailability();
    })();
    @endif

</script>

@endsection
