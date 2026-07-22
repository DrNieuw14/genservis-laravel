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

    <!-- YEARLY TOTALS -->
    @if($yearlyTotals->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach($yearlyTotals as $yt)
                <div class="border rounded-lg p-5 bg-gray-50">
                    <p class="text-sm text-gray-500 font-semibold">{{ $yt['year'] }} Total</p>
                    <p class="text-2xl font-bold text-gray-800">₱{{ number_format($yt['total_bill'], 2) }}</p>
                    <p class="text-gray-500">{{ number_format($yt['total_consumption'], 2) }} kWh consumed</p>
                </div>
            @endforeach
        </div>
    @endif

    <!-- CONSUMPTION TREND CHART -->
    @if($chartData->count() > 0)
        <div class="border rounded-lg p-5 mb-6">
            <h3 class="font-bold text-lg mb-3">📊 Consumption Trend</h3>
            <canvas id="consumptionTrendChart" height="90"></canvas>
        </div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reporting Month</th>
                    <th class="p-3 text-left">Campus</th>
                    <th class="p-3 text-center">Electricity Bill (₱)</th>
                    <th class="p-3 text-center">vs Previous Month (₱)</th>
                    <th class="p-3 text-center">Consumption (kWh)</th>
                    <th class="p-3 text-center">vs Previous Month (kWh)</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($reports as $report)

                    @php
                        $consDiff = $report->consumptionDifference();
                        $billDiff = $report->billDifference();
                    @endphp

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $report->monthLabel() }}</td>
                        <td class="p-3">{{ $report->campus }}</td>
                        <td class="p-3 text-center">{{ $report->current_month_bill !== null ? number_format($report->current_month_bill, 2) : '-' }}</td>
                        <td class="p-3 text-center">
                            @if($billDiff === null)
                                <span class="text-gray-400 text-xs">-</span>
                            @elseif($billDiff < 0)
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-green-100 text-green-700">
                                    🔻 Saved ₱{{ number_format(abs($billDiff), 2) }} ({{ $report->billPercentChange() }}%)
                                </span>
                            @elseif($billDiff > 0)
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-red-100 text-red-700">
                                    🔺 +₱{{ number_format($billDiff, 2) }} ({{ $report->billPercentChange() }}%)
                                </span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-gray-100 text-gray-600">No change</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">{{ $report->current_month_consumption !== null ? number_format($report->current_month_consumption, 2) : '-' }}</td>
                        <td class="p-3 text-center">
                            @if($consDiff === null)
                                <span class="text-gray-400 text-xs">-</span>
                            @elseif($consDiff < 0)
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-green-100 text-green-700">
                                    🔻 Saved {{ number_format(abs($consDiff), 2) }} kWh ({{ $report->consumptionPercentChange() }}%)
                                </span>
                            @elseif($consDiff > 0)
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-red-100 text-red-700">
                                    🔺 +{{ number_format($consDiff, 2) }} kWh ({{ $report->consumptionPercentChange() }}%)
                                </span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-gray-100 text-gray-600">No change</span>
                            @endif
                        </td>
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
                        <td colspan="9" class="p-6 text-center text-gray-500">
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

@if($chartData->count() > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        const trendData = @json($chartData);

        new Chart(document.getElementById('consumptionTrendChart'), {
            type: 'line',
            data: {
                labels: trendData.map(d => d.month),
                datasets: [
                    {
                        label: 'Electricity Bill (₱)',
                        data: trendData.map(d => d.bill),
                        borderColor: '#16a34a',
                        backgroundColor: '#16a34a',
                        yAxisID: 'yBill',
                        tension: 0.3,
                    },
                    {
                        label: 'Consumption (kWh)',
                        data: trendData.map(d => d.consumption),
                        borderColor: '#2563eb',
                        backgroundColor: '#2563eb',
                        yAxisID: 'yConsumption',
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    yBill: {
                        type: 'linear',
                        position: 'left',
                        title: { display: true, text: '₱' },
                    },
                    yConsumption: {
                        type: 'linear',
                        position: 'right',
                        title: { display: true, text: 'kWh' },
                        grid: { drawOnChartArea: false },
                    },
                },
            },
        });
    </script>
@endif

@endsection
