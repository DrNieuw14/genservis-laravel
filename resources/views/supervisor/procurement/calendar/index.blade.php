@extends('supervisor.reports.layouts.report')

@section('report-title')
📅 Procurement Calendar
@endsection

@section('report-description')
Chronological timeline of PPMP lifecycle milestones.
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

@php
    $createdCount = $events->where('type', 'created')->count();
    $submittedCount = $events->where('type', 'submitted')->count();
    $approvedCount = $events->where('type', 'approved')->count();
    $rejectedCount = $events->where('type', 'rejected')->count();
@endphp

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

    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-2xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="uppercase tracking-wider text-sm text-yellow-100">Plans Created</p>
                <h2 class="text-5xl font-extrabold mt-3">{{ $createdCount }}</h2>
            </div>
            <div class="text-5xl opacity-70">📝</div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="uppercase tracking-wider text-sm text-blue-100">Submitted</p>
                <h2 class="text-5xl font-extrabold mt-3">{{ $submittedCount }}</h2>
            </div>
            <div class="text-5xl opacity-70">📤</div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="uppercase tracking-wider text-sm text-green-100">Approved</p>
                <h2 class="text-5xl font-extrabold mt-3">{{ $approvedCount }}</h2>
            </div>
            <div class="text-5xl opacity-70">✔️</div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="uppercase tracking-wider text-sm text-red-100">Rejected</p>
                <h2 class="text-5xl font-extrabold mt-3">{{ $rejectedCount }}</h2>
            </div>
            <div class="text-5xl opacity-70">✖️</div>
        </div>
    </div>

</div>

@endsection


{{-- =========================================
    EXECUTIVE SUMMARY
========================================= --}}

@section('executive-summary')

<p class="text-lg">

For planning year <strong>{{ $year }}</strong>,
<strong>{{ $createdCount }}</strong>
procurement plan(s) were created,
<strong>{{ $submittedCount }}</strong>
submitted for approval,
<strong>{{ $approvedCount }}</strong>
approved, and
<strong>{{ $rejectedCount }}</strong>
rejected.

</p>

@endsection


{{-- =========================================
    TABLE TITLE
========================================= --}}

@section('table-title')
Procurement Plan Timeline
@endsection


{{-- =========================================
    TABLE
========================================= --}}

@section('report-table')

@php
    $eventColors = [
        'created' => 'bg-yellow-100 text-yellow-700',
        'submitted' => 'bg-blue-100 text-blue-700',
        'approved' => 'bg-green-100 text-green-700',
        'rejected' => 'bg-red-100 text-red-700',
    ];
@endphp

<table class="w-full">

    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Date</th>
            <th class="p-3 text-left">Event</th>
            <th class="p-3 text-left">Plan No.</th>
            <th class="p-3 text-left">Department</th>
        </tr>
    </thead>

    <tbody>

    @forelse($events as $event)

        <tr class="border-t">
            <td class="p-3">{{ $event['date']?->format('M d, Y h:i A') }}</td>
            <td class="p-3">
                <span class="{{ $eventColors[$event['type']] ?? 'bg-gray-100 text-gray-700' }} px-2 py-1 rounded">
                    {{ $event['label'] }}
                </span>
            </td>
            <td class="p-3">{{ $event['plan']->plan_number }}</td>
            <td class="p-3">{{ $event['plan']->department->department_name ?? 'Unassigned' }}</td>
        </tr>

    @empty

        <tr>
            <td colspan="4" class="text-center p-8">No procurement plan activity found for {{ $year }}.</td>
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
    <li>Follow up on plans that have remained in Draft or Submitted status for an extended period.</li>
    <li>Use this timeline to anticipate upcoming approval workload for the Administrator.</li>
</ul>

@endsection
