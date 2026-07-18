@extends('supervisor.reports.layouts.report')

@section('report-title')
📈 Purchase Forecast
@endsection

@section('report-description')
Quarterly planned procurement spend derived from PPMP line items.
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

<div class="print:hidden mb-6">

    <form method="GET" class="flex items-center gap-3">

        <label class="text-gray-700 font-semibold">Planning Year</label>

        <select name="year" onchange="this.form.submit()" class="border rounded px-4 py-2">

            @for($option = now()->year + 1; $option >= now()->year - 4; $option--)

                <option value="{{ $option }}" @selected($year == $option)>{{ $option }}</option>

            @endfor

        </select>

    </form>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    @foreach($quarterTotals as $quarter => $totals)

        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="uppercase tracking-wider text-sm text-purple-100">{{ $quarter }} Forecast</p>
                    <h2 class="text-3xl font-extrabold mt-3">₱{{ number_format($totals['cost'], 2) }}</h2>
                    <p class="text-purple-100 text-sm mt-1">{{ number_format($totals['quantity']) }} units</p>
                </div>
                <div class="text-5xl opacity-70">📈</div>
            </div>
        </div>

    @endforeach

</div>

@endsection


{{-- =========================================
    EXECUTIVE SUMMARY
========================================= --}}

@section('executive-summary')

<p class="text-lg">

For planning year <strong>{{ $year }}</strong>, total forecasted procurement spend across all PPMP line items is
<strong>₱{{ number_format($annualTotal, 2) }}</strong>,
distributed across
<strong>{{ $byDepartment->count() }}</strong>
department(s) over the four quarters.

</p>

@endsection


{{-- =========================================
    TABLE TITLE
========================================= --}}

@section('table-title')
Quarterly Forecast by Department
@endsection


{{-- =========================================
    TABLE
========================================= --}}

@section('report-table')

@php
    $maxQuarter = max(1, collect($quarterTotals)->max('cost'));
@endphp

<div class="p-5 border-b">

    <h3 class="font-semibold text-gray-700 mb-3">Quarterly Trend</h3>

    @foreach($quarterTotals as $quarter => $totals)

        <div class="flex items-center gap-3 mb-2">
            <span class="w-10 text-sm font-semibold text-gray-600">{{ $quarter }}</span>
            <div class="flex-1 bg-gray-200 rounded-full h-4 overflow-hidden">
                <div class="bg-purple-600 h-4" style="width: {{ round(($totals['cost'] / $maxQuarter) * 100, 2) }}%;"></div>
            </div>
            <span class="w-32 text-sm text-gray-600 text-right">₱{{ number_format($totals['cost'], 2) }}</span>
        </div>

    @endforeach

</div>

<table class="w-full">

    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Department</th>
            <th class="p-3">Q1</th>
            <th class="p-3">Q2</th>
            <th class="p-3">Q3</th>
            <th class="p-3">Q4</th>
            <th class="p-3">Total</th>
        </tr>
    </thead>

    <tbody>

    @forelse($byDepartment as $department => $row)

        <tr class="border-t">
            <td class="p-3 font-semibold">{{ $department }}</td>
            <td class="text-center">₱{{ number_format($row['q1'], 2) }}</td>
            <td class="text-center">₱{{ number_format($row['q2'], 2) }}</td>
            <td class="text-center">₱{{ number_format($row['q3'], 2) }}</td>
            <td class="text-center">₱{{ number_format($row['q4'], 2) }}</td>
            <td class="text-center font-bold">₱{{ number_format($row['total'], 2) }}</td>
        </tr>

    @empty

        <tr>
            <td colspan="6" class="text-center p-8">No procurement plan items found for {{ $year }}.</td>
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
    <li>Coordinate delivery schedules with suppliers ahead of quarters with the heaviest forecasted spend.</li>
    <li>Departments with concentrated spend in a single quarter may want to spread procurement to smooth cash flow.</li>
    <li>Review this forecast against actual budget allocation in Budget Monitoring to confirm feasibility.</li>
</ul>

@endsection
