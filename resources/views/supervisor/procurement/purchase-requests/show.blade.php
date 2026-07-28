@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 {{ $pr->pr_number }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $pr->plan->plan_number }} ·
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                    {{ match($pr->status) {
                        'Approved' => 'bg-blue-100 text-blue-700',
                        'Completed' => 'bg-green-100 text-green-700',
                        'Rejected' => 'bg-red-100 text-red-700',
                        default => 'bg-yellow-100 text-yellow-700',
                    } }}">
                    {{ $pr->status }}
                </span>
            </p>

        </div>

        <x-back-button :href="route('procurement.purchase-requests.index')" />

    </div>

@if(session('success'))
<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6 text-lg">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 text-lg">{{ session('error') }}</div>
@endif

    <!-- Details -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div>
            <p class="text-sm text-gray-500">Date</p>
            <p class="font-semibold">{{ $pr->pr_date->format('M d, Y') }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Requested By</p>
            <p class="font-semibold">{{ $pr->requestedBy->name ?? '—' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Approved By</p>
            <p class="font-semibold">{{ $pr->approvedBy->name ?? '—' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Total Cost</p>
            <p class="font-semibold text-green-700">₱ {{ number_format($pr->total_cost, 2) }}</p>
        </div>

    </div>

    @if($pr->purpose)
    <div class="mb-6">
        <p class="text-sm text-gray-500">Purpose</p>
        <p>{{ $pr->purpose }}</p>
    </div>
    @endif

    <!-- Action buttons -->
    <div class="flex flex-wrap gap-3 mb-8">

        @if($pr->status === 'Draft')

            @if(auth()->user()->hasPermission('approve-purchase-requests'))
            <form action="{{ route('procurement.purchase-requests.approve', $pr->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold">
                    ✔ Approve
                </button>
            </form>

            <button type="button" onclick="confirmReject()" class="bg-red-100 text-red-700 hover:bg-red-200 px-5 py-2 rounded-lg font-semibold">
                ✕ Reject
            </button>

            <form id="rejectForm" action="{{ route('procurement.purchase-requests.reject', $pr->id) }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="reason" id="rejectReason">
            </form>
            @endif

            @if(auth()->user()->hasPermission('edit-purchase-requests'))
            <a href="{{ route('procurement.purchase-requests.edit', $pr->id) }}" class="bg-gray-100 hover:bg-gray-200 px-5 py-2 rounded-lg font-semibold">
                ✎ Edit Header
            </a>
            @endif

        @elseif($pr->status === 'Approved')

            @if(auth()->user()->hasPermission('approve-purchase-requests'))
            <form action="{{ route('procurement.purchase-requests.complete', $pr->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold">
                    ✔ Mark Completed
                </button>
            </form>
            @endif

        @endif

    </div>

    <!-- Items -->
    <h3 class="text-xl font-bold text-gray-800 mb-3">Line Items</h3>

    <div class="overflow-x-auto mb-6">
    <table class="min-w-full text-sm">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left px-3 py-2">Material</th>
                <th class="text-left px-3 py-2">UACS</th>
                <th class="text-left px-3 py-2">PPA</th>
                <th class="text-right px-3 py-2">Qty</th>
                <th class="text-right px-3 py-2">Unit Cost</th>
                <th class="text-right px-3 py-2">Total</th>
                @if($pr->status === 'Draft')
                <th class="text-center px-3 py-2">Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($pr->items as $item)
            <tr class="border-b">
                <td class="px-3 py-2">{{ $item->planItem->material_name }}</td>
                <td class="px-3 py-2 text-gray-500">{{ optional(optional($item->planItem->material)->classification)->uacs_code ?? '—' }}</td>
                <td class="px-3 py-2">
                    @if($item->planItem->ppa)
                    <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full text-xs font-semibold">{{ $item->planItem->ppa }}</span>
                    @else
                    <span class="text-gray-400 text-xs">Untagged</span>
                    @endif
                </td>
                <td class="px-3 py-2 text-right">{{ $item->quantity_requested }}</td>
                <td class="px-3 py-2 text-right">₱ {{ number_format($item->unit_cost, 2) }}</td>
                <td class="px-3 py-2 text-right font-semibold">₱ {{ number_format($item->total_cost, 2) }}</td>
                @if($pr->status === 'Draft')
                <td class="px-3 py-2 text-center">
                    <form action="{{ route('procurement.purchase-requests.items.destroy', [$pr->id, $item->id]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Remove</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-6 text-gray-500">No items yet</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($pr->status === 'Draft')

    <div class="border rounded-xl p-5 bg-gray-50">

        <h4 class="font-semibold text-gray-800 mb-3">➕ Add Item from {{ $pr->plan->plan_number }}</h4>

        <form action="{{ route('procurement.purchase-requests.items.store', $pr->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf

            <div class="md:col-span-2">
                <label class="text-sm font-semibold">Planned Item</label>
                <select id="planItemSelect" name="procurement_plan_item_id" class="w-full border rounded mt-1" required
                        onchange="prefillUnitCost()">
                    <option value="">-- Select Item --</option>
                    @foreach($availableItems as $planItem)
                    <option value="{{ $planItem->id }}" data-unit-cost="{{ $planItem->estimated_unit_cost }}" data-ppa="{{ $planItem->ppa ?? '' }}">
                        {{ $planItem->material_name }} ({{ $planItem->ppa ?? 'untagged' }}) — ₱{{ number_format($planItem->estimated_unit_cost, 2) }}/unit
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold">Quantity</label>
                <input type="number" name="quantity_requested" id="qtyInput" min="1" value="1" class="w-full border rounded mt-1" required>
            </div>

            <div>
                <label class="text-sm font-semibold">Unit Cost</label>
                <input type="number" step="0.01" name="unit_cost" id="unitCostInput" class="w-full border rounded mt-1" required>
            </div>

            <div class="md:col-span-3">
                <label class="text-sm font-semibold">Notes (optional)</label>
                <input type="text" name="notes" class="w-full border rounded mt-1">
            </div>

            <div class="flex items-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold w-full">
                    Add Item
                </button>
            </div>

        </form>

        <p id="untaggedWarning" class="hidden text-amber-700 text-sm mt-3">
            ⚠ This planned item has no PPA tag yet — it won't count toward any PRE ceiling until it's tagged (via Bulk-Tag PPA on the plan page).
        </p>

    </div>

    @endif

</div>

<script>

    function prefillUnitCost()
    {
        const select = document.getElementById('planItemSelect');
        const option = select.options[select.selectedIndex];

        if (!option || !option.value) {
            return;
        }

        document.getElementById('unitCostInput').value = option.dataset.unitCost;

        document.getElementById('untaggedWarning').classList.toggle('hidden', option.dataset.ppa !== '');
    }

    function confirmReject()
    {
        Swal.fire({
            title: 'Reject this Purchase Request?',
            input: 'text',
            inputLabel: 'Reason (optional)',
            showCancelButton: true,
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('rejectReason').value = result.value || '';
                document.getElementById('rejectForm').submit();
            }
        });
    }

</script>

@endsection
