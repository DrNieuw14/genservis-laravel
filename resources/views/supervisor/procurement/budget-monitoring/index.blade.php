@extends('supervisor.reports.layouts.report')

@section('report-title')
💰 Budget Monitoring
@endsection

@section('report-description')
PPMP budget allocation and utilization by department.
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

<div class="print:hidden mb-6">

    <form method="GET" class="flex items-center gap-3">

        <label class="text-white font-semibold">Planning Year</label>

        <select name="year" onchange="this.form.submit()" class="border rounded px-4 py-2">

            @for($option = now()->year + 1; $option >= now()->year - 4; $option--)

                <option value="{{ $option }}" @selected($year == $option)>{{ $option }}</option>

            @endfor

        </select>

    </form>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="uppercase tracking-wider text-sm text-blue-100">Total Allocated</p>
                <h2 class="text-3xl font-extrabold mt-3">₱{{ number_format($totalAllocated, 2) }}</h2>
            </div>
            <div class="text-5xl opacity-70">💰</div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="uppercase tracking-wider text-sm text-green-100">Total Approved</p>
                <h2 class="text-3xl font-extrabold mt-3">₱{{ number_format($totalApproved, 2) }}</h2>
            </div>
            <div class="text-5xl opacity-70">✔️</div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-2xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="uppercase tracking-wider text-sm text-yellow-100">Total Planned</p>
                <h2 class="text-3xl font-extrabold mt-3">₱{{ number_format($totalPlanned, 2) }}</h2>
            </div>
            <div class="text-5xl opacity-70">📝</div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-{{ $totalRemaining < 0 ? 'red' : 'indigo' }}-600 to-{{ $totalRemaining < 0 ? 'red' : 'indigo' }}-700 rounded-2xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="uppercase tracking-wider text-sm text-indigo-100">Total Remaining</p>
                <h2 class="text-3xl font-extrabold mt-3">₱{{ number_format($totalRemaining, 2) }}</h2>
            </div>
            <div class="text-5xl opacity-70">📊</div>
        </div>
    </div>

</div>

@endsection


{{-- =========================================
    EXECUTIVE SUMMARY
========================================= --}}

@section('executive-summary')

<p class="text-lg">

For planning year <strong>{{ $year }}</strong>, PPMPs across
<strong>{{ $byDepartment->count() }}</strong>
department(s) have allocated a total of
<strong>₱{{ number_format($totalAllocated, 2) }}</strong>,
of which
<strong>₱{{ number_format($totalPlanned, 2) }}</strong>
has been committed to planned line items, leaving
<strong>₱{{ number_format($totalRemaining, 2) }}</strong>
remaining.
<strong>₱{{ number_format($totalApproved, 2) }}</strong>
has been formally approved so far.

</p>

@endsection


{{-- =========================================
    TABLE TITLE
========================================= --}}

@section('table-title')
Budget Utilization by Department
@endsection


{{-- =========================================
    TABLE
========================================= --}}

@section('report-table')

<table class="w-full">

    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Department</th>
            <th class="p-3">Plans</th>
            <th class="p-3">Allocated</th>
            <th class="p-3">Planned</th>
            <th class="p-3">Remaining</th>
            <th class="p-3 text-left" style="min-width:180px">Utilization</th>
        </tr>
    </thead>

    <tbody>

    @forelse($byDepartment as $department => $row)

        @php
            $percentage = min($row['utilization'], 100);
            $barColor = 'bg-green-500';

            if ($row['utilization'] >= 90) {
                $barColor = 'bg-red-600';
            } elseif ($row['utilization'] >= 60) {
                $barColor = 'bg-yellow-500';
            }
        @endphp

        <tr class="border-t">
            <td class="p-3 font-semibold">{{ $department }}</td>
            <td class="text-center">{{ $row['plans_count'] }}</td>
            <td class="text-center">₱{{ number_format($row['allocated'], 2) }}</td>
            <td class="text-center">₱{{ number_format($row['planned'], 2) }}</td>
            <td class="text-center">₱{{ number_format($row['remaining'], 2) }}</td>
            <td class="p-3">
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="{{ $barColor }} h-3" style="width: {{ $percentage }}%;"></div>
                </div>
                <span class="text-xs text-gray-600">{{ $row['utilization'] }}%</span>
            </td>
        </tr>

    @empty

        <tr>
            <td colspan="6" class="text-center p-8">No procurement plans found for {{ $year }}.</td>
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
    <li>Departments above 90% utilization should review remaining line items before adding further commitments.</li>
    <li>Departments with low utilization relative to their allocation may have room to fund additional priority needs.</li>
    <li>Coordinate with department heads on plans still in Draft to keep budgets moving toward approval.</li>
</ul>

@endsection
