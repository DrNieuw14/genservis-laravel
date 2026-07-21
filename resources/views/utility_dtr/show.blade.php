@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🗓️ {{ $personnel->fullname }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Daily Time Record — {{ $monthRangeLabel }}
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('utility-dtr.index')" />

            <a href="{{ route('utility-dtr.print', ['personnelId' => $personnel->id, 'start_date' => $monthStart->toDateString(), 'end_date' => $monthEnd->toDateString()]) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    <!-- SUBMISSION STATUS -->
    <div class="border rounded-lg p-5 bg-gray-50 mb-6">

        @php $statusInfo = $submission->statusLabel(); @endphp

        <div class="flex flex-wrap items-center justify-between gap-4">

            <span class="text-sm px-3 py-1 rounded-full font-semibold {{ $statusInfo['class'] }}">
                {{ $statusInfo['label'] }}
            </span>

            <div class="flex gap-2">

                @if($submission->status === 'employee_verified')

                    <form method="POST" action="{{ route('utility-dtr.check', ['personnelId' => $personnel->id]) }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $monthStart->toDateString() }}">
                        <input type="hidden" name="end_date" value="{{ $monthEnd->toDateString() }}">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                            🔎 Mark as Checked
                        </button>
                    </form>

                    <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow">
                        ❌ Send Back
                    </button>

                @elseif($submission->status === 'draft')

                    <span class="text-gray-500 text-sm">Waiting for the employee to verify this DTR first.</span>

                @endif

            </div>

        </div>

    </div>

    @if($submission->status === 'employee_verified')

        <div id="rejectModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold mb-3">Send Back for Correction</h3>
                <form method="POST" action="{{ route('utility-dtr.reject', ['personnelId' => $personnel->id]) }}">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $monthStart->toDateString() }}">
                    <input type="hidden" name="end_date" value="{{ $monthEnd->toDateString() }}">
                    <label class="block mb-2 font-semibold text-sm">Reason (optional)</label>
                    <textarea name="rejection_reason" rows="3" class="w-full border rounded-lg p-3"></textarea>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                                class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">Cancel</button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Send Back</button>
                    </div>
                </form>
            </div>
        </div>

    @endif

    <!-- MONTH NAVIGATION -->
    <div class="flex items-center justify-between border rounded-lg p-4 bg-gray-50 mb-6">

        <a href="{{ route('utility-dtr.show', ['personnelId' => $personnel->id, 'month' => $monthStart->copy()->subMonth()->format('Y-m')]) }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            ← Previous Month
        </a>

        <span class="font-semibold text-lg text-gray-700">{{ $monthLabel }}</span>

        <a href="{{ route('utility-dtr.show', ['personnelId' => $personnel->id, 'month' => $monthStart->copy()->addMonth()->format('Y-m')]) }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            Next Month →
        </a>

    </div>

    <!-- SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-5">
            <p class="uppercase tracking-wider text-xs text-green-100">Present</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $presentDays }}</h2>
        </div>

        <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-2xl shadow-lg text-white p-5">
            <p class="uppercase tracking-wider text-xs text-orange-100">Late</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $lateDays }}</h2>
        </div>

        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-lg text-white p-5">
            <p class="uppercase tracking-wider text-xs text-red-100">Absent</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $absentDays }}</h2>
        </div>

        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-2xl shadow-lg text-white p-5">
            <p class="uppercase tracking-wider text-xs text-indigo-100">On Leave</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $leaveDays }}</h2>
        </div>

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-5">
            <p class="uppercase tracking-wider text-xs text-blue-100">Hours Worked</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $totalWorkedHours }}</h2>
        </div>

        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg text-white p-5">
            <p class="uppercase tracking-wider text-xs text-purple-100">Overtime (hrs)</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $totalOvertimeHours }}</h2>
        </div>

    </div>

    <!-- DAY-BY-DAY TABLE -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Day</th>
                    <th class="p-3">Time In</th>
                    <th class="p-3">Time Out</th>
                    <th class="p-3">Task</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Overtime</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($days as $day)

                    @php
                        $entry = $day['entry'];
                        $statusInfo = $day['status']
                            ? \App\Models\UtilitySchedule::attendanceStatusLabel($day['status'])
                            : null;
                    @endphp

                    <tr class="hover:bg-gray-50 {{ $day['date']->isToday() ? 'bg-green-50' : '' }} {{ $day['date']->isWeekend() ? 'bg-gray-50' : '' }}">

                        <td class="p-3">{{ $day['date']->format('M d, Y') }}</td>
                        <td class="p-3 text-gray-500">{{ $day['date']->format('D') }}</td>

                        <td class="p-3 text-center">{{ $entry?->time_in?->format('g:i A') ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $entry?->time_out?->format('g:i A') ?? '-' }}</td>

                        <td class="p-3">{{ $entry?->task ?? '-' }}</td>

                        <td class="p-3 text-center">
                            @if($statusInfo)
                                <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $statusInfo['class'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">Not scheduled</span>
                            @endif
                        </td>

                        <td class="p-3 text-center">
                            {{ $entry?->overtime_minutes > 0 ? $entry->overtime_minutes . ' min' : '-' }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
