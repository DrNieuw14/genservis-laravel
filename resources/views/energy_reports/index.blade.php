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

    <!-- CONSUMPTION TREND CHARTS -->
    @if($cycleOptions->isNotEmpty())
        <div class="mb-6">
            <form method="GET" action="{{ route('energy-reports.index') }}" class="flex flex-wrap items-end gap-3 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">From cycle</label>
                    <select name="from" class="border rounded-lg p-2">
                        <option value="">Earliest</option>
                        @foreach($cycleOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $from === $opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">To cycle</label>
                    <select name="to" class="border rounded-lg p-2">
                        <option value="">Latest</option>
                        @foreach($cycleOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $to === $opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Filter</button>
                @if($from || $to)
                    <a href="{{ route('energy-reports.index') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
                @endif
            </form>

            @if($chartData->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                    <div class="border rounded-lg p-5">
                        <h3 class="font-bold text-lg mb-3">📊 Consumption and Electricity Bill Trend</h3>
                        <canvas id="consumptionTrendChart" height="110"></canvas>
                    </div>
                    <div class="border rounded-lg p-5">
                        <h3 class="font-bold text-lg mb-3">📉 Consumption Change vs. Previous Cycle</h3>
                        <canvas id="consumptionChangeChart" height="110"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="border rounded-lg p-5">
                        <h3 class="font-bold text-lg mb-3">⚡ Electricity Cost per kWh</h3>
                        <canvas id="rateChart" height="110"></canvas>
                    </div>

                    <div class="border rounded-lg p-5">
                        <h3 class="font-bold text-lg mb-3">
                            💰 Rate Charge This Month
                            @if($latestReport)
                                ({{ $latestReport->chartMonthLabel() }})
                            @endif
                        </h3>

                        @php
                            $prevRate = $latestReport?->previousRate();
                            $curRate = $latestReport?->currentRate();
                            $rateDiff = $latestReport?->rateDifference();
                            $rateDiffPercent = $latestReport?->rateDifferencePercent();
                            $maxRateScale = max($prevRate ?? 0, $curRate ?? 0, 1);
                            $rateDown = $rateDiff !== null && $rateDiff <= 0;
                        @endphp

                        @if($prevRate === null || $curRate === null)
                            <p class="text-gray-500 text-sm">Not enough data yet to compare rates — needs both this cycle's and the previous cycle's bill/consumption figures.</p>
                        @else
                            <div class="space-y-4 mt-4">
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-1">
                                        <span>Previous Rate (₱/kWh)</span>
                                        <span>₱{{ number_format($prevRate, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-6">
                                        <div class="bg-gray-400 h-6 rounded-full" style="width: {{ $prevRate / $maxRateScale * 100 }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-1">
                                        <span>Current Rate (₱/kWh)</span>
                                        <span>₱{{ number_format($curRate, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-6">
                                        <div class="{{ $rateDown ? 'bg-green-500' : 'bg-red-500' }} h-6 rounded-full" style="width: {{ $curRate / $maxRateScale * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-4 rounded-lg {{ $rateDown ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <p class="font-semibold {{ $rateDown ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $rateDown ? '✅' : '⚠️' }}
                                    Rate {{ $rateDown ? 'decreased' : 'increased' }} by ₱{{ number_format(abs($rateDiff), 2) }}/kWh
                                    {{ $rateDown ? '— RATE DOWN' : '— RATE UP' }}
                                </p>
                                <p class="text-sm {{ $rateDown ? 'text-green-600' : 'text-red-600' }} mt-1">
                                    The utility's charge is {{ number_format(abs($rateDiffPercent), 1) }}% {{ $rateDown ? 'lower' : 'higher' }} than last cycle.
                                    {{ $rateDown ? 'Good news for the budget.' : 'This is the utility rate itself, not usage — conservation measures won\'t offset it.' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-sm">No reports fall within the selected cycle range.</p>
            @endif
        </div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Reporting Month</th>
                    <th class="p-3 text-left">Campus</th>
                    <th class="p-3 text-center">Rate (₱/kWh)</th>
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
                        <td class="p-3 text-center">{{ $report->currentRate() !== null ? number_format($report->currentRate(), 2) : '-' }}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
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

        // Percent change is null for the first cycle in range (no prior
        // cycle to compare against) — plot it as a zero-height bar rather
        // than breaking the chart; the datalabel below still shows "-" for it.
        const changeValues = trendData.map(d => d.consumptionPercentChange);
        const changeData = changeValues.map(v => v ?? 0);

        new Chart(document.getElementById('consumptionChangeChart'), {
            type: 'bar',
            data: {
                labels: trendData.map(d => d.month),
                datasets: [
                    {
                        label: '% Change',
                        data: changeData,
                        backgroundColor: changeData.map(v => v > 0 ? '#ef4444' : '#16a34a'),
                        borderRadius: 4,
                    },
                ],
            },
            plugins: [ChartDataLabels],
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => (ctx.raw > 0 ? '+' : '') + ctx.raw + '%',
                        },
                    },
                    datalabels: {
                        anchor: (ctx) => changeValues[ctx.dataIndex] === null ? 'center' : (changeValues[ctx.dataIndex] > 0 ? 'end' : 'start'),
                        align: (ctx) => changeValues[ctx.dataIndex] === null ? 'center' : (changeValues[ctx.dataIndex] > 0 ? 'end' : 'start'),
                        color: '#374151',
                        font: { weight: 'bold' },
                        formatter: (v, ctx) => {
                            const raw = changeValues[ctx.dataIndex];
                            if (raw === null) return '-';
                            return (raw > 0 ? '+' : '') + raw + '%';
                        },
                    },
                },
                scales: {
                    y: {
                        title: { display: true, text: '% Change' },
                        ticks: { callback: (v) => v + '%' },
                    },
                },
            },
        });

        new Chart(document.getElementById('rateChart'), {
            type: 'bar',
            data: {
                labels: trendData.map(d => d.month),
                datasets: [
                    {
                        label: '₱ / kWh',
                        data: trendData.map(d => d.rate),
                        backgroundColor: '#3b82f6',
                        borderRadius: 4,
                    },
                ],
            },
            plugins: [ChartDataLabels],
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => '₱' + ctx.raw + ' / kWh',
                        },
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: '#374151',
                        font: { weight: 'bold' },
                        formatter: (v) => v === null ? '-' : v.toFixed(2),
                    },
                },
                scales: {
                    y: {
                        title: { display: true, text: '₱ / kWh' },
                        beginAtZero: true,
                    },
                },
            },
        });
    </script>
@endif

@endsection
