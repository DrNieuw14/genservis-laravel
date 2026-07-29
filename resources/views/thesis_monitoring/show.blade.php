@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🎓 {{ $thesisAdvisee->student_names_label }}
            </h2>
            <p class="text-gray-500 mt-1 text-lg">
                {{ trim(($thesisAdvisee->program ?? '') . ' ' . ($thesisAdvisee->year_level ?? '')) ?: 'No program set' }}
                @if($thesisAdvisee->members->count() > 1)
                · {{ $thesisAdvisee->members->count() }} members
                @endif
            </p>
        </div>

        <x-back-button :href="route('thesis-monitoring.index')" />

    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-gray-50 border rounded-lg p-4 mb-8">
        <p class="text-sm text-gray-500 mb-1">Thesis Title</p>
        <p class="text-lg font-semibold text-gray-800">{{ $thesisAdvisee->thesis_title }}</p>
    </div>

    @php
        $last = $thesisAdvisee->latestMovement();
        $days = $thesisAdvisee->daysSinceLastMovement();
        $holder = $thesisAdvisee->currentHolder();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="border rounded-lg p-4">
            <p class="text-sm text-gray-500">Current Stage</p>
            <p class="text-xl font-bold text-gray-800">{{ $last->chapter_stage ?? '—' }}</p>
        </div>
        <div class="border rounded-lg p-4">
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-xl font-bold text-gray-800">{{ $holder ?? 'Not started' }}</p>
        </div>
        <div class="border rounded-lg p-4">
            <p class="text-sm text-gray-500">Last Movement</p>
            <p class="text-xl font-bold text-gray-800">
                @if(is_null($days)) — @else {{ $days }} day{{ $days === 1 ? '' : 's' }} ago @endif
            </p>
        </div>
    </div>

    <!-- Log a Movement -->
    <h3 class="text-lg font-bold text-gray-800 mb-3">Log a Movement</h3>

    <form method="POST" action="{{ route('thesis-monitoring.movements.store', $thesisAdvisee->id) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-8 items-end">
        @csrf

        <div>
            <label class="text-sm font-semibold">Direction</label>
            <select name="direction" class="w-full border rounded mt-1" required>
                <option value="in">IN — received from student</option>
                <option value="out">OUT — returned to student</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold">Chapter / Stage</label>
            <input type="text" name="chapter_stage" list="chapterStageOptions" autocomplete="off" placeholder="e.g. Outline, Chapter 1, Final Manuscript" class="w-full border rounded mt-1" required>
            <datalist id="chapterStageOptions">
                @foreach($chapterStages as $stage)
                <option value="{{ $stage }}"></option>
                @endforeach
            </datalist>
        </div>

        <div>
            <label class="text-sm font-semibold">Date</label>
            <input type="date" name="moved_at" value="{{ now()->toDateString() }}" class="w-full border rounded mt-1" required>
        </div>

        <div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded w-full">Log Movement</button>
        </div>

        <div class="md:col-span-4">
            <label class="text-sm font-semibold">Remarks (optional)</label>
            <textarea name="remarks" rows="2" placeholder="e.g. for revision — methodology needs rework" class="w-full border rounded mt-1"></textarea>
        </div>

    </form>

    <!-- Movement Log -->
    <h3 class="text-lg font-bold text-gray-800 mb-3">Movement Log</h3>

    @if($thesisAdvisee->movements->isEmpty())

    <p class="text-gray-500 text-center py-8">No movements logged yet.</p>

    @else

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="p-3">Date</th>
                    <th class="p-3">Direction</th>
                    <th class="p-3">Chapter / Stage</th>
                    <th class="p-3">Remarks</th>
                    <th class="p-3">Logged By</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($thesisAdvisee->movements->reverse() as $movement)
                <tr class="align-top">
                    <td class="p-3 whitespace-nowrap">{{ $movement->moved_at->format('M d, Y') }}</td>
                    <td class="p-3">
                        @if($movement->direction === 'in')
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">IN — from student</span>
                        @else
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">OUT — to student</span>
                        @endif
                    </td>
                    <td class="p-3 font-medium">{{ $movement->chapter_stage }}</td>
                    <td class="p-3 text-gray-600">{{ $movement->remarks ?? '—' }}</td>
                    <td class="p-3 text-gray-500">{{ $movement->loggedBy->username ?? '—' }}</td>
                    <td class="p-3 text-right">
                        <form action="{{ route('thesis-monitoring.movements.destroy', [$thesisAdvisee->id, $movement->id]) }}" method="POST" class="inline" onsubmit="return confirm('Remove this log entry?')">
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

@endsection
