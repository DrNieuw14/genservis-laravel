@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🚰 Water Bill Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Carmona Water District billing notices, tracked per meter/account.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('water-meters.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                🚰 Manage Meters
            </a>
            <a href="{{ route('water-bills.print', request()->query()) }}" target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FILTERS -->
    <form method="GET" action="{{ route('water-bills.index') }}" class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <div>
                <label class="block mb-1 font-semibold text-sm">Meter</label>
                <select name="meter_id" class="w-full border rounded-lg p-3">
                    <option value="">All Meters</option>
                    @foreach($meters as $meter)
                        <option value="{{ $meter->id }}" {{ (string) $meterId === (string) $meter->id ? 'selected' : '' }}>
                            {{ $meter->label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">From Month</label>
                <input type="text" id="filterMonthFromPicker" placeholder="Click to select month"
                       readonly autocomplete="off"
                       class="w-full border rounded-lg p-3 cursor-pointer bg-white">
                <input type="hidden" name="month_from" id="filterMonthFrom" value="{{ $monthFrom }}">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-sm">To Month</label>
                <input type="text" id="filterMonthToPicker" placeholder="Click to select month"
                       readonly autocomplete="off"
                       class="w-full border rounded-lg p-3 cursor-pointer bg-white">
                <input type="hidden" name="month_to" id="filterMonthTo" value="{{ $monthTo }}">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    🔍 Filter
                </button>

                @if($meterId || $monthFrom || $monthTo)
                    <a href="{{ route('water-bills.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                        Clear
                    </a>
                @endif
            </div>

        </div>

    </form>

    <!-- TREND CHARTS -->
    @if($chartMonthOptions->isNotEmpty())
        <div class="mb-6">
            <form method="GET" action="{{ route('water-bills.index') }}" class="flex flex-wrap items-end gap-3 mb-4">
                <input type="hidden" name="meter_id" value="{{ $meterId }}">
                <input type="hidden" name="month_from" value="{{ $monthFrom }}">
                <input type="hidden" name="month_to" value="{{ $monthTo }}">

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">From month</label>
                    <select name="chart_from" class="border rounded-lg p-2">
                        <option value="">Earliest</option>
                        @foreach($chartMonthOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $chartFrom === $opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">To month</label>
                    <select name="chart_to" class="border rounded-lg p-2">
                        <option value="">Latest</option>
                        @foreach($chartMonthOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $chartTo === $opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Filter</button>
                @if($chartFrom || $chartTo)
                    <a href="{{ route('water-bills.index', array_filter(['meter_id' => $meterId, 'month_from' => $monthFrom, 'month_to' => $monthTo])) }}" class="text-sm text-gray-500 hover:underline">Clear</a>
                @endif
            </form>

            @if($chartData->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                    <div class="border rounded-lg p-5">
                        <h3 class="font-bold text-lg mb-3">📊 Water Bill and Usage Trend</h3>
                        <canvas id="waterBillTrendChart" height="110"></canvas>
                    </div>
                    <div class="border rounded-lg p-5">
                        <h3 class="font-bold text-lg mb-3">📉 Usage Change vs. Previous Month</h3>
                        <canvas id="usageChangeChart" height="110"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="border rounded-lg p-5">
                        <h3 class="font-bold text-lg mb-3">💧 Water Cost per m³</h3>
                        <canvas id="waterRateChart" height="110"></canvas>
                    </div>

                    <div class="border rounded-lg p-5">
                        <h3 class="font-bold text-lg mb-3">
                            💰 Rate Charge This Month
                            @if($latestChartRow)
                                ({{ $latestChartRow['month'] }})
                            @endif
                        </h3>

                        @php
                            $prevRate = $previousChartRow['rate'] ?? null;
                            $curRate = $latestChartRow['rate'] ?? null;
                            $waterRateDiff = ($prevRate !== null && $curRate !== null) ? round($curRate - $prevRate, 2) : null;
                            $waterRateDiffPercent = ($waterRateDiff !== null && $prevRate) ? round(($waterRateDiff / $prevRate) * 100, 2) : null;
                            $maxWaterRateScale = max($prevRate ?? 0, $curRate ?? 0, 1);
                            $waterRateDown = $waterRateDiff !== null && $waterRateDiff <= 0;
                        @endphp

                        @if($prevRate === null || $curRate === null)
                            <p class="text-gray-500 text-sm">Not enough data yet to compare rates — needs both this month's and the previous month's bill/usage figures.</p>
                        @else
                            <div class="space-y-4 mt-4">
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-1">
                                        <span>Previous Rate (₱/m³)</span>
                                        <span>₱{{ number_format($prevRate, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-6">
                                        <div class="bg-gray-400 h-6 rounded-full" style="width: {{ $prevRate / $maxWaterRateScale * 100 }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-1">
                                        <span>Current Rate (₱/m³)</span>
                                        <span>₱{{ number_format($curRate, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-6">
                                        <div class="{{ $waterRateDown ? 'bg-green-500' : 'bg-red-500' }} h-6 rounded-full" style="width: {{ $curRate / $maxWaterRateScale * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-4 rounded-lg {{ $waterRateDown ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <p class="font-semibold {{ $waterRateDown ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $waterRateDown ? '✅' : '⚠️' }}
                                    Rate {{ $waterRateDown ? 'decreased' : 'increased' }} by ₱{{ number_format(abs($waterRateDiff), 2) }}/m³
                                    {{ $waterRateDown ? '— RATE DOWN' : '— RATE UP' }}
                                </p>
                                <p class="text-sm {{ $waterRateDown ? 'text-green-600' : 'text-red-600' }} mt-1">
                                    The utility's charge is {{ number_format(abs($waterRateDiffPercent), 1) }}% {{ $waterRateDown ? 'lower' : 'higher' }} than last month.
                                    {{ $waterRateDown ? 'Good news for the budget.' : 'This is the utility rate itself, not usage — conservation measures won\'t offset it.' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-sm">No bills fall within the selected month range.</p>
            @endif
        </div>
    @endif

    <!-- ADD BILL -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-lg">Recorded Bills</h3>
        @if($meters->isNotEmpty())
            <button type="button" onclick="openBillModal('add')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                ➕ Add Bill
            </button>
        @else
            <a href="{{ route('water-meters.index') }}" class="text-blue-600 hover:underline text-sm">
                Add a water meter first to start recording bills →
            </a>
        @endif
    </div>

    <div class="overflow-x-auto border rounded-lg">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Meter</th>
                    <th class="p-3 text-left">Month</th>
                    <th class="p-3 text-center">Prev → Present</th>
                    <th class="p-3 text-center">Usage</th>
                    <th class="p-3 text-center">vs Previous Month</th>
                    <th class="p-3 text-center">Rate (₱/m³)</th>
                    <th class="p-3 text-center">Water Bill (₱)</th>
                    <th class="p-3 text-center">ESF (₱)</th>
                    <th class="p-3 text-center">Total Due (₱)</th>
                    <th class="p-3 text-center">Due Date</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($bills as $bill)
                    @php $usageDiff = $bill->usageDifference(); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $bill->meter->label ?? '-' }}</td>
                        <td class="p-3">{{ $bill->monthLabel() }}</td>
                        <td class="p-3 text-center">
                            {{ $bill->previous_reading !== null ? number_format($bill->previous_reading, 2) : '-' }}
                            →
                            {{ $bill->present_reading !== null ? number_format($bill->present_reading, 2) : '-' }}
                        </td>
                        <td class="p-3 text-center font-semibold">{{ $bill->usage() !== null ? number_format($bill->usage(), 2) : '-' }}</td>
                        <td class="p-3 text-center">
                            @if($usageDiff === null)
                                <span class="text-gray-400 text-xs">-</span>
                            @elseif($usageDiff < 0)
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-green-100 text-green-700">
                                    🔻 Saved {{ number_format(abs($usageDiff), 2) }} ({{ $bill->usagePercentChange() }}%)
                                </span>
                            @elseif($usageDiff > 0)
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-red-100 text-red-700">
                                    🔺 +{{ number_format($usageDiff, 2) }} ({{ $bill->usagePercentChange() }}%)
                                </span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full font-semibold bg-gray-100 text-gray-600">No change</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">{{ $bill->currentRate() !== null ? number_format($bill->currentRate(), 2) : '-' }}</td>
                        <td class="p-3 text-center">{{ $bill->water_bill !== null ? number_format($bill->water_bill, 2) : '-' }}</td>
                        <td class="p-3 text-center">{{ $bill->esf !== null ? number_format($bill->esf, 2) : '-' }}</td>
                        <td class="p-3 text-center font-semibold">{{ $bill->totalDue() !== null ? number_format($bill->totalDue(), 2) : '-' }}</td>
                        <td class="p-3 text-center">
                            {{ $bill->due_date?->format('M d, Y') ?? '-' }}
                            @if($bill->isOverdue())
                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-semibold ml-1">Overdue</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex gap-2 justify-center">
                                <button type="button" class="text-blue-600 hover:underline text-sm"
                                    onclick='openBillModal("edit", {{ $bill->id }}, {{ $bill->water_meter_id }}, {{ json_encode($bill->report_month) }}, {{ $bill->previous_reading ?? "null" }}, {{ $bill->present_reading ?? "null" }}, {{ $bill->water_bill ?? "null" }}, {{ $bill->esf ?? "null" }}, {{ $bill->amount_after_due_date ?? "null" }}, {{ json_encode($bill->due_date?->format("Y-m-d")) }}, {{ json_encode($bill->meter_reader_name) }}, {{ json_encode($bill->remarks) }})'>
                                    ✏️ Edit
                                </button>
                                <form method="POST" action="{{ route('water-bills.destroy', $bill->id) }}"
                                      onsubmit="return genservisConfirm(event, 'Remove this bill record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">🗑 Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="p-6 text-center text-gray-500">No water bills recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bills->links() }}
    </div>

</div>

<!-- ADD/EDIT BILL MODAL -->
<div id="billModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 id="billModalTitle" class="text-xl font-bold">Add Bill</h2>
            <button type="button" onclick="closeBillModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>
        <form id="billForm" method="POST" action="{{ route('water-bills.store') }}">
            @csrf
            <input type="hidden" id="billFormMethod" name="_method" value="POST">

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block mb-1 font-semibold text-sm">Meter</label>
                    <select name="water_meter_id" id="billMeterId" class="w-full border rounded-lg p-3" required>
                        <option value="">Select meter</option>
                        @foreach($meters as $meter)
                            <option value="{{ $meter->id }}">{{ $meter->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">For the Month Of</label>
                    <input type="text" id="billReportMonthPicker" placeholder="Click to select month"
                           readonly autocomplete="off"
                           class="w-full border rounded-lg p-3 cursor-pointer bg-white">
                    <input type="hidden" name="report_month" id="billReportMonth" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Present Reading</label>
                    <input type="number" step="0.01" min="0" name="present_reading" id="billPresentReading" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Previous Reading</label>
                    <input type="number" step="0.01" min="0" name="previous_reading" id="billPreviousReading" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Usage</label>
                    <input type="text" id="billUsage" readonly disabled class="w-full border rounded-lg p-3 bg-gray-100 text-gray-600">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Water Bill (₱)</label>
                    <input type="number" step="0.01" min="0" name="water_bill" id="billWaterBill" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">ESF (₱)</label>
                    <input type="number" step="0.01" min="0" name="esf" id="billEsf" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Total Due (₱)</label>
                    <input type="text" id="billTotalDue" readonly disabled class="w-full border rounded-lg p-3 bg-gray-100 text-gray-600">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Amount After Due Date (₱)</label>
                    <input type="number" step="0.01" min="0" name="amount_after_due_date" id="billAmountAfterDue" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Due Date</label>
                    <input type="text" name="due_date" id="billDueDate" readonly autocomplete="off"
                           placeholder="Click to select date"
                           class="w-full border rounded-lg p-3 cursor-pointer bg-white">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Meter Reader</label>
                    <input type="text" name="meter_reader_name" id="billMeterReader" class="w-full border rounded-lg p-3">
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-1 font-semibold text-sm">Remarks</label>
                    <textarea name="remarks" id="billRemarks" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>

            </div>

            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeBillModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">💾 Save</button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<script>

    flatpickr("#filterMonthFromPicker", {
        plugins: [new monthSelectPlugin({ shorthand: true, dateFormat: "Y-m", altFormat: "F Y" })],
        defaultDate: @json($monthFrom ?: null),
        onChange: function (selectedDates, dateStr) {
            document.getElementById('filterMonthFrom').value = dateStr;
        },
    });

    flatpickr("#filterMonthToPicker", {
        plugins: [new monthSelectPlugin({ shorthand: true, dateFormat: "Y-m", altFormat: "F Y" })],
        defaultDate: @json($monthTo ?: null),
        onChange: function (selectedDates, dateStr) {
            document.getElementById('filterMonthTo').value = dateStr;
        },
    });

    // Months already billed, per meter — the picker warns if you pick one
    // that's taken for whichever meter is currently selected.
    const takenMonthsByMeter = @json($meters->mapWithKeys(fn($m) => [$m->id => $m->bills->pluck('report_month')]));
    const billReportMonthInput = document.getElementById('billReportMonth');

    const billMonthPicker = flatpickr("#billReportMonthPicker", {
        plugins: [new monthSelectPlugin({
            shorthand: true,
            dateFormat: "Y-m",
            altFormat: "F Y",
        })],
        onChange: function (selectedDates, dateStr) {

            const meterId = document.getElementById('billMeterId').value;
            const taken = takenMonthsByMeter[meterId] || [];

            if (taken.includes(dateStr) && dateStr !== billReportMonthInput.dataset.originalMonth) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Already recorded',
                    text: 'A bill for this meter and month has already been recorded — pick a different one.',
                    confirmButtonColor: '#2563eb',
                });
                billMonthPicker.clear();
                billReportMonthInput.value = '';
                return;
            }

            billReportMonthInput.value = dateStr;
        },
    });

    const billDueDatePicker = flatpickr("#billDueDate", {
        altInput: true,
        altFormat: "M j, Y",
        dateFormat: "Y-m-d",
    });

    function openBillModal(mode, id, meterId, reportMonth, previousReading, presentReading, waterBill, esf, amountAfterDue, dueDate, meterReader, remarks) {

        document.getElementById('billModalTitle').innerText = mode === 'edit' ? 'Edit Bill' : 'Add Bill';
        document.getElementById('billMeterId').value = meterId ?? '';
        billReportMonthInput.dataset.originalMonth = mode === 'edit' ? (reportMonth ?? '') : '';
        billMonthPicker.setDate(reportMonth ?? null, true);
        document.getElementById('billPreviousReading').value = previousReading ?? '';
        document.getElementById('billPresentReading').value = presentReading ?? '';
        document.getElementById('billWaterBill').value = waterBill ?? '';
        document.getElementById('billEsf').value = esf ?? '';
        document.getElementById('billAmountAfterDue').value = amountAfterDue ?? '';
        billDueDatePicker.setDate(dueDate ?? null);
        document.getElementById('billMeterReader').value = meterReader ?? '';
        document.getElementById('billRemarks').value = remarks ?? '';

        const form = document.getElementById('billForm');

        if (mode === 'edit') {
            form.action = '{{ url('/water-bills') }}/' + id;
            document.getElementById('billFormMethod').value = 'PUT';
        } else {
            form.action = '{{ route('water-bills.store') }}';
            document.getElementById('billFormMethod').value = 'POST';
        }

        updateBillUsage();
        updateBillTotalDue();

        document.getElementById('billModal').classList.remove('hidden');
    }

    function closeBillModal() {
        document.getElementById('billModal').classList.add('hidden');
    }

    // Usage and Total Due are always computed, never typed in — matches
    // this app's "trust the reading/figure difference" convention (same as
    // Energy Conservation Report's consumption).
    function updateBillUsage() {
        const present = parseFloat(document.getElementById('billPresentReading').value);
        const previous = parseFloat(document.getElementById('billPreviousReading').value);
        const field = document.getElementById('billUsage');
        field.value = (!isNaN(present) && !isNaN(previous)) ? (present - previous).toFixed(2) : '';
    }

    function updateBillTotalDue() {
        const waterBill = parseFloat(document.getElementById('billWaterBill').value) || 0;
        const esf = parseFloat(document.getElementById('billEsf').value) || 0;
        const hasAny = document.getElementById('billWaterBill').value !== '' || document.getElementById('billEsf').value !== '';
        document.getElementById('billTotalDue').value = hasAny ? (waterBill + esf).toFixed(2) : '';
    }

    document.getElementById('billPresentReading').addEventListener('input', updateBillUsage);
    document.getElementById('billPreviousReading').addEventListener('input', updateBillUsage);
    document.getElementById('billWaterBill').addEventListener('input', updateBillTotalDue);
    document.getElementById('billEsf').addEventListener('input', updateBillTotalDue);

    // Auto-fill Meter + Previous Reading from that meter's most recent
    // bill when adding a new one, same "carry last figure forward" habit
    // as Energy Conservation Report — only for Add, never overwrites Edit.
    document.getElementById('billMeterId').addEventListener('change', function () {
        if (document.getElementById('billFormMethod').value === 'PUT') {
            return;
        }
        const latestByMeter = @json($meters->mapWithKeys(fn($m) => [$m->id => optional($m->latestBill())->present_reading]));
        const val = latestByMeter[this.value];
        document.getElementById('billPreviousReading').value = val ?? '';
        updateBillUsage();
    });

    // Arriving from the "🧾 Add Bill" shortcut on the Water Meters page —
    // open the modal automatically with that meter already selected.
    @if(request()->boolean('add_bill') && $meterId)
        document.addEventListener('DOMContentLoaded', function () {
            openBillModal('add');
            const meterSelect = document.getElementById('billMeterId');
            meterSelect.value = '{{ $meterId }}';
            meterSelect.dispatchEvent(new Event('change'));
        });
    @endif

    @if($chartData->count() > 0)
        const trendData = @json($chartData);

        new Chart(document.getElementById('waterBillTrendChart'), {
            type: 'line',
            data: {
                labels: trendData.map(d => d.month),
                datasets: [
                    {
                        label: 'Water Bill (₱)',
                        data: trendData.map(d => d.bill),
                        borderColor: '#2563eb',
                        backgroundColor: '#2563eb',
                        yAxisID: 'yBill',
                        tension: 0.3,
                    },
                    {
                        label: 'Usage',
                        data: trendData.map(d => d.usage),
                        borderColor: '#7c3aed',
                        backgroundColor: '#7c3aed',
                        yAxisID: 'yUsage',
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    yBill: { type: 'linear', position: 'left', title: { display: true, text: '₱' } },
                    yUsage: { type: 'linear', position: 'right', title: { display: true, text: 'Usage' }, grid: { drawOnChartArea: false } },
                },
            },
        });

        // Percent change is null for the first month in range (no prior
        // month to compare against) — plot it as a zero-height bar rather
        // than breaking the chart; the datalabel below still shows "-" for it.
        const usageChangeValues = trendData.map(d => d.usagePercentChange);
        const usageChangeData = usageChangeValues.map(v => v ?? 0);

        new Chart(document.getElementById('usageChangeChart'), {
            type: 'bar',
            data: {
                labels: trendData.map(d => d.month),
                datasets: [
                    {
                        label: '% Change',
                        data: usageChangeData,
                        backgroundColor: usageChangeData.map(v => v > 0 ? '#ef4444' : '#16a34a'),
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
                        anchor: (ctx) => usageChangeValues[ctx.dataIndex] === null ? 'center' : (usageChangeValues[ctx.dataIndex] > 0 ? 'end' : 'start'),
                        align: (ctx) => usageChangeValues[ctx.dataIndex] === null ? 'center' : (usageChangeValues[ctx.dataIndex] > 0 ? 'end' : 'start'),
                        color: '#374151',
                        font: { weight: 'bold' },
                        formatter: (v, ctx) => {
                            const raw = usageChangeValues[ctx.dataIndex];
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

        new Chart(document.getElementById('waterRateChart'), {
            type: 'bar',
            data: {
                labels: trendData.map(d => d.month),
                datasets: [
                    {
                        label: '₱ / m³',
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
                            label: (ctx) => '₱' + ctx.raw + ' / m³',
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
                        title: { display: true, text: '₱ / m³' },
                        beginAtZero: true,
                    },
                },
            },
        });
    @endif

</script>

@endsection
