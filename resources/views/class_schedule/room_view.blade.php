@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏫 Room Schedule
            </h2>
            <p class="text-gray-500 mt-1 text-lg">Read-only view, derived from the section schedules.</p>
        </div>

        <x-back-button :href="route('class-schedule.index')" />

    </div>

    <form method="GET" class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Room</label>
        <select name="room" class="border rounded px-4 py-2 w-full md:w-64" onchange="this.form.submit()">
            @foreach($rooms as $r)
            <option value="{{ $r }}" @selected($room === $r)>{{ $r }}</option>
            @endforeach
        </select>
    </form>

    @if($grid)

    <div class="overflow-x-auto border rounded-lg">

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
                            <td rowspan="{{ $cell['rowspan'] }}" class="p-2 border-l bg-indigo-50 align-top">
                                <div class="font-semibold text-indigo-900">{{ $entry->section->label }}</div>
                                <div class="text-xs text-gray-600">{{ $entry->subject->code }}</div>
                                <div class="text-xs text-gray-600">{{ $entry->faculty_label }}</div>
                            </td>
                        @endif
                    @endforeach
                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

    @else

    <p class="text-gray-500">No rooms are in use yet.</p>

    @endif

</div>

@endsection
