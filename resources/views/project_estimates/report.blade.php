@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex justify-between items-start mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📊 Project Estimate Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                All project estimates, for your own reporting and records.
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('project-estimates.index')" />

            <a href="{{ route('project-estimates.reports.print', request()->query()) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

        </div>

    </div>

    <!-- FILTERS -->
    <form method="GET" action="{{ route('project-estimates.reports') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

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
                    <a href="{{ route('project-estimates.reports') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                        Clear
                    </a>
                @endif
            </div>

        </div>

    </form>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-purple-100">Total Estimates</p>
            <h2 class="text-4xl font-extrabold mt-3">{{ $totalEstimates }}</h2>
        </div>

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-blue-100">Materials / Equipment</p>
            <h2 class="text-3xl font-extrabold mt-3">₱{{ number_format($totalMaterials, 2) }}</h2>
        </div>

        <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-orange-100">Labor</p>
            <h2 class="text-3xl font-extrabold mt-3">₱{{ number_format($totalLabor, 2) }}</h2>
        </div>

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-green-100">Total Estimated Value</p>
            <h2 class="text-3xl font-extrabold mt-3">₱{{ number_format($totalValue, 2) }}</h2>
        </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reference No.</th>
                    <th class="p-3 text-left">Project Name</th>
                    <th class="p-3 text-left">Location</th>
                    <th class="p-3">Items</th>
                    <th class="p-3">Materials / Equipment</th>
                    <th class="p-3">Labor</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Created</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($estimates as $estimate)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $estimate->reference_no }}</td>
                        <td class="p-3 font-semibold">{{ $estimate->project_name }}</td>
                        <td class="p-3">{{ $estimate->location ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $estimate->items->count() }}</td>
                        <td class="p-3 text-center">₱{{ number_format($estimate->materialsTotal(), 2) }}</td>
                        <td class="p-3 text-center">₱{{ number_format($estimate->laborTotal(), 2) }}</td>
                        <td class="p-3 text-center font-semibold">₱{{ number_format($estimate->grandTotal(), 2) }}</td>
                        <td class="p-3 text-center">{{ $estimate->created_at->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            <a href="{{ route('project-estimates.show', $estimate->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                View
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="p-6 text-center text-gray-500">
                            No project estimates match these filters.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
