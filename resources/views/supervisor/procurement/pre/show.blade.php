@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                💰 FY{{ $pre->year }} Program of Receipts and Expenditures
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Prepared by {{ $pre->preparedBy->name ?? '—' }} ·
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                    {{ $pre->status === 'Approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $pre->status }}
                </span>
            </p>

        </div>

        <x-back-button :href="route('procurement.pre.index')" />

    </div>

@if(session('success'))
<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">
{{ session('success') }}
</div>
@endif

    <!-- Income Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        <div class="bg-blue-50 rounded-xl p-5">
            <p class="text-sm text-gray-500">Total Projected Income</p>
            <p class="text-2xl font-bold text-blue-700">₱ {{ number_format($pre->total_projected_income, 2) }}</p>
        </div>

        <div class="bg-purple-50 rounded-xl p-5">
            <p class="text-sm text-gray-500">Total Estimated Expenses (all PPAs)</p>
            <p class="text-2xl font-bold text-purple-700">₱ {{ number_format($pre->total_expenses, 2) }}</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-5">
            <p class="text-sm text-gray-500">Allocation Rows</p>
            <p class="text-2xl font-bold text-gray-700">{{ $pre->allocations->count() }}</p>
        </div>

    </div>

    <!-- Allotment Class / Object of Expenditure Detail -->
    @if($allocationLines->isNotEmpty())

    <div class="mb-8">

        <h3 class="text-xl font-bold text-gray-800 mb-1">📋 Allotment Class / Object of Expenditure</h3>

        <p class="text-gray-500 text-sm mb-4">
            The UACS-level detail behind the PPA totals above — Fund 164, matching the source PRE document.
        </p>

        @foreach($allocationLines as $class => $rows)

        <div class="mb-6">

            <h4 class="font-semibold text-gray-700 mb-2 bg-gray-100 px-3 py-2 rounded">{{ $class }}</h4>

            <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left px-3 py-2">UACS</th>
                        <th class="text-left px-3 py-2">Description</th>
                        @foreach($ppaOrder as $ppa)
                        <th class="text-right px-3 py-2">{{ $ppa }}</th>
                        @endforeach
                        <th class="text-right px-3 py-2 font-bold">Total</th>
                        <th class="text-right px-3 py-2 font-bold text-purple-700">Utilized</th>
                        <th class="text-right px-3 py-2 font-bold text-gray-700">Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr class="border-b">
                        <td class="px-3 py-2 text-gray-500">{{ $row['uacs_code'] }}</td>
                        <td class="px-3 py-2">{{ $row['description'] }}</td>
                        @foreach($ppaOrder as $ppa)
                        <td class="px-3 py-2 text-right">
                            {{ $row['amounts'][$ppa] > 0 ? number_format($row['amounts'][$ppa], 2) : '—' }}
                        </td>
                        @endforeach
                        <td class="px-3 py-2 text-right font-semibold">{{ number_format($row['total'], 2) }}</td>
                        <td class="px-3 py-2 text-right">
                            <button type="button"
                                    onclick="showUtilizationDetail('{{ $row['row_key'] }}')"
                                    class="text-purple-700 underline hover:text-purple-900">
                                {{ $row['total_utilized'] > 0 ? number_format($row['total_utilized'], 2) : '— (view/log)' }}
                            </button>
                        </td>
                        <td class="px-3 py-2 text-right font-semibold
                            {{ ($row['total'] - $row['total_utilized']) < 0 ? 'text-red-600' : 'text-gray-700' }}">
                            {{ number_format($row['total'] - $row['total_utilized'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

        </div>

        @endforeach

        @if($pre->allocations->where('fund_source', '164')->where('ppa', 'GASS')->first()?->mooe > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
            ⚠ Note: the source document's GASS/MOOE ceiling above (₱{{ number_format($pre->allocations->where('fund_source','164')->where('ppa','GASS')->first()->mooe, 2) }}) doesn't have a matching line in this itemized breakdown — the detail pages don't itemize where that amount goes. This is a gap in the source PRE itself, not a data-entry omission here; worth confirming with Rochelle.
        </div>
        @endif

    </div>

    @endif

    <!-- Reconciliation -->
    @if($plans->isNotEmpty())

    <div class="mb-8">

        <h3 class="text-xl font-bold text-gray-800 mb-3">📊 PPMP Reconciliation (FY{{ $pre->year }})</h3>

        <p class="text-gray-500 text-sm mb-4">
            Warn-only — items over their PPA's MOOE + Capital Outlay ceiling are flagged here, submission is never blocked.
        </p>

        @foreach($plans as $row)

        <div class="border rounded-xl p-4 mb-3">

            <p class="font-semibold text-gray-800 mb-2">
                {{ $row['plan']->plan_number }}
                <span class="text-gray-400 font-normal">— {{ $row['plan']->department->department_name ?? 'No Department' }}</span>
            </p>

            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="py-1 pr-4">PPA</th>
                        <th class="py-1 pr-4">Planned (PPMP)</th>
                        <th class="py-1 pr-4">Ceiling (PRE MOOE+CO)</th>
                        <th class="py-1">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($row['reconciliation'] as $ppa => $r)
                    <tr class="border-t">
                        <td class="py-1 pr-4 font-medium">{{ $ppa }}</td>
                        <td class="py-1 pr-4">₱ {{ number_format($r['planned'], 2) }}</td>
                        <td class="py-1 pr-4">₱ {{ number_format($r['ceiling'], 2) }}</td>
                        <td class="py-1">
                            @if($r['over'])
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">⚠ Over Ceiling</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">✔ Within Ceiling</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        @endforeach

    </div>

    @endif

    <!-- Allocation Ceilings -->
    @if(auth()->user()->hasPermission('edit-pre') || auth()->user()->hasPermission('create-pre'))

    <form method="POST" action="{{ route('procurement.pre.allocations.update', $pre->id) }}">
        @csrf
        @method('PUT')

        @foreach(['164' => 'Fund 164 (Regular Income)', '101' => 'Fund 101 (Trust / Misc)'] as $fund => $fundLabel)

        <h3 class="text-xl font-bold text-gray-800 mb-3 mt-6">{{ $fundLabel }}</h3>

        <div class="overflow-x-auto">
        <table class="min-w-full text-sm mb-4">
            <thead>
                <tr class="border-b bg-gray-100">
                    <th class="text-left px-3 py-2">PPA / MFO</th>
                    <th class="text-left px-3 py-2">Personal Services</th>
                    <th class="text-left px-3 py-2">MOOE</th>
                    <th class="text-left px-3 py-2">Capital Outlay</th>
                    <th class="text-left px-3 py-2">Infrastructure</th>
                </tr>
            </thead>
            <tbody>
                @foreach(['GASS', 'STO', 'MFO1', 'MFO2', 'MFO3', 'MFO4'] as $ppa)
                @php $existing = $allocations[$fund][$ppa] ?? null; @endphp
                <tr class="border-b">
                    <td class="px-3 py-2 font-medium">{{ $ppa }}</td>
                    <input type="hidden" name="rows[{{ $fund }}_{{ $ppa }}][fund_source]" value="{{ $fund }}">
                    <input type="hidden" name="rows[{{ $fund }}_{{ $ppa }}][ppa]" value="{{ $ppa }}">
                    <td class="px-3 py-2">
                        <input type="number" step="0.01" min="0"
                               name="rows[{{ $fund }}_{{ $ppa }}][personal_services]"
                               value="{{ $existing->personal_services ?? 0 }}"
                               class="w-32 border rounded px-2 py-1">
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" step="0.01" min="0"
                               name="rows[{{ $fund }}_{{ $ppa }}][mooe]"
                               value="{{ $existing->mooe ?? 0 }}"
                               class="w-32 border rounded px-2 py-1">
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" step="0.01" min="0"
                               name="rows[{{ $fund }}_{{ $ppa }}][capital_outlay]"
                               value="{{ $existing->capital_outlay ?? 0 }}"
                               class="w-32 border rounded px-2 py-1">
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" step="0.01" min="0"
                               name="rows[{{ $fund }}_{{ $ppa }}][infrastructure]"
                               value="{{ $existing->infrastructure ?? 0 }}"
                               class="w-32 border rounded px-2 py-1">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        @endforeach

        <button type="submit"
                class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">
            Save Allocation Ceilings
        </button>

    </form>

    @endif

</div>

<!-- Utilization Detail / Log Entry Modal (shared across all rows) -->
<div id="utilizationModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[85vh] overflow-y-auto">

        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 id="utilizationModalTitle" class="text-xl font-bold">Utilization Detail</h3>
            <button type="button" onclick="closeUtilizationModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>

        <div class="p-6">

            <table class="min-w-full text-sm mb-4">
                <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="py-1 pr-3">PPA</th>
                        <th class="py-1 pr-3">Source</th>
                        <th class="py-1 pr-3">Date</th>
                        <th class="py-1 pr-3">Description</th>
                        <th class="py-1 pr-3 text-right">Amount</th>
                        <th class="py-1"></th>
                    </tr>
                </thead>
                <tbody id="utilizationDetailBody">
                </tbody>
            </table>

            <p id="utilizationEmptyNote" class="text-gray-400 text-sm hidden">No utilization recorded yet.</p>

            <!-- Add Entry form — Personal Services rows only -->
            <div id="utilizationAddEntrySection" class="border-t pt-4 mt-4">

                <h4 class="font-semibold text-gray-700 mb-3">➕ Log Utilization Entry</h4>

                <form id="utilizationAddEntryForm" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                        <div>
                            <label class="text-sm font-semibold">PPA</label>
                            <select id="utilizationPpaSelect" class="w-full border rounded mt-1" onchange="updateUtilizationFormAction()"></select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Amount</label>
                            <input type="number" step="0.01" min="0" name="amount" class="w-full border rounded mt-1" required>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Date</label>
                            <input type="date" name="entry_date" value="{{ date('Y-m-d') }}" class="w-full border rounded mt-1" required>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Note</label>
                            <input type="text" name="note" placeholder="e.g. Payroll, Jan-Jun" class="w-full border rounded mt-1">
                        </div>

                    </div>

                    <button type="submit" class="mt-3 bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold">
                        Log Entry
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<script>

    const utilizationData = @json($utilizationData);

    const preId = {{ $pre->id }};
    const storeUrlTemplate = "/procurement/pre/{{ $pre->id }}/allocation-lines/:line/utilization-entries";
    const destroyUrlTemplate = "/procurement/pre/{{ $pre->id }}/allocation-lines/:line/utilization-entries/:entry";

    function showUtilizationDetail(rowKey)
    {
        const data = utilizationData[rowKey];

        if (!data) {
            return;
        }

        document.getElementById('utilizationModalTitle').innerText = data.uacs_code + ' — ' + data.description;

        const body = document.getElementById('utilizationDetailBody');
        body.innerHTML = '';

        if (data.detail.length === 0) {
            document.getElementById('utilizationEmptyNote').classList.remove('hidden');
        } else {
            document.getElementById('utilizationEmptyNote').classList.add('hidden');
        }

        data.detail.forEach(entry => {

            const tr = document.createElement('tr');
            tr.className = 'border-t';

            let deleteCell = '';

            if (entry.entry_id) {
                deleteCell = `<button type="button" onclick="deleteUtilizationEntry(${entry.line_id}, ${entry.entry_id})" class="text-red-600 hover:underline text-xs">Remove</button>`;
            }

            tr.innerHTML = `
                <td class="py-1 pr-3 font-medium">${entry.ppa}</td>
                <td class="py-1 pr-3">${entry.source}</td>
                <td class="py-1 pr-3">${entry.date}</td>
                <td class="py-1 pr-3">${entry.description}</td>
                <td class="py-1 pr-3 text-right">${Number(entry.amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td class="py-1">${deleteCell}</td>
            `;

            body.appendChild(tr);

        });

        // Manual entries can be logged against any line now (Personal
        // Services, MOOE, or Capital Outlay) — plenty of real MOOE lines
        // (utility bills, service contracts) never go through a Purchase
        // Request either, so this isn't limited to PS anymore.
        const select = document.getElementById('utilizationPpaSelect');
        select.innerHTML = '';

        Object.entries(data.line_ids).forEach(([ppa, lineId]) => {
            if (lineId) {
                const option = document.createElement('option');
                option.value = lineId;
                option.text = ppa;
                select.appendChild(option);
            }
        });

        updateUtilizationFormAction();

        document.getElementById('utilizationModal').classList.remove('hidden');
    }

    function updateUtilizationFormAction()
    {
        const lineId = document.getElementById('utilizationPpaSelect').value;
        document.getElementById('utilizationAddEntryForm').action = storeUrlTemplate.replace(':line', lineId);
    }

    function closeUtilizationModal()
    {
        document.getElementById('utilizationModal').classList.add('hidden');
    }

    function deleteUtilizationEntry(lineId, entryId)
    {
        Swal.fire({
            title: 'Remove this utilization entry?',
            showCancelButton: true,
            confirmButtonText: 'Remove',
            confirmButtonColor: '#d33'
        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = destroyUrlTemplate.replace(':line', lineId).replace(':entry', entryId);

            form.innerHTML = `@csrf @method('DELETE')`;

            document.body.appendChild(form);
            form.submit();

        });
    }

</script>

@endsection
