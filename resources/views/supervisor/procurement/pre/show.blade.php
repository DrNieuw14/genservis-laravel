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

@endsection
