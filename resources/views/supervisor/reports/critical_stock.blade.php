@extends('supervisor.reports.layouts.report')

@section('report-title')
🚨 Critical Stock Report
@endsection

@section('report-description')
Materials currently at critical inventory levels.
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-lg shadow p-5">

        <h4 class="text-gray-500 text-sm">

            Total Critical Items

        </h4>

        <p class="text-4xl font-bold text-red-600">

            {{ $criticalCount }}

        </p>

    </div>

    <div class="bg-white rounded-lg shadow p-5">

        <h4 class="text-gray-500 text-sm">

            Critical Percentage

        </h4>

        <p class="text-4xl font-bold text-red-600">

            {{ $criticalPercentage }}%

        </p>

    </div>

    <div class="bg-white rounded-lg shadow p-5">

        <h4 class="text-gray-500 text-sm">

            Departments Affected

        </h4>

        <p class="text-4xl font-bold text-orange-600">

            {{ $departmentsAffected }}

        </p>

    </div>

    <div class="bg-white rounded-lg shadow p-5">

        <h4 class="text-gray-500 text-sm">

            Status

        </h4>

        <p class="text-xl font-bold text-red-700">

            Immediate Action

        </p>

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