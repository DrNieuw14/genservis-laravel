@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🚰 Water Meters
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Water connections/accounts billed by Carmona Water District.
                Click a meter's name to view its reading history and add bills.
            </p>
        </div>

        <div class="flex gap-2">
            <x-back-button :href="route('water-bills.index')" />
            <button type="button" onclick="openMeterModal('add')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                ➕ Add Meter
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto border rounded-lg">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Label</th>
                    <th class="p-3 text-left">Account No.</th>
                    <th class="p-3 text-left">Service No.</th>
                    <th class="p-3 text-left">Meter No.</th>
                    <th class="p-3 text-left">Meter Brand</th>
                    <th class="p-3 text-center">Bills Recorded</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($meters as $meter)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">
                            <a href="{{ route('water-meters.show', $meter->id) }}" class="text-blue-600 hover:underline">
                                {{ $meter->label }}
                            </a>
                        </td>
                        <td class="p-3">{{ $meter->account_no ?? '-' }}</td>
                        <td class="p-3">{{ $meter->service_no ?? '-' }}</td>
                        <td class="p-3">{{ $meter->meter_no ?? '-' }}</td>
                        <td class="p-3">{{ $meter->meter_brand ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $meter->bills_count }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $meter->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $meter->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex gap-2 justify-center">
                                <a href="{{ route('water-meters.show', $meter->id) }}"
                                   class="text-green-600 hover:underline text-sm">
                                    🧾 Add Bill
                                </a>
                                <button type="button" class="text-blue-600 hover:underline text-sm"
                                    onclick='openMeterModal("edit", {{ $meter->id }}, {{ json_encode($meter->label) }}, {{ json_encode($meter->account_no) }}, {{ json_encode($meter->service_no) }}, {{ json_encode($meter->meter_no) }}, {{ json_encode($meter->meter_brand) }}, {{ $meter->is_active ? "true" : "false" }})'>
                                    ✏️ Edit
                                </button>
                                <form method="POST" action="{{ route('water-meters.destroy', $meter->id) }}"
                                      onsubmit="return confirm('Remove this meter? Only possible if it has no recorded bills.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">🗑 Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">No water meters recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- ADD/EDIT METER MODAL -->
<div id="meterModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 id="meterModalTitle" class="text-xl font-bold">Add Meter</h2>
            <button type="button" onclick="closeMeterModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>
        <form id="meterForm" method="POST" action="{{ route('water-meters.store') }}">
            @csrf
            <input type="hidden" id="meterFormMethod" name="_method" value="POST">
            <div class="p-6 grid grid-cols-1 gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-sm">Label</label>
                    <input type="text" name="label" id="meterLabel" placeholder="e.g. Maduya (2)" class="w-full border rounded-lg p-3" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-semibold text-sm">Account No.</label>
                        <input type="text" name="account_no" id="meterAccountNo" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-sm">Service No.</label>
                        <input type="text" name="service_no" id="meterServiceNo" class="w-full border rounded-lg p-3">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-semibold text-sm">Meter No.</label>
                        <input type="text" name="meter_no" id="meterNo" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-sm">Meter Brand</label>
                        <input type="text" name="meter_brand" id="meterBrand" class="w-full border rounded-lg p-3">
                    </div>
                </div>
                <div id="meterActiveWrap" class="hidden">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="meterIsActive" value="1">
                        <span class="text-sm font-semibold">Active</span>
                    </label>
                </div>
            </div>
            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeMeterModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">💾 Save</button>
            </div>
        </form>
    </div>
</div>

<script>

    function openMeterModal(mode, id, label, accountNo, serviceNo, meterNo, meterBrand, isActive) {
        document.getElementById('meterModalTitle').innerText = mode === 'edit' ? 'Edit Meter' : 'Add Meter';
        document.getElementById('meterLabel').value = label ?? '';
        document.getElementById('meterAccountNo').value = accountNo ?? '';
        document.getElementById('meterServiceNo').value = serviceNo ?? '';
        document.getElementById('meterNo').value = meterNo ?? '';
        document.getElementById('meterBrand').value = meterBrand ?? '';

        const form = document.getElementById('meterForm');
        const activeWrap = document.getElementById('meterActiveWrap');

        if (mode === 'edit') {
            form.action = '{{ url('/water-meters') }}/' + id;
            document.getElementById('meterFormMethod').value = 'PUT';
            activeWrap.classList.remove('hidden');
            document.getElementById('meterIsActive').checked = !!isActive;
        } else {
            form.action = '{{ route('water-meters.store') }}';
            document.getElementById('meterFormMethod').value = 'POST';
            activeWrap.classList.add('hidden');
        }

        document.getElementById('meterModal').classList.remove('hidden');
    }

    function closeMeterModal() {
        document.getElementById('meterModal').classList.add('hidden');
    }

</script>

@endsection
