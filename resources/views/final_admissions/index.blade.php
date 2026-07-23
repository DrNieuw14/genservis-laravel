@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📋 Final List of Admission
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                The official, staff-curated list of who's finally admitted per program — search and add manually.
            </p>
        </div>

        @if($selectedYear)
            <a href="{{ route('final-admissions.print', $selectedYear->id) }}{{ $programCode ? '?program='.$programCode : '' }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded whitespace-nowrap">
                🖨 {{ $programCode ? "Print {$programCode} List" : 'Print Full List' }}
            </a>
        @endif

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}

            @if(session('moveCandidate'))
                <form method="POST" action="{{ route('final-admissions.move', session('moveCandidate')) }}" class="inline mt-2">
                    @csrf
                    <input type="hidden" name="program_code" value="{{ session('moveTargetProgram') }}">
                    <button type="submit" class="bg-white text-red-700 font-semibold px-3 py-1 rounded ml-2">
                        Move to {{ session('moveTargetProgram') }} instead
                    </button>
                </form>
            @endif
        </div>
    @endif

    @if(!$selectedYear)

        <p class="text-gray-500 text-center py-10">No admission years yet — create one under Admission Years first.</p>

    @else

        <form method="GET" class="mb-6 flex items-center gap-3">
            <label class="font-semibold text-sm whitespace-nowrap">Admission Year:</label>
            <select name="admission_year_id" onchange="this.form.submit()" class="border rounded-lg p-2">
                @foreach($years as $year)
                    <option value="{{ $year->id }}" @selected($selectedYear->id === $year->id)>{{ $year->label }}</option>
                @endforeach
            </select>

            <label class="font-semibold text-sm whitespace-nowrap ml-4">Program:</label>
            <select name="program" onchange="this.form.submit()" class="border rounded-lg p-2">
                <option value="">🔵 All Programs</option>
                @foreach($programOptions as $fullName => $code)
                    <option value="{{ $code }}" @selected($programCode === $code)>{{ $code }} — {{ $fullName }}</option>
                @endforeach
            </select>
        </form>

        <!-- SEARCH & ADD — works from "All Programs" too, not just once a
             specific program is selected. Each result gets its own program
             picker next to Add/Move, defaulting to whichever program is
             currently selected (if any) for convenience. -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 mb-6">

            <h3 class="text-lg font-semibold text-blue-800 mb-3">
                🔍 Search Applicant Roster
                @if($programCode && $quotaStatus)
                    <span class="text-sm font-normal">({{ $finalList->count() }} of {{ $quotaStatus['quota'] }} slots filled for {{ $programCode }})</span>
                @endif
            </h3>

            <form method="GET" class="flex flex-col md:flex-row gap-3 mb-2">
                <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
                @if($programCode)<input type="hidden" name="program" value="{{ $programCode }}">@endif

                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or control number"
                    class="w-full border rounded-lg p-3">

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
                    🔍 Search
                </button>
            </form>

            @if($search)

                <div class="overflow-x-auto border rounded-lg bg-white mt-3">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Control No.</th>
                                <th class="p-3 text-left">Name</th>
                                <th class="p-3 text-left">Program Applied For</th>
                                <th class="p-3 text-center">Score / Grade</th>
                                <th class="p-3 text-center">Status / Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($searchResults as $applicant)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3">{{ $applicant->control_number }}</td>
                                    <td class="p-3 font-semibold">{{ $applicant->fullName() }}</td>
                                    <td class="p-3">{{ $applicant->program }}</td>
                                    <td class="p-3 text-center">
                                        @if($applicant->examGrade !== null)
                                            {{ $applicant->examScore }} / {{ $applicant->examGrade }} ({{ $applicant->examProgramCode }})
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        @if($applicant->finalAdmission)

                                            <div class="flex flex-col items-center gap-1">
                                                <span class="text-yellow-700 text-xs">
                                                    Finalized to {{ $applicant->finalAdmission->program_code }}
                                                </span>

                                                <form method="POST" action="{{ route('final-admissions.move', $applicant->finalAdmission->id) }}"
                                                      class="flex items-center gap-1"
                                                      onsubmit="return confirmSubmit(this, 'Move Applicant?', 'Move this applicant to the selected program?', 'Yes, Move', '#d97706')">
                                                    @csrf
                                                    <select name="program_code" class="border rounded p-1 text-xs" required>
                                                        <option value="">Move to...</option>
                                                        @foreach($programOptions as $fullName => $code)
                                                            @if($code !== $applicant->finalAdmission->program_code)
                                                                <option value="{{ $code }}" @selected($programCode === $code)>{{ $code }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-2 py-1 rounded text-xs">
                                                        Move
                                                    </button>
                                                </form>
                                            </div>

                                        @else

                                            <form method="POST" action="{{ route('final-admissions.store') }}" class="flex items-center gap-1 justify-center">
                                                @csrf
                                                <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
                                                <input type="hidden" name="admission_applicant_id" value="{{ $applicant->id }}">
                                                <select name="program_code" class="border rounded p-1 text-xs" required>
                                                    <option value="">-- Program --</option>
                                                    @foreach($programOptions as $fullName => $code)
                                                        <option value="{{ $code }}" @selected($programCode === $code)>{{ $code }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                                    ➕ Add
                                                </button>
                                            </form>

                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-500">No matching applicants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @endif

        </div>

        @if(!$programCode)

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($programCounts as $p)
                    <a href="{{ route('final-admissions.index', ['admission_year_id' => $selectedYear->id, 'program' => $p['code']]) }}"
                       class="border rounded-lg p-5 hover:shadow-md hover:border-green-400 transition block">
                        <p class="text-xl font-bold text-gray-800">{{ $p['code'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $p['program'] }}</p>
                        <p class="text-2xl font-semibold text-green-700 mt-3">{{ $p['count'] }}</p>
                        <p class="text-xs text-gray-400">finalized</p>
                    </a>
                @empty
                    <p class="text-gray-500 col-span-3 text-center py-10">
                        No one finalized yet. Search above to start adding students, or select a specific program for more tools (quota, who passed).
                    </p>
                @endforelse
            </div>

        @else

            <!-- WHO PASSED (PROGRAM RANKINGS CUTOFF) -->
            <div class="border border-green-200 bg-green-50 rounded-lg p-5 mb-6">

                <h3 class="text-lg font-semibold text-green-800 mb-3">
                    ✅ Who Passed — {{ $programCode }}
                    @if($quotaStatus)
                        <span class="text-sm font-normal">(Quota: {{ $quotaStatus['quota'] }} — {{ $passedList->count() }} passed per cutoff)</span>
                    @endif
                </h3>

                @if(!$quotaStatus)

                    <p class="text-sm text-green-700">
                        No capacity set yet for {{ $programCode }} — set Sections × Students per Section on
                        <a href="{{ route('program-rankings.show', [$selectedYear->id, $programCode]) }}" class="underline">that program's ranking page</a>
                        to see who passed here.
                    </p>

                @else

                    @php $addableCount = $passedList->filter(fn($r) => !$r->alreadyFinalizedHere && !$r->alreadyFinalizedElsewhere && $r->admission_applicant_id)->count(); @endphp

                    <form method="POST" action="{{ route('final-admissions.bulk-store') }}" id="bulkAddForm">
                        @csrf
                        <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
                        <input type="hidden" name="program_code" value="{{ $programCode }}">

                        <div class="flex items-center justify-between mb-2">
                            <label class="flex items-center gap-2 text-sm font-semibold text-green-800">
                                <input type="checkbox" id="selectAll-passedCb" onclick="toggleAllCheckboxes(this, 'passedCb')" {{ $addableCount === 0 ? 'disabled' : '' }}>
                                Select All ({{ $addableCount }} addable)
                            </label>

                            <button type="submit" id="addBtn-passedCb" disabled
                                    class="bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-4 py-2 rounded text-sm"
                                    onclick="return confirmSubmit(this.form, 'Add Selected Applicants?', 'Add all checked applicants to the {{ $programCode }} final list?', 'Yes, Add', '#16a34a')">
                                ➕ Add Selected to Final List
                            </button>
                        </div>

                        <div class="overflow-x-auto border rounded-lg bg-white">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-3 text-center"></th>
                                        <th class="p-3 text-center">Rank</th>
                                        <th class="p-3 text-left">Control No.</th>
                                        <th class="p-3 text-left">Name</th>
                                        <th class="p-3 text-center">Score / Grade</th>
                                        <th class="p-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse($passedList as $i => $ranking)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-3 text-center">
                                                @if(!$ranking->alreadyFinalizedHere && !$ranking->alreadyFinalizedElsewhere && $ranking->admission_applicant_id)
                                                    <input type="checkbox" name="admission_applicant_ids[]" value="{{ $ranking->admission_applicant_id }}"
                                                           class="passedCb" onclick="updateBulkState('passedCb')">
                                                @endif
                                            </td>
                                            <td class="p-3 text-center">{{ $i + 1 }}</td>
                                            <td class="p-3">{{ $ranking->code ?? '-' }}</td>
                                            <td class="p-3 font-semibold">{{ $ranking->examinee_name }}</td>
                                            <td class="p-3 text-center">{{ $ranking->raw_score }} / {{ $ranking->grade }}</td>
                                            <td class="p-3 text-center">
                                                @if($ranking->alreadyFinalizedHere)
                                                    <span class="text-green-700 font-semibold">✅ On Final List</span>
                                                @elseif($ranking->alreadyFinalizedElsewhere)
                                                    <span class="text-yellow-700 text-xs">Finalized elsewhere</span>
                                                @elseif($ranking->admission_applicant_id)
                                                    <button type="submit" form="singleAdd-{{ $ranking->admission_applicant_id }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                                        ➕ Add
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 text-xs" title="No matching roster record">Not in roster</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-6 text-center text-gray-500">No one has passed yet for this program.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    @foreach($passedList as $ranking)
                        @if(!$ranking->alreadyFinalizedHere && !$ranking->alreadyFinalizedElsewhere && $ranking->admission_applicant_id)
                            <form method="POST" action="{{ route('final-admissions.store') }}" id="singleAdd-{{ $ranking->admission_applicant_id }}" class="hidden">
                                @csrf
                                <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
                                <input type="hidden" name="admission_applicant_id" value="{{ $ranking->admission_applicant_id }}">
                                <input type="hidden" name="program_code" value="{{ $programCode }}">
                            </form>
                        @endif
                    @endforeach

                @endif

            </div>

            <!-- CURRENT FINAL LIST -->
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-xl font-semibold text-gray-800">Final List — {{ $programCode }} ({{ $finalList->count() }})</h3>
                <div class="flex items-center gap-3">
                    <a href="{{ route('final-admissions.index', ['admission_year_id' => $selectedYear->id]) }}" class="text-sm text-blue-600 hover:underline">
                        ← Back to all programs
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Control No.</th>
                            <th class="p-3 text-left">Name</th>
                            <th class="p-3 text-center">Score / Grade</th>
                            <th class="p-3 text-center">Source</th>
                            <th class="p-3 text-left">Added By</th>
                            <th class="p-3 text-center">Date Added</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($finalList as $entry)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3">{{ $entry->applicant->control_number ?? '-' }}</td>
                                <td class="p-3 font-semibold">{{ $entry->applicant->fullName() ?? '-' }}</td>
                                <td class="p-3 text-center">
                                    @if($entry->examGrade !== null)
                                        {{ $entry->examScore }} / {{ $entry->examGrade }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    @if($entry->fromReapplication)
                                        <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-1 rounded-full">🕐 Waiting List</span>
                                    @else
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">✅ Who Passed</span>
                                    @endif
                                </td>
                                <td class="p-3">{{ $entry->addedBy->name ?? '-' }}</td>
                                <td class="p-3 text-center">{{ $entry->created_at->format('M d, Y') }}</td>
                                <td class="p-3 text-center">
                                    <form method="POST" action="{{ route('final-admissions.destroy', $entry->id) }}"
                                          onsubmit="return confirmSubmit(this, 'Remove Applicant?', 'Remove this applicant from the {{ $programCode }} final list?', 'Yes, Remove', '#dc2626')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">🗑 Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-500">
                                    No one finalized yet for {{ $programCode }} — search above to add someone.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- REAPPLICANTS WAITING LIST -->
            <div class="border border-orange-200 bg-orange-50 rounded-lg p-5 mt-6">

                <h3 class="text-lg font-semibold text-orange-800 mb-1">
                    🕐 Reapplicants Waiting List — {{ $programCode }}
                </h3>
                <p class="text-sm text-orange-700 mb-3">
                    Students who didn't make their original program's cutoff and picked {{ $programCode }} as their
                    1st or 2nd choice on the Reapplication Form. They're waiting here, not automatically admitted —
                    add them to the Final List only once you've decided.
                </p>

                <form method="GET" class="flex flex-col md:flex-row gap-3 mb-4">
                    <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
                    <input type="hidden" name="program" value="{{ $programCode }}">

                    <input type="text" name="reapp_search" value="{{ $reappSearch }}" placeholder="Search this list by name or control number"
                        class="w-full border rounded-lg p-3">

                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
                        🔍 Search
                    </button>

                    @if($reappSearch)
                        <a href="{{ route('final-admissions.index', ['admission_year_id' => $selectedYear->id, 'program' => $programCode]) }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg shadow text-center">
                            Clear
                        </a>
                    @endif
                </form>

                @php $reappAddableCount = $reapplicantsList->filter(fn($r) => !$r->alreadyFinalizedHere && !$r->alreadyFinalizedElsewhere && $r->admission_applicant_id)->count(); @endphp

                <form method="POST" action="{{ route('final-admissions.bulk-store') }}" id="bulkAddFormReapp">
                    @csrf
                    <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
                    <input type="hidden" name="program_code" value="{{ $programCode }}">

                    <div class="flex items-center justify-between mb-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-orange-800">
                            <input type="checkbox" id="selectAll-reappCb" onclick="toggleAllCheckboxes(this, 'reappCb')" {{ $reappAddableCount === 0 ? 'disabled' : '' }}>
                            Select All ({{ $reappAddableCount }} addable)
                        </label>

                        <button type="submit" id="addBtn-reappCb" disabled
                                class="bg-orange-600 hover:bg-orange-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-4 py-2 rounded text-sm"
                                onclick="return confirmSubmit(this.form, 'Add Selected Reapplicants?', 'Add all checked reapplicants to the {{ $programCode }} final list?', 'Yes, Add', '#ea580c')">
                            ➕ Add Selected to Final List
                        </button>
                    </div>

                    <div class="overflow-x-auto border rounded-lg bg-white">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-center"></th>
                                    <th class="p-3 text-left">Control No.</th>
                                    <th class="p-3 text-left">Name</th>
                                    <th class="p-3 text-left">1st Choice</th>
                                    <th class="p-3 text-left">2nd Choice</th>
                                    <th class="p-3 text-center">Score / Grade</th>
                                    <th class="p-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($reapplicantsList as $reapp)
                                    <tr class="hover:bg-gray-50 {{ $reapp->is_duplicate ? 'bg-yellow-50' : '' }}">
                                        <td class="p-3 text-center">
                                            @if(!$reapp->alreadyFinalizedHere && !$reapp->alreadyFinalizedElsewhere && $reapp->admission_applicant_id)
                                                <input type="checkbox" name="admission_applicant_ids[]" value="{{ $reapp->admission_applicant_id }}"
                                                       class="reappCb" onclick="updateBulkState('reappCb')">
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            {{ $reapp->control_number ?? '-' }}
                                            @if($reapp->is_duplicate)
                                                <span class="text-yellow-600 text-xs" title="Duplicate submission">⚠</span>
                                            @endif
                                        </td>
                                        <td class="p-3 font-semibold">{{ $reapp->fullName() }}</td>
                                        <td class="p-3">{{ $reapp->first_choice ?? '-' }}</td>
                                        <td class="p-3">{{ $reapp->second_choice ?? '-' }}</td>
                                        <td class="p-3 text-center">
                                            @if($reapp->examGrade !== null)
                                                {{ $reapp->examScore }} / {{ $reapp->examGrade }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center">
                                            @if($reapp->alreadyFinalizedHere)
                                                <span class="text-green-700 font-semibold">✅ On Final List</span>
                                            @elseif($reapp->alreadyFinalizedElsewhere)
                                                <span class="text-yellow-700 text-xs">Finalized elsewhere</span>
                                            @elseif($reapp->admission_applicant_id)
                                                <button type="submit" form="reappSingleAdd-{{ $reapp->admission_applicant_id }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                                    ➕ Add
                                                </button>
                                            @else
                                                <span class="text-gray-400 text-xs" title="No matching roster record">Not in roster</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-6 text-center text-gray-500">
                                            @if($reappSearch)
                                                No reapplicants matching "{{ $reappSearch }}" for {{ $programCode }}.
                                            @else
                                                No reapplicants have picked {{ $programCode }} as a choice yet.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                @foreach($reapplicantsList as $reapp)
                    @if(!$reapp->alreadyFinalizedHere && !$reapp->alreadyFinalizedElsewhere && $reapp->admission_applicant_id)
                        <form method="POST" action="{{ route('final-admissions.store') }}" id="reappSingleAdd-{{ $reapp->admission_applicant_id }}" class="hidden">
                            @csrf
                            <input type="hidden" name="admission_year_id" value="{{ $selectedYear->id }}">
                            <input type="hidden" name="admission_applicant_id" value="{{ $reapp->admission_applicant_id }}">
                            <input type="hidden" name="program_code" value="{{ $programCode }}">
                        </form>
                    @endif
                @endforeach

            </div>

        @endif

    @endif

</div>

<script>
    // Generic bulk-select wiring — shared by "Who Passed" (checkboxClass
    // 'passedCb') and "Reapplicants Waiting List" (checkboxClass 'reappCb'),
    // since both are the same "select some rows, bulk-add them" pattern.
    // Expects #selectAll-{checkboxClass} and #addBtn-{checkboxClass} to exist.
    function toggleAllCheckboxes(masterCheckbox, checkboxClass) {
        document.querySelectorAll('.' + checkboxClass).forEach(cb => cb.checked = masterCheckbox.checked);
        updateBulkState(checkboxClass);
    }

    function updateBulkState(checkboxClass) {
        const boxes = document.querySelectorAll('.' + checkboxClass);
        const checkedCount = document.querySelectorAll('.' + checkboxClass + ':checked').length;

        const addBtn = document.getElementById('addBtn-' + checkboxClass);
        if (addBtn) {
            addBtn.disabled = checkedCount === 0;
        }

        const selectAll = document.getElementById('selectAll-' + checkboxClass);
        if (selectAll) {
            selectAll.checked = boxes.length > 0 && checkedCount === boxes.length;
        }
    }

    // Shared SweetAlert2 confirmation for every destructive/bulk action on
    // this page (Move, Remove, bulk Add) — replaces the plain browser
    // confirm() popup with the same styled dialog used everywhere else in
    // this app (see Materials Inventory's "Delete Material?" for the
    // original pattern). Always returns false so the native onsubmit/
    // onclick is blocked immediately; form.submit() inside the .then()
    // bypasses the onsubmit handler entirely (per the DOM spec), so there's
    // no risk of looping back into this same confirmation a second time.
    function confirmSubmit(form, title, text, confirmText, confirmColor) {
        Swal.fire({
            title: title,
            text: text,
            icon: confirmColor === '#dc2626' ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmText,
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }
</script>

@endsection
