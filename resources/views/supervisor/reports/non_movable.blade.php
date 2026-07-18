@extends('supervisor.reports.layouts.report')

@section('report-title')
📦 Non-Movable Items
@endsection

@section('report-description')
Materials with zero Material Request or Walk-In Issuance activity.
@endsection

@section('print-route')
{{ route('inventory.non-movable.print') }}
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    {{-- Non-Movable Items --}}
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-gray-300">
                    Non-Movable Items
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $nonMovableCount }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                📦
            </div>

        </div>

    </div>


    {{-- Percentage of Inventory --}}
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-orange-100">
                    Of Total Inventory
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ number_format($nonMovablePercentage,1) }}%
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                📊
            </div>

        </div>

    </div>


    {{-- Departments --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-blue-100">
                    Departments Holding These
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $departmentsAffected }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                🏢
            </div>

        </div>

    </div>


    {{-- Overall Status --}}
    <div class="bg-gradient-to-r from-slate-600 to-slate-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-slate-200">
                    Inventory Status
                </p>

                <h2 class="text-2xl font-bold mt-4">
                    ⚪ UNUSED
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                🗑️
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

This report identifies
<strong>{{ $nonMovableCount }}</strong>
materials
({{ number_format($nonMovablePercentage,1) }}% of the catalog)
across
<strong>{{ $departmentsAffected }}</strong>
department(s) that have never been issued through a Material Request
or Walk-In Issuance.

These items tie up shelf space and budget without being used — worth
reviewing for reassignment, consolidation, or removal from the catalog.

</p>

@endsection


{{-- =========================================
    TABLE TITLE
========================================= --}}

@section('table-title')

Materials With No Request Activity

@endsection


{{-- =========================================
    TABLE
========================================= --}}

@section('report-table')

<table class="w-full">

    <thead class="bg-gray-100">

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
                Current Stock
            </th>

            <th class="p-3">
                Date Added
            </th>

        </tr>

    </thead>

    <tbody>

    @forelse($nonMovable as $material)

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

            <td class="text-center font-bold">
                {{ $material->quantity }}
            </td>

            <td class="text-center">
                {{ $material->created_at->format('M d, Y') }}
            </td>

        </tr>

    @empty

        <tr>

            <td colspan="6" class="text-center p-8">
                🎉 Every material has some request activity on record.
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
        Review whether these materials are still needed before
        including them in the next procurement plan.
    </li>

    <li>
        Consider reassigning unused stock to a department that
        could actually use it, instead of buying more elsewhere.
    </li>

    <li>
        If an item has sat unused for a long period, verify it
        hasn't expired or become obsolete.
    </li>

</ul>

@endsection
