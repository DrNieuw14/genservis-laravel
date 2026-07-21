@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏠 {{ $room->room_name }}
                @if($room->room_type)
                    <span class="text-sm font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                        {{ $room->room_type }}
                    </span>
                @endif
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $room->building ?? 'No building specified' }}
                @if($room->department)
                    — {{ $room->department->department_name }}
                @endif
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('property-inventory.index')" />

            <a href="{{ route('property-inventory.edit', $room->id) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                ✏️ Edit
            </a>

            <a href="{{ route('property-inventory.print', $room->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

            <form method="POST" action="{{ route('property-inventory.destroy', $room->id) }}"
                  onsubmit="return confirm('Delete this room and all its property items? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    🗑 Delete
                </button>
            </form>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-blue-100">Total Items</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $room->propertyItems->count() }}</h2>
        </div>

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-green-100">Total Value</p>
            <h2 class="text-3xl font-extrabold mt-2">₱{{ number_format($room->totalValue(), 2) }}</h2>
        </div>

    </div>

    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-lg">Property Items</h3>

        <button type="button" onclick="openItemModal('add')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
            ➕ Add Item
        </button>
    </div>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Property Name</th>
                    <th class="p-3 text-left">Property No.</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-center">Unit Value (₱)</th>
                    <th class="p-3 text-center">Total (₱)</th>
                    <th class="p-3 text-center">Date Acquired</th>
                    <th class="p-3 text-center">Condition</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($room->propertyItems as $item)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $item->property_name }}</td>
                        <td class="p-3">{{ $item->property_number ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $item->quantity }}</td>
                        <td class="p-3 text-center">{{ $item->unit_value !== null ? number_format($item->unit_value, 2) : '-' }}</td>
                        <td class="p-3 text-center font-semibold">{{ number_format($item->totalValue(), 2) }}</td>
                        <td class="p-3 text-center">{{ $item->date_acquired?->format('M d, Y') ?? '-' }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $item->conditionBadgeClass() }}">
                                {{ $item->condition }}
                            </span>
                        </td>
                        <td class="p-3 text-center">

                            <div class="flex gap-2 justify-center">

                                <button type="button"
                                    class="text-blue-600 hover:underline text-sm"
                                    onclick='openItemModal("edit", {{ $item->id }}, {{ json_encode($item->property_name) }}, {{ json_encode($item->property_number) }}, {{ json_encode($item->description) }}, {{ $item->quantity }}, {{ $item->unit_value ?? "null" }}, {{ json_encode($item->date_acquired?->format("Y-m-d")) }}, {{ json_encode($item->condition) }}, {{ json_encode($item->remarks) }})'>
                                    ✏️ Edit
                                </button>

                                <form method="POST" action="{{ route('property-inventory.items.destroy', [$room->id, $item->id]) }}"
                                      onsubmit="return confirm('Remove this property item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">
                                        🗑 Remove
                                    </button>
                                </form>

                            </div>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No property items yet. Click "Add Item" to start this room's inventory.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- ADD/EDIT ITEM MODAL -->
<div id="itemModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 id="itemModalTitle" class="text-xl font-bold">Add Item</h2>
            <button type="button" onclick="closeItemModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>

        <form id="itemForm" method="POST" action="{{ route('property-inventory.items.store', $room->id) }}">
            @csrf
            <input type="hidden" id="itemFormMethod" name="_method" value="POST">

            <div class="p-6 grid grid-cols-1 gap-4">

                <div>
                    <label class="block mb-1 font-semibold text-sm">Property Name</label>
                    <input type="text" name="property_name" id="itemPropertyName" placeholder="e.g. Office Chair, Desktop Computer"
                        class="w-full border rounded-lg p-3" required>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-1 font-semibold text-sm">Property No. / Tag</label>
                        <input type="text" name="property_number" id="itemPropertyNumber" placeholder="Optional"
                            class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-sm">Quantity</label>
                        <input type="number" name="quantity" id="itemQuantity" min="1" value="1"
                            class="w-full border rounded-lg p-3" required>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-1 font-semibold text-sm">Unit Value (₱)</label>
                        <input type="number" name="unit_value" id="itemUnitValue" step="0.01" min="0"
                            class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-sm">Date Acquired</label>
                        <input type="date" name="date_acquired" id="itemDateAcquired"
                            class="w-full border rounded-lg p-3">
                    </div>

                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Condition</label>
                    <select name="condition" id="itemCondition" class="w-full border rounded-lg p-3">
                        @foreach(\App\Models\PropertyItem::CONDITIONS as $condition)
                            <option value="{{ $condition }}">{{ $condition }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Description</label>
                    <textarea name="description" id="itemDescription" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Remarks</label>
                    <textarea name="remarks" id="itemRemarks" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>

            </div>

            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeItemModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">
                    Cancel
                </button>
                <button type="submit" name="action" value="add_another" id="itemSaveAddAnother"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
                    ➕ Save & Add Another
                </button>
                <button type="submit" name="action" value="done"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">
                    💾 Save
                </button>
            </div>

        </form>

    </div>

</div>

<script>

    function openItemModal(mode, itemId, propertyName, propertyNumber, description, quantity, unitValue, dateAcquired, condition, remarks) {

        document.getElementById('itemModalTitle').innerText = mode === 'edit' ? 'Edit Item' : 'Add Item';
        document.getElementById('itemPropertyName').value = propertyName ?? '';
        document.getElementById('itemPropertyNumber').value = propertyNumber ?? '';
        document.getElementById('itemDescription').value = description ?? '';
        document.getElementById('itemQuantity').value = quantity ?? 1;
        document.getElementById('itemUnitValue').value = unitValue ?? '';
        document.getElementById('itemDateAcquired').value = dateAcquired ?? '';
        document.getElementById('itemCondition').value = condition ?? 'Good';
        document.getElementById('itemRemarks').value = remarks ?? '';

        const form = document.getElementById('itemForm');
        const saveAddAnotherBtn = document.getElementById('itemSaveAddAnother');

        if (mode === 'edit') {
            form.action = '{{ url('/property-inventory/'.$room->id.'/items') }}/' + itemId;
            document.getElementById('itemFormMethod').value = 'PUT';
            saveAddAnotherBtn.classList.add('hidden');
        } else {
            form.action = '{{ route('property-inventory.items.store', $room->id) }}';
            document.getElementById('itemFormMethod').value = 'POST';
            saveAddAnotherBtn.classList.remove('hidden');
        }

        document.getElementById('itemModal').classList.remove('hidden');
    }

    function closeItemModal() {
        document.getElementById('itemModal').classList.add('hidden');
    }

    @if(session('reopen_add_item'))
        document.addEventListener('DOMContentLoaded', function () {
            openItemModal('add');
        });
    @endif

</script>

@endsection
