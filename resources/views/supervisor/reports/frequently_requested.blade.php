@extends('supervisor.reports.layouts.report')

@section('report-title')
🔥 Frequently Requested Items
@endsection

@section('report-description')
Materials with the highest combined demand from Material Requests and Walk-In Issuances.
@endsection

@section('print-route')
{{ route('inventory.frequently-requested.print') }}
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    {{-- Top Item --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-blue-100">
                    Most Requested
                </p>

                <h2 class="text-2xl font-bold mt-4">
                    {{ $topItem->name ?? '-' }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                🏆
            </div>

        </div>

    </div>


    {{-- Items Tracked --}}
    <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-teal-100">
                    Items Tracked
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $frequentlyRequestedCount }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                🔥
            </div>

        </div>

    </div>


    {{-- Total Transactions --}}
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-orange-100">
                    Total Transactions
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $totalTransactions }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                📊
            </div>

        </div>

    </div>


    {{-- Total Qty Requested --}}
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-gray-300">
                    Total Qty Requested
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $totalQtyRequested }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                📦
            </div>

        </div>

    </div>

</div>

@endsection


{{-- =========================================
    EXECUTIVE SUMMARY
========================================= --}}

@section('executive-summary')

<p class="text-lg">

This report tracks
<strong>{{ $frequentlyRequestedCount }}</strong>
materials out of
<strong>{{ $totalMaterials }}</strong>
in the catalog that have been requested at least once, ranked by how
often they've been requested across Material Requests and Walk-In
Issuances combined.

These are the items whose stock levels matter most — running out of
them affects the most requesters.

</p>

@endsection


{{-- =========================================
    TABLE TITLE
========================================= --}}

@section('table-title')

Top {{ $frequentlyRequestedCount }} Requested Materials

@endsection


{{-- =========================================
    TABLE
========================================= --}}

@section('report-table')

<table class="w-full">

    <thead class="bg-blue-100">

        <tr>

            <th class="p-3">#</th>

            <th class="p-3 text-left">
                Material
            </th>

            <th class="p-3">
                Department
            </th>

            <th class="p-3">
                Category
            </th>

            <th class="p-3">
                Total Transactions
            </th>

            <th class="p-3">
                Total Qty Requested
            </th>

        </tr>

    </thead>

    <tbody>

    @forelse($frequentlyRequested as $material)

        <tr class="border-t">

            <td class="p-3 text-center">
                {{ $loop->iteration }}
            </td>

            <td class="p-3">
                {{ $material->name }}
            </td>

            <td class="text-center">
                {{ $material->department->department_name ?? '-' }}
            </td>

            <td class="text-center">
                {{ $material->category->name ?? '-' }}
            </td>

            <td class="text-center font-bold text-blue-600">
                {{ $material->total_transactions }}
            </td>

            <td class="text-center">
                {{ $material->total_requested_qty }}
            </td>

        </tr>

    @empty

        <tr>

            <td colspan="6" class="text-center p-8">
                No request activity on record yet.
            </td>

        </tr>

    @endforelse

    </tbody>

</table>

@endsection


{{-- =========================================
    RECOMMENDATION
========================================= --}}

@section('recommendation')

<ul class="list-disc pl-6 space-y-2">

    <li>
        Prioritize these materials when planning procurement —
        they see the most actual use.
    </li>

    <li>
        Consider raising the reorder threshold for the top items
        so they don't run out between restocks.
    </li>

    <li>
        Review this list periodically, as demand patterns shift
        across the school year.
    </li>

</ul>

@endsection
