@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📅 My Schedule
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Your duty roster as assigned by the General Services Officer.
        </p>
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

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Shift</th>
                    <th class="p-3 text-left">Task</th>
                    <th class="p-3 text-left">Location</th>
                    <th class="p-3 text-left">Attendance</th>
                    <th class="p-3 text-left">Related Job Request</th>
                    <th class="p-3 text-left">Notes</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($entries as $entry)

                    @php
                        $status = $entry->attendanceStatus();
                        $statusInfo = \App\Models\UtilitySchedule::attendanceStatusLabel($status);
                    @endphp

                    <tr class="hover:bg-gray-50 {{ $entry->schedule_date->isToday() ? 'bg-green-50' : '' }}">

                        <td class="p-3">
                            {{ $entry->schedule_date->format('D, M d, Y') }}
                            @if($entry->schedule_date->isToday())
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-1">Today</span>
                            @endif
                        </td>

                        <td class="p-3">
                            @if($entry->shift_start || $entry->shift_end)
                                {{ $entry->shift_start ? \Illuminate\Support\Carbon::parse($entry->shift_start)->format('g:i A') : '?' }}
                                –
                                {{ $entry->shift_end ? \Illuminate\Support\Carbon::parse($entry->shift_end)->format('g:i A') : '?' }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="p-3 font-semibold">{{ $entry->task }}</td>

                        <td class="p-3">{{ $entry->location ?? '-' }}</td>

                        <td class="p-3">

                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $statusInfo['class'] }}">
                                {{ $statusInfo['label'] }}
                            </span>

                            @if($entry->time_in)
                                <div class="text-xs text-gray-500 mt-1">
                                    In: {{ $entry->time_in->format('g:i A') }}
                                    @if($entry->time_out)
                                        · Out: {{ $entry->time_out->format('g:i A') }}
                                    @endif
                                </div>
                            @endif

                            @if($entry->overtime_minutes > 0)
                                <div class="text-xs text-orange-600 font-semibold mt-1">
                                    ⏱ +{{ $entry->overtime_minutes }} min OT
                                    @if($entry->overtime_reason)
                                        <div class="text-gray-500 font-normal italic">"{{ $entry->overtime_reason }}"</div>
                                    @endif
                                </div>
                            @endif

                            @if(!$entry->time_in)

                                @if($entry->schedule_date->isFuture())

                                    <div class="text-xs text-gray-400 mt-1">
                                        🔒 Opens {{ $entry->schedule_date->format('M d, Y') }}
                                    </div>

                                @else

                                    <form method="POST" action="{{ route('utility-schedule.check-in', $entry->id) }}" class="mt-1">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded shadow">
                                            ✅ Check In
                                        </button>
                                    </form>

                                @endif

                            @elseif(!$entry->time_out)

                                <form method="POST" action="{{ route('utility-schedule.check-out', $entry->id) }}" class="mt-1"
                                      onsubmit="return prepareCheckout(this, {{ json_encode($entry->shift_end) }}, {{ json_encode($entry->schedule_date->toDateString()) }})">
                                    @csrf
                                    <input type="hidden" name="overtime_reason">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded shadow">
                                        🏁 Check Out
                                    </button>
                                </form>

                            @endif

                        </td>

                        <td class="p-3">
                            @if($entry->jobRequest)
                                <a href="{{ route('job-requests.show', $entry->jobRequest->id) }}" class="text-blue-600 hover:underline">
                                    {{ $entry->jobRequest->reference_no }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="p-3 text-gray-500">{{ $entry->notes ?? '-' }}</td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No schedule entries yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

    // If checking out past shift_end (+ grace), offer an optional one-line
    // reason — purely informational, nothing blocks the checkout either way.
    function prepareCheckout(form, shiftEnd, scheduleDate) {

        if (shiftEnd) {

            const graceDeadline = new Date(scheduleDate + 'T' + shiftEnd);
            graceDeadline.setMinutes(graceDeadline.getMinutes() + 15);

            if (new Date() > graceDeadline) {
                const reason = prompt('You are checking out past your scheduled shift end. Add a quick reason for the overtime? (optional)');
                if (reason) {
                    form.querySelector('input[name="overtime_reason"]').value = reason;
                }
            }
        }

        return true;
    }

</script>

@endsection
