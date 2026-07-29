@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧑‍🏫 Faculty Schedule
            </h2>
            <p class="text-gray-500 mt-1 text-lg">Read-only grid, derived from the section schedules — plus profile fields for linked employee records.</p>
        </div>

        <x-back-button :href="route('class-schedule.index')" />

    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-2 flex flex-wrap gap-4 items-end">

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
            <label class="block text-sm font-medium text-gray-700 mb-1">Faculty (<span id="facultyVisibleCount">{{ $facultyList->count() }}</span>)</label>

            <input type="text" id="facultyComboBox" autocomplete="off" placeholder="Type to search, e.g. DALISAY"
                class="border rounded px-4 py-2 w-full"
                value="{{ $facultyList->firstWhere('key', $key)['label'] ?? '' }}"
                oninput="filterFacultyOptions()" onfocus="showFacultyDropdown(); this.select()">

            <input type="hidden" name="faculty" id="facultyKeyInput" value="{{ $key }}">

            <div id="facultyDropdown" class="hidden absolute z-20 bg-white border rounded-lg shadow-lg mt-1 w-full max-h-72 overflow-y-auto">
                @foreach($facultyList as $f)
                <div class="faculty-option px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm"
                    data-key="{{ $f['key'] }}"
                    data-label="{{ $f['label'] }}">
                    {{ $f['label'] }}
                </div>
                @endforeach
            </div>
        </div>

    </form>

    <p class="text-xs text-gray-400 mb-6">"(unlinked)" entries are transcribed from the real schedule but have no matching employee record yet — no profile fields until linked. Program/Year Level filter which faculty appear, based on who currently has a class in a matching section.</p>

    <script>
        function filterFacultyOptions()
        {
            const term = document.getElementById('facultyComboBox').value.trim().toLowerCase();
            const options = document.querySelectorAll('#facultyDropdown .faculty-option');
            let visible = 0;

            options.forEach(function (opt) {
                const match = opt.dataset.label.toLowerCase().includes(term);
                opt.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            document.getElementById('facultyVisibleCount').innerText = visible;
            showFacultyDropdown();
        }

        function showFacultyDropdown()
        {
            document.getElementById('facultyDropdown').classList.remove('hidden');
        }

        document.querySelectorAll('.faculty-option').forEach(function (opt) {
            opt.addEventListener('click', function () {
                document.getElementById('facultyKeyInput').value = opt.dataset.key;
                document.getElementById('facultyComboBox').value = opt.dataset.label;
                document.getElementById('facultyDropdown').classList.add('hidden');
                opt.closest('form').submit();
            });
        });

        document.getElementById('facultyComboBox').addEventListener('keydown', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            const firstMatch = [...document.querySelectorAll('#facultyDropdown .faculty-option')]
                .find(function (opt) { return opt.style.display !== 'none'; });
            if (firstMatch) firstMatch.click();
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#facultyComboBox') && !e.target.closest('#facultyDropdown')) {
                document.getElementById('facultyDropdown').classList.add('hidden');
            }
        });
    </script>

    @if($grid)

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">No. of Preparations</p>
            <p class="text-xl font-bold text-gray-800">{{ $numberOfPreparations }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Contact Hours / Week</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalContactHours }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Highest Educational Attainment</p>
            <p class="text-lg font-semibold text-gray-800">{{ $profile->highest_educational_attainment ?? '—' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Designation</p>
            <p class="text-lg font-semibold text-gray-800">{{ $profile->designation ?? '—' }}</p>
        </div>
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
                        @elseif($cell['type'] === 'empty')
                            <td class="p-2"></td>
                        @else
                            @php $entry = $cell['entry']; @endphp
                            <td rowspan="{{ $cell['rowspan'] }}" class="p-2 border-l bg-amber-50 align-top">
                                <div class="font-semibold text-amber-900">{{ $entry->subject->code }}</div>
                                <div class="text-xs text-gray-600">{{ $entry->section->label }}</div>
                                <div class="text-xs text-gray-600">{{ $entry->room ?? 'TBA' }}</div>
                            </td>
                        @endif
                    @endforeach
                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

    <!-- Subject Load -->
    @if($subjectLoad->isNotEmpty())
    <h3 class="text-lg font-bold text-gray-800 mb-3">Subject / Course / Section Load</h3>
    <table class="min-w-full text-sm mb-8">
        <thead>
            <tr class="border-b bg-gray-50 text-left">
                <th class="px-3 py-2">Subject</th>
                <th class="px-3 py-2">Course / Yr / Section</th>
                <th class="px-3 py-2 text-right">Lecture</th>
                <th class="px-3 py-2 text-right">Laboratory</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjectLoad as $row)
            <tr class="border-b">
                <td class="px-3 py-2 font-medium">{{ $row['subject']->code }}</td>
                <td class="px-3 py-2">{{ $row['section']->label }}</td>
                <td class="px-3 py-2 text-right">{{ $row['subject']->lecture_units }}</td>
                <td class="px-3 py-2 text-right">{{ $row['subject']->lab_units }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Profile fields — only for a linked employee record -->
    @if($personnel && auth()->user()->hasPermission('manage-class-schedule'))

    <h3 class="text-lg font-bold text-gray-800 mb-3">Faculty Profile</h3>

    <form method="POST" action="{{ route('class-schedule.faculty.profile.update', $personnel->id) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-semibold">Highest Educational Attainment</label>
            <input type="text" name="highest_educational_attainment" value="{{ old('highest_educational_attainment', $profile->highest_educational_attainment) }}" class="w-full border rounded mt-1">
        </div>

        <div>
            <label class="text-sm font-semibold">Designation</label>
            <input type="text" name="designation" value="{{ old('designation', $profile->designation) }}" class="w-full border rounded mt-1">
        </div>

        <div>
            <label class="text-sm font-semibold">Consultation Schedule</label>
            <textarea name="consultation_schedule" rows="2" class="w-full border rounded mt-1">{{ old('consultation_schedule', $profile->consultation_schedule) }}</textarea>
        </div>

        <div>
            <label class="text-sm font-semibold">Research</label>
            <textarea name="research" rows="2" class="w-full border rounded mt-1">{{ old('research', $profile->research) }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label class="text-sm font-semibold">Extension</label>
            <textarea name="extension" rows="2" class="w-full border rounded mt-1">{{ old('extension', $profile->extension) }}</textarea>
        </div>

        <div class="md:col-span-2">
            <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-2 rounded-xl shadow-lg font-semibold">
                Save Profile
            </button>
        </div>

    </form>

    @elseif(!$personnel && auth()->user()->hasPermission('manage-class-schedule'))

    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">

        <p class="text-sm text-amber-800 mb-3">
            ⚠ "{{ $facultyLabel }}" isn't linked to an employee record yet, so profile fields aren't available. If you know who this is, link it below — every schedule entry currently under this name will move to that employee's record.
        </p>

        <form method="POST" action="{{ route('class-schedule.faculty.link') }}" class="flex flex-wrap gap-3 items-end">
            @csrf
            <input type="hidden" name="faculty_name" value="{{ $facultyLabel }}">

            <div class="flex-1 min-w-[16rem]">
                <label class="text-sm font-semibold">This is actually...</label>
                <select name="personnel_id" class="w-full border rounded mt-1" required>
                    <option value="">-- Select Employee --</option>
                    @foreach($allPersonnel as $p)
                    <option value="{{ $p->id }}">{{ $p->fullname }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-lg font-semibold">
                Link
            </button>

        </form>

    </div>

    @endif

    @endif

</div>

@endsection
