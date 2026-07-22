@php
    $medicine = $medicine ?? null;
    $old = fn ($field, $default = null) => old($field, $medicine?->{$field} ?? $default);
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@if ($errors->any())
    <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $medicine ? route('clinic-medicines.update', $medicine->id) : route('clinic-medicines.store') }}">
    @csrf
    @if($medicine) @method('PUT') @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block mb-2 font-semibold">Name</label>
            <input type="text" name="name" value="{{ $old('name') }}" class="w-full border rounded-lg p-4" required>
        </div>
        <div>
            <label class="block mb-2 font-semibold">Brand (optional)</label>
            <input type="text" name="brand" value="{{ $old('brand') }}" class="w-full border rounded-lg p-4">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
            <label class="block mb-2 font-semibold">Unit</label>
            <input type="text" name="unit" value="{{ $old('unit') }}" placeholder="e.g. Tablet, Bottle" class="w-full border rounded-lg p-4">
        </div>
        <div>
            <label class="block mb-2 font-semibold">Current Stock</label>
            <input type="number" min="0" name="current_stock" value="{{ $old('current_stock', 0) }}" class="w-full border rounded-lg p-4" required>
        </div>
        <div>
            <label class="block mb-2 font-semibold">Initial Stock Received (optional)</label>
            <input type="number" min="0" name="quantity_received" value="{{ $old('quantity_received') }}" class="w-full border rounded-lg p-4">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block mb-2 font-semibold">Expiration Date (optional)</label>
            <input type="text" id="expiration_picker" name="expiration_date" value="{{ $old('expiration_date') }}"
                autocomplete="off" class="w-full border rounded-lg p-4">
        </div>
    </div>

    <div class="mb-8">
        <label class="block mb-2 font-semibold">Notes (optional)</label>
        <textarea name="notes" rows="3" class="w-full border rounded-lg p-4">{{ $old('notes') }}</textarea>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
            💾 Save
        </button>
        <a href="{{ route('clinic-medicines.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-8 py-3 rounded-lg">
            Cancel
        </a>
    </div>

</form>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr("#expiration_picker", { altInput: true, altFormat: "F Y", dateFormat: "Y-m-d", defaultDate: document.getElementById('expiration_picker').value });
</script>
