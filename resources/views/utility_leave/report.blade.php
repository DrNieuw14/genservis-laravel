@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex justify-between items-start mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📊 Utility Leave Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Leave requests from Utility & Maintenance Staff, for your own reporting and records.
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('utility-leave.index')" />

            <a href="{{ route('utility-leave.reports.print', request()->query()) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

        </div>

    </div>

    <!-- FILTERS -->
    <form method="GET" action="{{ route('utility-leave.reports') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

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
                    <a href="{{ route('utility-leave.reports') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                        Clear
                    </a>
                @endif
            </div>

        </div>

    </form>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-yellow-100">Pending</p>
            <h2 class="text-4xl font-extrabold mt-3">{{ $pendingCount }}</h2>
        </div>

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-green-100">Approved</p>
            <h2 class="text-4xl font-extrabold mt-3">{{ $approvedCount }}</h2>
        </div>

        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-red-100">Rejected</p>
            <h2 class="text-4xl font-extrabold mt-3">{{ $rejectedCount }}</h2>
        </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Staff</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Reason</th>
                    <th class="p-3">Date From</th>
                    <th class="p-3">Date To</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($leaves as $leave)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $leave->personnel->fullname ?? 'N/A' }}</td>
                        <td class="p-3">{{ $leave->leave_type }}</td>
                        <td class="p-3">{{ $leave->reason }}</td>
                        <td class="p-3 text-center">{{ $leave->start_date->format('M d, Y') }}</td>
                        <td class="p-3 text-center">{{ $leave->end_date->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            @if($leave->status == 'Pending')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">⏳ Pending</span>
                            @elseif($leave->status == 'Approved')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">✅ Approved</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">❌ Rejected</span>
                                @if($leave->rejection_reason)
                                    <div class="text-xs text-gray-500 mt-1 italic">"{{ $leave->rejection_reason }}"</div>
                                @endif
                            @endif
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            No leave requests match these filters.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
