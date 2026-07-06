@extends('supervisor.reports.layouts.report')

@section('report-title')
🚨 Critical Stock Report
@endsection

@section('report-description')
Materials currently at critical inventory levels.
@endsection

@section('print-route')
{{ route('inventory.critical.print') }}
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    {{-- Critical Items --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-red-100">
                    Critical Items
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $criticalCount }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                🚨
            </div>

        </div>

    </div>


    {{-- Critical Percentage --}}
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-orange-100">
                    Critical Percentage
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ number_format($criticalPercentage,1) }}%
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
                    Departments Affected
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
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-gray-300">
                    Inventory Status
                </p>

                <h2 class="text-2xl font-bold mt-4">
                    🔴 CRITICAL
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                ⚠️
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
<strong>{{ $criticalCount }}</strong>
critical inventory materials affecting
<strong>{{ $departmentsAffected }}</strong>
department(s).

Immediate procurement is recommended to
prevent operational disruption.

</p>

@endsection


{{-- =========================================
    TABLE TITLE
========================================= --}}

@section('table-title')

Detailed Critical Inventory

@endsection


{{-- =========================================
    TABLE
========================================= --}}

@section('report-table')

<table class="w-full">

    <thead class="bg-red-100">

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

                Qty

            </th>

            <th class="p-3">

                Threshold

            </th>

            <th class="p-3">

                Recommendation

            </th>

        </tr>

    </thead>

    <tbody>

    @forelse($criticalMaterials as $material)

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

                {{ $material->category->category_name ?? '-' }}

            </td>

            <td class="text-center font-bold text-red-600">

                {{ $material->quantity }}

            </td>

            <td class="text-center">

                {{ $material->threshold }}

            </td>

            <td class="text-center">

                Immediate Procurement

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="7" class="text-center p-8">

                🎉 No critical stock materials found.

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

        Prioritize procurement of materials
        with the lowest available quantity.

    </li>

    <li>

        Review reorder thresholds regularly.

    </li>

    <li>

        Monitor inventory daily until
        replenishment is completed.

    </li>

</ul>

@endsection