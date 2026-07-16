@extends('supervisor.reports.layouts.report')

@section('report-title')
🧪 Expiration Report
@endsection

@section('report-description')
Expiring and expired inventory batches.
@endsection

@section('print-route')
{{ route('inventory.expiration.print') }}
@endsection


{{-- =========================================
    KPI CARDS
========================================= --}}

@section('kpi-cards')

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    {{-- Expiring Soon --}}
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-purple-100">
                    Expiring Within 30 Days
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $expiringCount }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                ⏳
            </div>

        </div>

    </div>


    {{-- Expired --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-lg text-white p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="uppercase tracking-wider text-sm text-red-100">
                    Expired Batches
                </p>

                <h2 class="text-5xl font-extrabold mt-3">
                    {{ $expiredCount }}
                </h2>

            </div>

            <div class="text-5xl opacity-70">
                🧪
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
                    @if($expiredCount > 0)
                        🔴 EXPIRED FOUND
                    @elseif($expiringCount > 0)
                        🟡 EXPIRING SOON
                    @else
                        🟢 NO ISSUES
                    @endif
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
<strong>{{ $expiringCount }}</strong>
batch(es) expiring within 30 days and
<strong>{{ $expiredCount }}</strong>
already-expired batch(es), affecting
<strong>{{ $departmentsAffected }}</strong>
department(s).

Expired batches should be pulled from usable stock immediately,
and expiring batches should be prioritized for consumption.

</p>

@endsection


{{-- =========================================
    TABLE TITLE
========================================= --}}

@section('table-title')

Expiring & Expired Inventory Batches

@endsection


{{-- =========================================
    TABLE
========================================= --}}

@section('report-table')

<div class="p-5">

    <h3 class="font-bold text-gray-800 mb-3">
        Expiring Within 30 Days
    </h3>

    <table class="w-full mb-8">

        <thead class="bg-purple-100">

            <tr>
                <th class="p-3">#</th>
                <th class="p-3 text-left">Material</th>
                <th class="p-3">Department</th>
                <th class="p-3">Batch No.</th>
                <th class="p-3">Remaining</th>
                <th class="p-3">Expiration Date</th>
                <th class="p-3">Days Left</th>
            </tr>

        </thead>

        <tbody>

        @forelse($expiringMaterials as $batch)

            <tr class="border-t">
                <td class="p-3 text-center">{{ $loop->iteration }}</td>
                <td class="p-3">{{ $batch->material->name }}</td>
                <td class="text-center">{{ $batch->material->department->department_name ?? '-' }}</td>
                <td class="text-center">{{ $batch->batch_no }}</td>
                <td class="text-center">{{ $batch->quantity_remaining }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($batch->expiration_date)->format('M d, Y') }}</td>
                <td class="text-center text-purple-700 font-bold">{{ now()->diffInDays($batch->expiration_date) }} days</td>
            </tr>

        @empty

            <tr>
                <td colspan="7" class="text-center p-6">🎉 No materials expiring within 30 days.</td>
            </tr>

        @endforelse

        </tbody>

    </table>

    <h3 class="font-bold text-gray-800 mb-3">
        Already Expired
    </h3>

    <table class="w-full">

        <thead class="bg-red-100">

            <tr>
                <th class="p-3">#</th>
                <th class="p-3 text-left">Material</th>
                <th class="p-3">Department</th>
                <th class="p-3">Batch No.</th>
                <th class="p-3">Remaining</th>
                <th class="p-3">Expiration Date</th>
                <th class="p-3">Days Expired</th>
            </tr>

        </thead>

        <tbody>

        @forelse($expiredMaterials as $batch)

            <tr class="border-t">
                <td class="p-3 text-center">{{ $loop->iteration }}</td>
                <td class="p-3">{{ $batch->material->name }}</td>
                <td class="text-center">{{ $batch->material->department->department_name ?? '-' }}</td>
                <td class="text-center">{{ $batch->batch_no }}</td>
                <td class="text-center">{{ $batch->quantity_remaining }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($batch->expiration_date)->format('M d, Y') }}</td>
                <td class="text-center text-red-600 font-bold">{{ \Carbon\Carbon::parse($batch->expiration_date)->diffInDays(now()) }} days</td>
            </tr>

        @empty

            <tr>
                <td colspan="7" class="text-center p-6">🎉 No expired inventory batches.</td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection


{{-- =========================================
    RECOMMENDATION
========================================= --}}

@section('recommendation')

<ul class="list-disc pl-6 space-y-2">

    <li>

        Remove expired batches from usable stock
        immediately and log the disposal.

    </li>

    <li>

        Prioritize consumption of batches expiring
        within 30 days before older, longer-dated stock.

    </li>

    <li>

        Review procurement schedules to avoid
        over-ordering perishable materials.

    </li>

</ul>

@endsection
