@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🚰 {{ $meter->label }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Reading history for this account — add next month's reading right here.
            </p>
        </div>

        <div class="flex gap-2">
            <x-back-button :href="route('water-meters.index')" />
            <a href="{{ route('water-bills.index', ['meter_id' => $meter->id]) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                📊 Full Report
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

    <!-- METER INFO -->
    <div class="border rounded-lg p-5 bg-gray-50 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500 font-semibold">Account No.</p>
                <p class="text-lg font-semibold text-gray-800">{{ $meter->account_no ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-semibold">Service No.</p>
                <p class="text-lg font-semibold text-gray-800">{{ $meter->service_no ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-semibold">Meter No.</p>
                <p class="text-lg font-semibold text-gray-800">{{ $meter->meter_no ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-semibold">Meter Brand</p>
                <p class="text-lg font-semibold text-gray-800">{{ $meter->meter_brand ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- ADD BILL -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-lg">Reading History</h3>
        <button type="button" onclick="openBillModal('add')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
            ➕ Add Bill / Next Reading
        </button>
    </div>

    <div class="overflow-x-auto border rounded-lg">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Month</th>
                    <th class="p-3 text-center">Prev. Reading</th>
                    <th class="p-3 text-center">Pres. Reading</th>
                    <th class="p-3 text-center">Usage</th>
                    <th class="p-3 text-center">vs Previous Month</th>
                    <th class="p-3 text-center">Water Bill (₱)</th>
                    <th class="p-3 text-center">ESF (₱)</th>
                    <th class="p-3 text-center">Total Due (₱)</th>
                    <th class="p-3 text-center">Due Date</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($meter->bills as $bill)
                    @php $usageDiff = $bill->usageDifference(); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $bill->monthLabel() }}</td>
                        <td class="p-3 text-center">{{ $bill->previous_reading !== null ? number_format($bill->previous_reading, 2) : '-' }}</td>
                        <td class="p-3 text-center">{{ $bill->present_reading !== null ? number_format($bill->present_reading, 2) : '-' }}</td>
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
                                    onclick='openBillModal("edit", {{ $bill->id }}, {{ json_encode($bill->report_month) }}, {{ $bill->previous_reading ?? "null" }}, {{ $bill->present_reading ?? "null" }}, {{ $bill->water_bill ?? "null" }}, {{ $bill->esf ?? "null" }}, {{ $bill->amount_after_due_date ?? "null" }}, {{ json_encode($bill->due_date?->format("Y-m-d")) }}, {{ json_encode($bill->meter_reader_name) }}, {{ json_encode($bill->remarks) }})'>
                                    ✏️ Edit
                                </button>
                                <form method="POST" action="{{ route('water-bills.destroy', $bill->id) }}"
                                      onsubmit="return confirm('Remove this bill record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">🗑 Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-6 text-center text-gray-500">No readings recorded yet for this account.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
            <input type="hidden" name="water_meter_id" value="{{ $meter->id }}">

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block mb-1 font-semibold text-sm">For the Month Of</label>
                    <input type="text" id="billReportMonthPicker" placeholder="Click to select month"
                           readonly autocomplete="off"
                           class="w-full border rounded-lg p-3 cursor-pointer bg-white">
                    <input type="hidden" name="report_month" id="billReportMonth" required>
                </div>

                <div></div>

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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<script>

    // Every month this meter already has a bill for — the edit case
    // removes its own month from the check inside openBillModal() so
    // re-saving the same bill never falsely flags itself as a duplicate.
    const takenMonths = @json($meter->bills->pluck('report_month'));
    const billReportMonthInput = document.getElementById('billReportMonth');

    const billMonthPicker = flatpickr("#billReportMonthPicker", {
        plugins: [new monthSelectPlugin({
            shorthand: true,
            dateFormat: "Y-m",
            altFormat: "F Y",
        })],
        onChange: function (selectedDates, dateStr) {

            if (takenMonths.includes(dateStr) && dateStr !== billReportMonthInput.dataset.originalMonth) {
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

    function openBillModal(mode, id, reportMonth, previousReading, presentReading, waterBill, esf, amountAfterDue, dueDate, meterReader, remarks) {

        document.getElementById('billModalTitle').innerText = mode === 'edit' ? 'Edit Bill' : 'Add Bill / Next Reading';
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

            // Carry the most recent reading forward as the new bill's
            // Previous Reading, same "one entry per figure" habit as
            // Energy Conservation Report.
            document.getElementById('billPreviousReading').value = @json(optional($meter->bills->first())->present_reading) ?? '';
        }

        updateBillUsage();
        updateBillTotalDue();

        document.getElementById('billModal').classList.remove('hidden');
    }

    function closeBillModal() {
        document.getElementById('billModal').classList.add('hidden');
    }

</script>

@endsection
