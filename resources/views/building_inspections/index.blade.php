@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏢 Building Inspection Checklist
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                PPLS-QF-03 — periodic building condition inspections.
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('building-inspections.reports') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                📊 Report
            </a>

            <a href="{{ route('building-inspections.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow">
                ➕ New Inspection
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-3">

        <input type="text" name="search" value="{{ $search }}" placeholder="Search by building name or reference no."
            class="w-full border rounded-lg p-3">

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
            🔍 Search
        </button>

        @if($search)
            <a href="{{ route('building-inspections.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg shadow text-center">
                Clear
            </a>
        @endif

    </form>

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
                            No building inspections yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $inspections->links() }}
    </div>

</div>

@endsection
