@extends('supervisor.reports.layouts.report')

@section('report-title')
🏢 Department Summary
@endsection

@section('report-description')
Inventory distribution by department.
@endsection

@section('print-route')
{{ route('inventory.department.print') }}
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    {{-- Total Departments --}}
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-indigo-100">
                    Total Departments
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $totalDepartments }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                🏢
            </div>

        </div>

    </div>


    {{-- Total Materials --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-blue-100">
                    Total Materials
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $totalMaterials }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                📦
            </div>

        </div>

    </div>


    {{-- Total Quantity --}}
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-green-100">
                    Total Quantity
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ number_format($totalQuantity) }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                🔢
            </div>

        </div>

    </div>


    {{-- Departments Needing Attention --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-red-100">
                    Needing Attention
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $departmentsWithIssues }}
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

This report distributes
<strong>{{ $totalMaterials }}</strong>
materials across
<strong>{{ $totalDepartments }}</strong>
department(s), totaling
<strong>{{ number_format($totalQuantity) }}</strong>
units on hand.
<strong>{{ $departmentsWithIssues }}</strong>
department(s) currently have materials at critical, low, or out-of-stock levels.

</p>

@endsection


{{-- =========================================
    TABLE TITLE
========================================= --}}

@section('table-title')

Department Inventory Distribution

@endsection


{{-- =========================================
    TABLE
========================================= --}}

@section('report-table')

<table class="w-full">

    <thead class="bg-gray-100">

        <tr>

            <th class="p-3 text-left">Department</th>
            <th class="p-3">Materials</th>
            <th class="p-3">Total Quantity</th>
            <th class="p-3">Critical</th>
            <th class="p-3">Low Stock</th>
            <th class="p-3">Out of Stock</th>
            <th class="p-3">Status</th>

        </tr>

    </thead>

    <tbody>

    @forelse($departmentRows as $row)

        @php
            $hasIssues = ($row->critical_count + $row->low_count + $row->out_of_stock_count) > 0;
        @endphp

        <tr class="border-t">

            <td class="p-3 font-semibold">{{ $row->department_name }}</td>
            <td class="text-center">{{ $row->total_materials }}</td>
            <td class="text-center">{{ number_format($row->total_quantity) }}</td>
            <td class="text-center text-orange-600 font-bold">{{ $row->critical_count }}</td>
            <td class="text-center text-yellow-600 font-bold">{{ $row->low_count }}</td>
            <td class="text-center text-red-600 font-bold">{{ $row->out_of_stock_count }}</td>
            <td class="text-center">

                @if($hasIssues)
                    <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs">Needs Attention</span>
                @else
                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs">Healthy</span>
                @endif

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="7" class="text-center p-8">No department data available.</td>
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

        Prioritize departments flagged "Needs Attention"
        for procurement follow-up.

    </li>

    <li>

        Review the distribution of stock across
        departments to identify imbalances.

    </li>

    <li>

        Coordinate with department heads on
        upcoming material needs.

    </li>

</ul>

@endsection
