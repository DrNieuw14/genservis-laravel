@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex justify-between items-start mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📊 Attendance Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Utility & Maintenance Staff attendance and overtime, for your own reporting and records.
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('utility-schedule.index')" />

            <a href="{{ route('utility-schedule.attendance-report.print', request()->query()) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

        </div>

    </div>

    <!-- FILTERS -->
    <form method="GET" action="{{ route('utility-schedule.attendance-report') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <div>
                <label class="block mb-1 font-semibold text-sm">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">Staff</label>
                <select name="personnel_id" class="w-full border rounded-lg p-3">
                    <option value="">All Staff</option>
                    @foreach($staff as $person)
                        <option value="{{ $person->id }}" {{ (string) $personnelId === (string) $person->id ? 'selected' : '' }}>
                            {{ $person->fullname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    🔍 Filter
                </button>

                @if($dateFrom || $dateTo || $personnelId)
                    <a href="{{ route('utility-schedule.attendance-report') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                        Clear
                    </a>
                @endif
            </div>

        </div>

    </form>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-6 mb-8">

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-green-100">Present</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $presentCount }}</h2>
                </div>
                <div class="text-5xl opacity-70">✅</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-orange-100">Late</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $lateCount }}</h2>
                </div>
                <div class="text-5xl opacity-70">⏰</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-red-100">No Show</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $noShowCount }}</h2>
                </div>
                <div class="text-5xl opacity-70">❌</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-yellow-100">Incomplete</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $incompleteCount }}</h2>
                </div>
                <div class="text-5xl opacity-70">⚠️</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-indigo-100">On Leave</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $onLeaveCount }}</h2>
                </div>
                <div class="text-5xl opacity-70">🌴</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-purple-100">Overtime</p>
                    <h2 class="text-4xl font-extrabold mt-3">{{ floor($totalOvertimeMinutes / 60) }}h {{ $totalOvertimeMinutes % 60 }}m</h2>
                </div>
                <div class="text-5xl opacity-70">⏱️</div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Staff</th>
                    <th class="p-3 text-left">Task</th>
                    <th class="p-3">Shift</th>
                    <th class="p-3">Time In</th>
                    <th class="p-3">Time Out</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Overtime</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($entries as $entry)

                    @php
                        $status = $entry->attendanceStatus();
                        $statusInfo = \App\Models\UtilitySchedule::attendanceStatusLabel($status);
                    @endphp

                    <tr class="hover:bg-gray-50">

                        <td class="p-3">{{ $entry->schedule_date->format('M d, Y') }}</td>

                        <td class="p-3">
                            <div class="flex items-center gap-2">

                                @if($entry->personnel?->photo_url)
                                    <img src="{{ $entry->personnel->photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover border">
                                @else
                                    <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($entry->personnel->fullname ?? '?', 0, 1)) }}
                                    </span>
                                @endif

                                {{ $entry->personnel->fullname ?? '-' }}

                            </div>
                        </td>

                        <td class="p-3">{{ $entry->task }}</td>

                        <td class="p-3 text-center">
                            @if($entry->shift_start || $entry->shift_end)
                                {{ $entry->shift_start ? \Illuminate\Support\Carbon::parse($entry->shift_start)->format('g:i A') : '?' }}
                                –
                                {{ $entry->shift_end ? \Illuminate\Support\Carbon::parse($entry->shift_end)->format('g:i A') : '?' }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="p-3 text-center">{{ $entry->time_in?->format('g:i A') ?? '-' }}</td>

                        <td class="p-3 text-center">{{ $entry->time_out?->format('g:i A') ?? '-' }}</td>

                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $statusInfo['class'] }}">
                                {{ $statusInfo['label'] }}
                            </span>
                        </td>

                        <td class="p-3 text-center">
                            @if($entry->overtime_minutes > 0)
                                <span class="text-orange-600 font-semibold">{{ $entry->overtime_minutes }} min</span>
                                @if($entry->overtime_reason)
                                    <div class="text-xs text-gray-500 italic">"{{ $entry->overtime_reason }}"</div>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No schedule entries match these filters.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
