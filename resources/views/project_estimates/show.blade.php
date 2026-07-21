@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 {{ $estimate->project_name }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $estimate->reference_no }}
                @if($estimate->location)
                    — {{ $estimate->location }}
                @endif
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('project-estimates.index')" />

            <a href="{{ route('project-estimates.edit', $estimate->id) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                ✏️ Edit
            </a>

            <a href="{{ route('project-estimates.print', $estimate->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

            <form method="POST" action="{{ route('project-estimates.destroy', $estimate->id) }}"
                  onsubmit="return confirm('Delete this project estimate and all its items? This cannot be undone.')">
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

    <!-- 1. PROJECT OVERVIEW -->
    <div class="border rounded-lg p-6 bg-gray-50 mb-8">

        <h3 class="text-xl font-semibold text-gray-800 mb-4">1. Project Overview</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">Scope of Work</p>
                <p class="font-semibold text-lg mt-1">{{ $estimate->scope_of_work ?: '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Duration</p>
                <p class="font-semibold text-lg mt-1">{{ $estimate->duration ?: '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Assumptions</p>
                <p class="font-semibold text-lg mt-1">{{ $estimate->assumptions ?: '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Exclusions</p>
                <p class="font-semibold text-lg mt-1">{{ $estimate->exclusions ?: '-' }}</p>
            </div>

            @if($estimate->jobRequest)
                <div>
                    <p class="text-sm text-gray-500">Related Job Request</p>
                    <p class="font-semibold text-lg mt-1">
                        <a href="{{ route('job-requests.show', $estimate->jobRequest->id) }}" class="text-blue-600 hover:underline">
                            {{ $estimate->jobRequest->reference_no }}
                        </a>
                    </p>
                </div>
            @endif

        </div>

    </div>

    <!-- 2. MATERIALS / EQUIPMENT COST ESTIMATE -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-lg">2. Materials / Equipment Cost Estimate</h3>

        <button type="button" onclick="openItemModal('add')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            ➕ Add Item
        </button>
    </div>

    <div class="overflow-x-auto border rounded-lg mb-8">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left w-16">Item No.</th>
                    <th class="p-3 text-left">Description</th>
                    <th class="p-3 text-center">Category</th>
                    <th class="p-3 text-center">Unit</th>
                    <th class="p-3 text-center">Quantity</th>
                    <th class="p-3 text-center">Unit Cost (₱)</th>
                    <th class="p-3 text-center">Total Cost (₱)</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($estimate->items as $index => $item)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $item->description }}</td>
                        <td class="p-3 text-center">{{ $item->categoryLabel() }}</td>
                        <td class="p-3 text-center">{{ $item->unit ?? '-' }}</td>
                        <td class="p-3 text-center">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                        <td class="p-3 text-center">{{ number_format($item->unit_cost, 2) }}</td>
                        <td class="p-3 text-center font-semibold">{{ number_format($item->totalCost(), 2) }}</td>
                        <td class="p-3 text-center">

                            <div class="flex gap-2 justify-center">

                                <button type="button"
                                    class="text-blue-600 hover:underline text-sm"
                                    onclick='openItemModal("edit", {{ $item->id }}, {{ json_encode($item->description) }}, {{ json_encode($item->unit) }}, {{ $item->quantity }}, {{ $item->unit_cost }}, "{{ $item->category }}")'>
                                    ✏️ Edit
                                </button>

                                <form method="POST" action="{{ route('project-estimates.items.destroy', [$estimate->id, $item->id]) }}"
                                      onsubmit="return confirm('Remove this item?')">
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
                            No items yet. Click "Add Item" to start costing this project.
                        </td>
                    </tr>

                @endforelse

            </tbody>

            @if($estimate->items->isNotEmpty())
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="6" class="p-3 text-right">Subtotal</td>
                        <td class="p-3 text-center">₱{{ number_format($estimate->grandTotal(), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif

        </table>

    </div>

    <!-- 3. SUMMARY OF ESTIMATED COSTS -->
    <h3 class="font-bold text-lg mb-3">3. Summary of Estimated Costs</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-blue-100">Materials / Equipment</p>
            <h2 class="text-3xl font-extrabold mt-2">₱{{ number_format($estimate->materialsTotal(), 2) }}</h2>
        </div>

        <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-orange-100">Labor</p>
            <h2 class="text-3xl font-extrabold mt-2">₱{{ number_format($estimate->laborTotal(), 2) }}</h2>
        </div>

        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg text-white p-6">
            <p class="uppercase tracking-wider text-sm text-green-100">Total Project Estimate</p>
            <h2 class="text-3xl font-extrabold mt-2">₱{{ number_format($estimate->grandTotal(), 2) }}</h2>
        </div>

    </div>

    <!-- 4. ATTACHMENTS -->
    <div class="flex items-center justify-between mb-3 mt-8">
        <h3 class="font-bold text-lg">4. Attachments</h3>
    </div>

    <div class="border rounded-lg p-5 bg-gray-50">

        <form method="POST" action="{{ route('project-estimates.photos.store', $estimate->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-wrap items-end gap-4">

                @php $hasBeforePhoto = $estimate->photos->where('type', 'before')->isNotEmpty(); @endphp

                <div>
                    <label class="block mb-1 font-semibold text-sm">Type</label>
                    <select name="type" class="border rounded-lg p-3">
                        <option value="before">📷 Before (Current Status)</option>
                        <option value="receipt">🧾 Receipt</option>
                        <option value="work_done" @disabled(!$hasBeforePhoto)>
                            🛠️ Work Done @if(!$hasBeforePhoto)(upload a Before photo first)@endif
                        </option>
                        <option value="other">📎 Other</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block mb-1 font-semibold text-sm">Photo(s)</label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="w-full border rounded-lg p-2 bg-white">
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    ⬆️ Upload
                </button>

            </div>

        </form>

        <p class="text-gray-500 text-xs mt-2">
            📷 Upload a "Before" photo of the current project status first — "Work Done" photos can't be added until one exists.
        </p>

        @if($estimate->photos->isNotEmpty())

            <div class="mt-6 flex flex-wrap gap-4">

                @foreach($estimate->photos as $photo)

                    <div class="text-center">

                        <a href="{{ $photo->url }}" target="_blank">
                            <img src="{{ $photo->url }}" alt="Attachment"
                                class="w-28 h-28 object-cover rounded-lg border hover:opacity-80 transition">
                        </a>

                        <p class="text-xs text-gray-600 font-semibold mt-1">{{ $photo->typeLabel() }}</p>

                        <p class="text-xs text-gray-500">
                            {{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}
                        </p>

                        <form method="POST" action="{{ route('project-estimates.photos.destroy', [$estimate->id, $photo->id]) }}"
                              onsubmit="return confirm('Remove this photo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">
                                🗑 Remove
                            </button>
                        </form>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-gray-500 text-sm mt-4">No attachments yet — start with a "Before" photo of the current project status.</p>

        @endif

    </div>

</div>

<!-- ADD/EDIT ITEM MODAL -->
<div id="itemModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 id="itemModalTitle" class="text-xl font-bold">Add Item</h2>
            <button type="button" onclick="closeItemModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>

        <form id="itemForm" method="POST" action="{{ route('project-estimates.items.store', $estimate->id) }}">
            @csrf
            <input type="hidden" id="itemFormMethod" name="_method" value="POST">

            <div class="p-6 grid grid-cols-1 gap-4">

                <div>
                    <label class="block mb-1 font-semibold text-sm">Description</label>
                    <input type="text" name="description" id="itemDescription" placeholder="e.g. R-32 Refrigerant"
                        class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Category</label>
                    <select name="category" id="itemCategory" class="w-full border rounded-lg p-3">
                        <option value="materials_equipment">Materials / Equipment</option>
                        <option value="labor">Labor</option>
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-4">

                    <div>
                        <label class="block mb-1 font-semibold text-sm">Unit</label>
                        <input type="text" name="unit" id="itemUnit" placeholder="e.g. kg, pc, lot"
                            class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-sm">Quantity</label>
                        <input type="number" name="quantity" id="itemQuantity" step="0.01" min="0.01"
                            class="w-full border rounded-lg p-3" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-sm">Unit Cost (₱)</label>
                        <input type="number" name="unit_cost" id="itemUnitCost" step="0.01" min="0"
                            class="w-full border rounded-lg p-3" required>
                    </div>

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

    function openItemModal(mode, itemId, description, unit, quantity, unitCost, category) {

        document.getElementById('itemModalTitle').innerText = mode === 'edit' ? 'Edit Item' : 'Add Item';
        document.getElementById('itemDescription').value = description ?? '';
        document.getElementById('itemUnit').value = unit ?? '';
        document.getElementById('itemQuantity').value = quantity ?? '';
        document.getElementById('itemUnitCost').value = unitCost ?? '';
        document.getElementById('itemCategory').value = category ?? 'materials_equipment';

        const form = document.getElementById('itemForm');
        const saveAddAnotherBtn = document.getElementById('itemSaveAddAnother');

        if (mode === 'edit') {
            form.action = '{{ url('/project-estimates/'.$estimate->id.'/items') }}/' + itemId;
            document.getElementById('itemFormMethod').value = 'PUT';
            // Editing always targets one existing item — "Add Another" only
            // makes sense when creating new items.
            saveAddAnotherBtn.classList.add('hidden');
        } else {
            form.action = '{{ route('project-estimates.items.store', $estimate->id) }}';
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
