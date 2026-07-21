@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                💡 Energy Conservation Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Monthly report to DOE Main Campus — energy consumption, conservation measures, and activities.
            </p>
        </div>

        <a href="{{ route('energy-reports.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">
            ➕ New Monthly Report
        </a>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reporting Month</th>
                    <th class="p-3 text-left">Campus</th>
                    <th class="p-3 text-center">Electricity Bill (₱)</th>
                    <th class="p-3 text-center">Consumption (kWh)</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($reports as $report)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $report->monthLabel() }}</td>
                        <td class="p-3">{{ $report->campus }}</td>
                        <td class="p-3 text-center">{{ $report->current_month_bill !== null ? number_format($report->current_month_bill, 2) : '-' }}</td>
                        <td class="p-3 text-center">{{ $report->current_month_consumption !== null ? number_format($report->current_month_consumption, 2) : '-' }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $report->status === 'submitted' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $report->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <a href="{{ route('energy-reports.show', $report->id) }}" class="text-blue-600 hover:underline text-sm">
                                📋 Open
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            No monthly reports yet. Click "New Monthly Report" to start one.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $reports->links() }}
    </div>

</div>

@endsection
