@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 New Property Issuance
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                An ICS or PAR slip is generated automatically based on the items' value.
            </p>
        </div>

        <x-back-button :href="route('property-issuances.index')" />
    </div>

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

    <form method="POST" action="{{ route('property-issuances.store') }}" id="issuanceForm">
        @csrf

        <div class="mb-6">
            <label class="block mb-2 font-semibold">Room <span class="text-gray-400 font-normal">(optional — only if these items are already tracked in a room)</span></label>
            <select id="roomSelect" class="w-full border rounded-lg p-4" onchange="if (this.value) { window.location = '{{ route('property-issuances.create') }}?room_id=' + this.value; } else { window.location = '{{ route('property-issuances.create') }}'; }">
                <option value="">— No room —</option>
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ $room && $room->id === $r->id ? 'selected' : '' }}>
                        {{ $r->room_name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if($room)
            <input type="hidden" name="room_id" value="{{ $room->id }}">

            <h3 class="font-bold text-lg mb-3">Select Items from {{ $room->room_name }}</h3>
            <p class="text-sm text-gray-500 mb-3">Only pick items in the same value bracket — mixed selections must be issued on separate slips.</p>

            @if($room->propertyItems->isEmpty())
                <p class="text-gray-500 mb-6">This room has no property items yet.</p>
            @else
                <div class="overflow-x-auto border rounded-lg mb-6">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-center"></th>
                                <th class="p-3 text-left">Property Name</th>
                                <th class="p-3 text-left">Property No.</th>
                                <th class="p-3 text-center">Qty</th>
                                <th class="p-3 text-center">Unit Value (₱)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($room->propertyItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 text-center">
                                        <input type="checkbox" name="existing_item_ids[]" value="{{ $item->id }}">
                                    </td>
                                    <td class="p-3 font-semibold">{{ $item->property_name }}</td>
                                    <td class="p-3">{{ $item->property_number ?? '-' }}</td>
                                    <td class="p-3 text-center">{{ $item->quantity }}</td>
                                    <td class="p-3 text-center">{{ $item->unit_value !== null ? number_format($item->unit_value, 2) : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif

        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-lg">Items to Issue</h3>
            <button type="button" onclick="addItemRow()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                ➕ Add Item Row
            </button>
        </div>

        <div class="overflow-x-auto border rounded-lg mb-6">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left" style="min-width:180px;">Property Name</th>
                        <th class="p-2 text-left" style="min-width:90px;">Unit</th>
                        <th class="p-2 text-left" style="min-width:110px;">Property No.</th>
                        <th class="p-2 text-center" style="min-width:70px;">Qty</th>
                        <th class="p-2 text-center" style="min-width:110px;">Unit Cost (₱)</th>
                        <th class="p-2 text-center" style="min-width:140px;">Date Acquired</th>
                        <th class="p-2 text-left" style="min-width:120px;">Est. Useful Life</th>
                        <th class="p-2 text-center"></th>
                    </tr>
                </thead>
                <tbody id="itemRows"></tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 font-semibold">Received By (Personnel)</label>
                <select name="recipient_personnel_id" class="w-full border rounded-lg p-4" required>
                    <option value="">Select personnel</option>
                    @foreach($personnel as $person)
                        <option value="{{ $person->id }}" {{ (string) old('recipient_personnel_id') === (string) $person->id ? 'selected' : '' }}>
                            {{ $person->fullname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Date Issued</label>
                <input type="text" id="issued_at" name="issued_at" readonly autocomplete="off"
                       value="{{ old('issued_at', now()->format('Y-m-d')) }}"
                       class="w-full border rounded-lg p-4 cursor-pointer bg-white" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Fund Cluster</label>
                <input type="text" name="fund_cluster" value="{{ old('fund_cluster') }}"
                       class="w-full border rounded-lg p-4">
            </div>

            <div>
                <label class="block mb-2 font-semibold">P.O. No. <span class="text-gray-400 font-normal">(if applicable)</span></label>
                <input type="text" name="po_number" value="{{ old('po_number') }}"
                       class="w-full border rounded-lg p-4">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-semibold">Remarks</label>
                <textarea name="remarks" rows="3" class="w-full border rounded-lg p-4">{{ old('remarks') }}</textarea>
            </div>

        </div>

        <div class="mt-8">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                🧾 Generate Slip
            </button>
        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

    flatpickr("#issued_at", {
        altInput: true,
        altFormat: "M j, Y",
        dateFormat: "Y-m-d",
        defaultDate: document.getElementById('issued_at').value,
    });

    let rowIndex = 0;

    function addItemRow() {
        const tbody = document.getElementById('itemRows');
        const i = rowIndex++;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="p-2"><input type="text" name="items[${i}][property_name]" class="w-full border rounded-lg p-2"></td>
            <td class="p-2"><input type="text" name="items[${i}][unit]" class="w-full border rounded-lg p-2" placeholder="e.g. pc"></td>
            <td class="p-2"><input type="text" name="items[${i}][property_number]" class="w-full border rounded-lg p-2"></td>
            <td class="p-2"><input type="number" name="items[${i}][quantity]" min="1" value="1" class="w-full border rounded-lg p-2 text-center"></td>
            <td class="p-2"><input type="number" name="items[${i}][unit_cost]" step="0.01" min="0" class="w-full border rounded-lg p-2 text-center"></td>
            <td class="p-2"><input type="date" name="items[${i}][date_acquired]" class="w-full border rounded-lg p-2"></td>
            <td class="p-2"><input type="text" name="items[${i}][estimated_useful_life]" class="w-full border rounded-lg p-2" placeholder="e.g. 5 years"></td>
            <td class="p-2 text-center"><button type="button" onclick="this.closest('tr').remove()" class="text-red-600 hover:underline text-sm">🗑</button></td>
        `;

        tbody.appendChild(tr);
    }

    // Start with one blank row so the form isn't empty on first load.
    addItemRow();

</script>

@endsection
