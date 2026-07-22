@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 Property Issuances
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                ICS and PAR slips issued from Room Inventory.
            </p>
        </div>

        <div class="flex gap-2">
            <x-back-button :href="route('property-inventory.index')" />

            <a href="{{ route('property-issuances.create') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                ➕ New Issuance
            </a>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    <!-- FILTERS -->
    <form method="GET" action="{{ route('property-issuances.index') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            <div>
                <label class="block mb-1 font-semibold text-sm">Date Range</label>

                <input type="text" id="date_range" readonly autocomplete="off"
                    class="w-full border rounded-lg p-3 cursor-pointer bg-white"
                    placeholder="Click to pick a date range">

                <input type="hidden" name="date_from" id="date_from" value="{{ $dateFrom }}">
                <input type="hidden" name="date_to" id="date_to" value="{{ $dateTo }}">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">Room</label>
                <select name="room_id" class="w-full border rounded-lg p-3">
                    <option value="">All Rooms</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ (string) $roomId === (string) $room->id ? 'selected' : '' }}>
                            {{ $room->room_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    🔍 Filter
                </button>

                @if($dateFrom || $dateTo || $roomId)
                    <a href="{{ route('property-issuances.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                        Clear
                    </a>
                @endif
            </div>

        </div>

    </form>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-green-100">Total Slips</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $totalSlips }}</h2>
                </div>
                <div class="text-5xl opacity-70">🧾</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-blue-100">ICS Slips</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $totalIcs }}</h2>
                </div>
                <div class="text-5xl opacity-70">📋</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-purple-100">PAR Slips</p>
                    <h2 class="text-5xl font-extrabold mt-3">{{ $totalPar }}</h2>
                </div>
                <div class="text-5xl opacity-70">🏛️</div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Slip No.</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Room</th>
                    <th class="p-3 text-left">Received By</th>
                    <th class="p-3 text-center">Items</th>
                    <th class="p-3 text-center">Total Value (₱)</th>
                    <th class="p-3 text-center">Date Issued</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($issuances as $issuance)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $issuance->slip_no }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $issuance->isIcs() ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ $issuance->isIcs() ? 'ICS' : 'PAR' }}
                            </span>
                        </td>
                        <td class="p-3">{{ $issuance->room->room_name ?? '-' }}</td>
                        <td class="p-3">{{ $issuance->recipient->fullname ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $issuance->items->count() }}</td>
                        <td class="p-3 text-center">{{ number_format($issuance->totalValue(), 2) }}</td>
                        <td class="p-3 text-center">{{ $issuance->issued_at->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            <a href="{{ route('property-issuances.show', $issuance->id) }}" class="text-blue-600 hover:underline text-sm">
                                📋 Open
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No issuance slips match these filters.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $issuances->links() }}
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

    function toISODate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    const existingFrom = @json($dateFrom ?: null);
    const existingTo = @json($dateTo ?: null);

    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "M j, Y",
        defaultDate: existingFrom && existingTo ? [existingFrom, existingTo] : (existingFrom ? [existingFrom] : null),

        onChange: function (selectedDates) {

            if (selectedDates.length === 1) {
                const iso = toISODate(selectedDates[0]);
                document.getElementById('date_from').value = iso;
                document.getElementById('date_to').value = iso;
            }

            if (selectedDates.length === 2) {
                document.getElementById('date_from').value = toISODate(selectedDates[0]);
                document.getElementById('date_to').value = toISODate(selectedDates[1]);
            }
        }
    });

</script>

@endsection
