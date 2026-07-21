@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🗓️ My DTR
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $monthRangeLabel }} — review this before verifying it.
            </p>
        </div>

        <a href="{{ route('utility-dtr.print', ['personnelId' => $personnel->id, 'start_date' => $monthStart->toDateString(), 'end_date' => $monthEnd->toDateString()]) }}"
           target="_blank"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            🖨 Print
        </a>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    <!-- PERIOD PICKER — e.g. a semi-monthly cutoff like the 15th to end of
         month, instead of always the full calendar month. -->
    <div class="border rounded-lg p-5 bg-gray-50 mb-6">

        <form method="GET" action="{{ route('utility-dtr.my') }}" class="flex flex-wrap items-end gap-3">

            <div class="flex-1 min-w-[220px]">
                <label class="block mb-1 font-semibold text-sm">Period to Review / Verify</label>
                <input type="text" id="period_range" placeholder="Click to pick a date range"
                       readonly autocomplete="off"
                       class="w-full border rounded-lg p-3 cursor-pointer bg-white">
                <input type="hidden" name="start_date" id="start_date" value="{{ $monthStart->toDateString() }}">
                <input type="hidden" name="end_date" id="end_date" value="{{ $monthEnd->toDateString() }}">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                🔍 Load Period
            </button>

            <a href="{{ route('utility-dtr.my', ['start_date' => $monthStart->copy()->startOfMonth()->toDateString(), 'end_date' => $monthStart->copy()->addDays(14)->toDateString()]) }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg text-sm">
                1st–15th
            </a>

            <a href="{{ route('utility-dtr.my', ['start_date' => $monthStart->copy()->addDays(15)->toDateString(), 'end_date' => $monthStart->copy()->endOfMonth()->toDateString()]) }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg text-sm">
                16th–End
            </a>

            <a href="{{ route('utility-dtr.my', ['start_date' => $monthStart->copy()->startOfMonth()->toDateString(), 'end_date' => $monthStart->copy()->endOfMonth()->toDateString()]) }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg text-sm">
                Full Month
            </a>

        </form>

    </div>

    <!-- STATUS -->
    <div class="border rounded-lg p-5 bg-gray-50 mb-6">

        @php $statusInfo = $submission->statusLabel(); @endphp

        <div class="flex flex-wrap items-center justify-between gap-4">

            <div>
                <span class="text-sm px-3 py-1 rounded-full font-semibold {{ $statusInfo['class'] }}">
                    {{ $statusInfo['label'] }}
                </span>

                @if($submission->rejected_at && $submission->status === 'draft')
                    <p class="text-red-600 text-sm mt-2">
                        Sent back by {{ $submission->rejected_stage === 'mark' ? 'the General Services Officer' : 'HR' }}
                        @if($submission->rejection_reason)
                            : "{{ $submission->rejection_reason }}"
                        @endif
                    </p>
                @endif
            </div>

            @if(in_array($submission->status, ['draft']))
                <div class="text-right">
                    <p class="text-sm text-gray-500 mb-2">
                        Period covered: <strong>{{ $monthRangeLabel }}</strong>
                    </p>

                    <form id="verifyDtrForm" method="POST" action="{{ route('utility-dtr.my.verify') }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $monthStart->toDateString() }}">
                        <input type="hidden" name="end_date" value="{{ $monthEnd->toDateString() }}">
                        <button type="button" onclick="confirmVerifyDtr()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                            ✅ Verify & Send to GSO
                        </button>
                    </form>
                </div>
            @endif

        </div>

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

                        <td class="p-3 text-center">
                            @if($day['rowLabel'])
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-indigo-100 text-indigo-700">
                                    {{ $day['rowLabel'] }}
                                </span>
                            @elseif($statusInfo)
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

    flatpickr("#period_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "M j, Y",
        defaultDate: ["{{ $monthStart->toDateString() }}", "{{ $monthEnd->toDateString() }}"],
        onChange: function (selectedDates) {
            if (selectedDates.length === 2) {
                document.getElementById('start_date').value = selectedDates[0].toISOString().slice(0, 10);
                document.getElementById('end_date').value = selectedDates[1].toISOString().slice(0, 10);
            }
        },
    });

    function confirmVerifyDtr() {

        Swal.fire({
            title: 'Verify DTR for {{ $monthRangeLabel }}?',
            html: `
                <p style="text-align:left;margin-bottom:10px;">
                    You're about to certify that this record of your hours worked is true and correct,
                    and send it to the General Services Officer for checking. Please review before confirming:
                </p>
                <div style="text-align:left;display:grid;grid-template-columns:1fr 1fr;gap:6px 16px;font-size:14px;">
                    <div>✅ Present</div><div><strong>{{ $presentDays }} day(s)</strong></div>
                    <div>⏰ Late</div><div><strong>{{ $lateDays }} day(s)</strong></div>
                    <div>❌ Absent</div><div><strong>{{ $absentDays }} day(s)</strong></div>
                    <div>🌴 On Leave</div><div><strong>{{ $leaveDays }} day(s)</strong></div>
                    <div>🕒 Hours Worked</div><div><strong>{{ $totalWorkedHours }} hrs</strong></div>
                    <div>➕ Overtime</div><div><strong>{{ $totalOvertimeHours }} hrs</strong></div>
                    <div>➖ Undertime</div><div><strong>{{ intdiv($totalUndertimeMinutes, 60) }}h {{ $totalUndertimeMinutes % 60 }}m</strong></div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, verify and send',
            cancelButtonText: 'Let me check again',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('verifyDtrForm').submit();
            }
        });

    }

</script>

@endsection
