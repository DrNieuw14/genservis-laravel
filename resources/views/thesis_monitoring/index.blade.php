@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🎓 Thesis Monitoring
            </h2>
            <p class="text-gray-500 mt-1 text-lg">Your advisees, current manuscript status, and how long it's been since the last hand-off.</p>
        </div>

        <button type="button" onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold whitespace-nowrap">
            ➕ Add Advisee
        </button>

    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">{{ session('success') }}</div>
    @endif

    @if($advisees->isEmpty())

    <p class="text-gray-500 text-center py-12">No advisees yet — click "Add Advisee" to start tracking a thesis.</p>

    @else

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="p-3">Advisee(s)</th>
                    <th class="p-3">Program / Year</th>
                    <th class="p-3">Thesis Title</th>
                    <th class="p-3">Current Stage</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Last Movement</th>
                    <th class="p-3"></th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($advisees as $advisee)
                @php
                    $last = $advisee->latestMovement();
                    $days = $advisee->daysSinceLastMovement();
                    $holder = $advisee->currentHolder();

                    $badgeClass = match(true) {
                        $holder === 'With Adviser' => 'bg-blue-100 text-blue-700',
                        $holder === 'With Student' => 'bg-purple-100 text-purple-700',
                        default => 'bg-gray-100 text-gray-500',
                    };

                    $daysClass = match(true) {
                        is_null($days) => 'text-gray-400',
                        $days <= 3 => 'text-green-600',
                        $days <= 7 => 'text-amber-600 font-semibold',
                        default => 'text-red-600 font-bold',
                    };
                @endphp
                <tr class="align-top hover:bg-gray-50">
                    <td class="p-3 font-semibold">
                        <a href="{{ route('thesis-monitoring.show', $advisee->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $advisee->student_names_label }}</a>
                        @if($advisee->members->count() > 1)
                        <span class="block text-xs font-normal text-gray-400">{{ $advisee->members->count() }} members</span>
                        @endif
                    </td>
                    <td class="p-3 text-gray-600">{{ trim(($advisee->program ?? '') . ' ' . ($advisee->year_level ?? '')) ?: '—' }}</td>
                    <td class="p-3 text-gray-600 max-w-xs truncate" title="{{ $advisee->thesis_title }}">{{ $advisee->thesis_title }}</td>
                    <td class="p-3 text-gray-600">{{ $last->chapter_stage ?? '—' }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">{{ $holder ?? 'Not started' }}</span>
                    </td>
                    <td class="p-3 {{ $daysClass }}">
                        @if(is_null($days))
                            —
                        @else
                            {{ $days }} day{{ $days === 1 ? '' : 's' }} ago
                        @endif
                    </td>
                    <td class="p-3 text-right whitespace-nowrap">
                        <button type="button" onclick='openEditModal(@json($advisee->id), @json($advisee->members->pluck("student_name")), @json($advisee->program), @json($advisee->year_level), @json($advisee->thesis_title))' class="text-blue-600 hover:underline text-xs mr-2">Edit</button>
                        <form action="{{ route('thesis-monitoring.destroy', $advisee->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove this advisee and their entire movement log?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

    @endif

</div>

<!-- Add/Edit Advisee Modal -->
<div id="adviseeModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">

        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 id="adviseeModalTitle" class="text-xl font-bold">Add Advisee</h3>
            <button type="button" onclick="closeAdviseeModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>

        <form id="adviseeForm" method="POST" action="{{ route('thesis-monitoring.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="adviseeFormMethod" value="POST">

            <div>
                <label class="text-sm font-semibold">Advisee(s) — add more for a group thesis (up to 6)</label>
                <div id="memberInputsContainer" class="space-y-2 mt-1"></div>
                <button type="button" onclick="addMemberInput()" class="text-blue-600 hover:underline text-sm mt-2">+ Add another member</button>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-semibold">Program</label>
                    <input type="text" name="program" id="programInput" placeholder="e.g. BSIT" class="w-full border rounded mt-1">
                </div>
                <div>
                    <label class="text-sm font-semibold">Year / Section</label>
                    <input type="text" name="year_level" id="yearLevelInput" placeholder="e.g. 4A" class="w-full border rounded mt-1">
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold">Thesis Title</label>
                <textarea name="thesis_title" id="thesisTitleInput" rows="3" class="w-full border rounded mt-1" required></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAdviseeModal()" class="px-4 py-2 border rounded">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">Save</button>
            </div>

        </form>

    </div>

</div>

<script>

    function addMemberInput(value)
    {
        const container = document.getElementById('memberInputsContainer');
        if (container.children.length >= 6) return;

        const row = document.createElement('div');
        row.className = 'flex gap-2';
        row.innerHTML = '<input type="text" name="student_names[]" placeholder="Student name" class="w-full border rounded" required>'
            + '<button type="button" onclick="removeMemberInput(this)" class="text-red-600 px-2">✕</button>';
        row.querySelector('input').value = value ?? '';
        container.appendChild(row);
    }

    function removeMemberInput(btn)
    {
        const container = document.getElementById('memberInputsContainer');
        if (container.children.length <= 1) return;
        btn.closest('div').remove();
    }

    function resetMemberInputs(names)
    {
        const container = document.getElementById('memberInputsContainer');
        container.innerHTML = '';
        (names && names.length ? names : ['']).forEach(name => addMemberInput(name));
    }

    function openAddModal()
    {
        document.getElementById('adviseeForm').reset();
        resetMemberInputs(['']);
        document.getElementById('adviseeForm').action = "{{ route('thesis-monitoring.store') }}";
        document.getElementById('adviseeFormMethod').value = 'POST';
        document.getElementById('adviseeModalTitle').innerText = 'Add Advisee';
        document.getElementById('adviseeModal').classList.remove('hidden');
    }

    function openEditModal(id, studentNames, program, yearLevel, thesisTitle)
    {
        document.getElementById('adviseeForm').reset();
        resetMemberInputs(studentNames);
        document.getElementById('programInput').value = program ?? '';
        document.getElementById('yearLevelInput').value = yearLevel ?? '';
        document.getElementById('thesisTitleInput').value = thesisTitle;

        document.getElementById('adviseeForm').action = "{{ url('thesis-monitoring') }}/" + id;
        document.getElementById('adviseeFormMethod').value = 'PUT';
        document.getElementById('adviseeModalTitle').innerText = 'Edit Advisee';
        document.getElementById('adviseeModal').classList.remove('hidden');
    }

    function closeAdviseeModal()
    {
        document.getElementById('adviseeModal').classList.add('hidden');
    }

</script>

@endsection
