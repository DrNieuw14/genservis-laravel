@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex justify-between items-start mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📊 Building Inspection Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                All building inspections, for your own reporting and records.
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('building-inspections.index')" />

            <a href="{{ route('building-inspections.reports.print', request()->query()) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

        </div>

    </div>

    <!-- FILTERS -->
    <form method="GET" action="{{ route('building-inspections.reports') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            <div>
                <label class="block mb-1 font-semibold text-sm">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full border rounded-lg p-3">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    🔍 Filter
                </button>

                @if($dateFrom || $dateTo)
                    <a href="{{ route('building-inspections.reports') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                        Clear
                    </a>
                @endif
            </div>

        </div>

    </form>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-purple-100">Total Inspections</p>
            <h2 class="text-4xl font-extrabold mt-3">{{ $totalInspections }}</h2>
        </div>

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-green-100">🟢 Good</p>
            <h2 class="text-4xl font-extrabold mt-3">{{ $goodCount }}</h2>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-yellow-100">🟡 Needs Attention</p>
            <h2 class="text-4xl font-extrabold mt-3">{{ $needsAttentionCount }}</h2>
        </div>

        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-red-100">🔴 Critical</p>
            <h2 class="text-4xl font-extrabold mt-3">{{ $criticalCount }}</h2>
        </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
                    <th class="p-3 text-left">Building</th>
                    <th class="p-3 text-left">In-Charge</th>
                    <th class="p-3">Inspection Date</th>
                    <th class="p-3">Condition</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($inspections as $inspection)

                    @php $score = $inspection->conditionScore(); @endphp

                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $inspection->reference_no }}</td>
                        <td class="p-3 font-semibold">{{ $inspection->building_name }}</td>
                        <td class="p-3">{{ $inspection->building_in_charge ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $inspection->inspection_date->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            {{ $score['label'] }}
                            <div class="text-xs text-gray-500">{{ $score['flagged'] }} of {{ $score['total'] }} flagged</div>
                        </td>
                        <td class="p-3 text-center">
                            <a href="{{ route('building-inspections.show', $inspection->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            No inspections match these filters.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
