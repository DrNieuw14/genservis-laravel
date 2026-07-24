@extends('layouts.app')

@section('content')

@php
    $field = fn ($label, $value) => '<p class="text-base text-gray-500">' . $label . '</p><p class="font-semibold text-xl mt-1">' . ($value !== null && $value !== '' ? e($value) : '-') . '</p>';
    $checklistLabel = fn ($group, $constant) => collect($consultation->{$group} ?? [])
        ->map(fn ($key) => $constant[$key] ?? $key)
        ->implode(', ') ?: '-';
@endphp

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🩺 {{ $consultation->patient_name }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $consultation->case_no }} — {{ $consultation->consultation_date->format('M d, Y') }}
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="$canManage ? route('health-consultations.index') : route('health-consultations.mine')" />

            @if($canManage)

                <a href="{{ route('health-consultations.edit', $consultation->id) }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    ✏️ Edit
                </a>

            @endif

            <a href="{{ route('health-consultations.print', $consultation->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

            @if($canManage)

                <form method="POST" action="{{ route('health-consultations.destroy', $consultation->id) }}"
                      onsubmit="return genservisConfirm(event, 'Delete this consultation record? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                        🗑 Delete
                    </button>
                </form>

            @endif

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- PATIENT INFO -->
    <div class="border rounded-lg p-6 bg-gray-50 mb-8">

        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Patient Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>{!! $field('Age', $consultation->patient_age) !!}</div>
            <div>{!! $field('Sex', $consultation->patient_sex) !!}</div>
            <div>{!! $field('Civil Status', $consultation->patient_civil_status) !!}</div>
            <div>{!! $field('Birthday', $consultation->patient_birthday?->format('M d, Y')) !!}</div>
            <div class="md:col-span-2">{!! $field('Address', $consultation->patient_address) !!}</div>
            <div>{!! $field('Time In', $consultation->time_in) !!}</div>
            <div>{!! $field('Time Out', $consultation->time_out) !!}</div>
        </div>

        <div class="mt-4">{!! $field('Chief Complaint', $consultation->chief_complaint) !!}</div>

    </div>

    <!-- EMERGENCY CONTACT / PREVIOUS CONSULTATION -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="border rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Contact Person (Emergency)</h3>
            <div class="space-y-3">
                {!! $field('Name', $consultation->emergency_contact_name) !!}
                {!! $field('Relationship', $consultation->emergency_contact_relationship) !!}
                {!! $field('Contact No.', $consultation->emergency_contact_no) !!}
            </div>
        </div>

        <div class="border rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Previous Consultation</h3>
            <div class="space-y-3">
                {!! $field('Date', $consultation->previous_consultation_date?->format('M d, Y')) !!}
                {!! $field('Diagnosis', $consultation->previous_diagnosis) !!}
                {!! $field('Medications', $consultation->previous_medications) !!}
                {!! $field('Attending Physician', $consultation->previous_attending_physician) !!}
            </div>
        </div>

    </div>

    <!-- ASSESSMENT -->
    <div class="border rounded-lg p-6 bg-gray-50 mb-8">

        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Assessment Section</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="space-y-3">
                {!! $field('Mode of Arrival', $consultation->mode_of_arrival) !!}
                {!! $field('Patient with injuries?', $consultation->has_injuries ? 'Yes' : 'None') !!}

                @if($consultation->has_injuries)
                    {!! $field('Injury Type(s)', $checklistLabel('injury_types', \App\Models\HealthConsultation::INJURY_TYPES) . ($consultation->injury_other_text ? ', ' . $consultation->injury_other_text : '')) !!}
                    <div class="grid grid-cols-2 gap-3">
                        {!! $field('NOI', $consultation->noi) !!}
                        {!! $field('POI', $consultation->poi) !!}
                        {!! $field('DOI', $consultation->doi?->format('M d, Y')) !!}
                        {!! $field('TOI', $consultation->toi) !!}
                    </div>
                @endif
            </div>

            <div class="space-y-3">
                <p class="text-base text-gray-500">Vital Signs</p>
                <div class="grid grid-cols-4 gap-3">
                    <div><p class="text-sm text-gray-500">BP</p><p class="font-semibold text-lg">{{ $consultation->vital_bp ?: '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Temp</p><p class="font-semibold text-lg">{{ $consultation->vital_temp ?: '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">PR</p><p class="font-semibold text-lg">{{ $consultation->vital_pr ?: '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">RR</p><p class="font-semibold text-lg">{{ $consultation->vital_rr ?: '-' }}</p></div>
                </div>

                <p class="text-base text-gray-500 mt-4">Glasgow Coma Scale</p>
                <div class="grid grid-cols-3 gap-3">
                    <div><p class="text-sm text-gray-500">Eye</p><p class="font-semibold text-lg">{{ $consultation->gcs_eye ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Verbal</p><p class="font-semibold text-lg">{{ $consultation->gcs_verbal ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Motor</p><p class="font-semibold text-lg">{{ $consultation->gcs_motor ?? '-' }}</p></div>
                </div>
                <p class="mt-2"><span class="text-base text-gray-500">Total Score:</span> <span class="font-semibold text-xl">{{ $consultation->gcsTotal() ?? '-' }}{{ $consultation->gcsTotal() ? ' / 15' : '' }}</span></p>
            </div>

        </div>

    </div>

    <!-- ALLERGIES / HISTORY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="border rounded-lg p-6">
            {!! $field('Allergies', $checklistLabel('allergies', \App\Models\HealthConsultation::ALLERGIES) . ($consultation->allergy_other_text ? ' (' . $consultation->allergy_other_text . ')' : '')) !!}
        </div>
        <div class="border rounded-lg p-6">
            {!! $field('Family History', $checklistLabel('family_history', \App\Models\HealthConsultation::FAMILY_HISTORY) . ($consultation->family_history_other_text ? ' (' . $consultation->family_history_other_text . ')' : '')) !!}
        </div>
        <div class="border rounded-lg p-6">
            {!! $field('Medical History', $checklistLabel('medical_history', \App\Models\HealthConsultation::MEDICAL_HISTORY) . ($consultation->medical_history_other_text ? ' (' . $consultation->medical_history_other_text . ')' : '')) !!}
        </div>
    </div>

    <!-- DIAGNOSIS / SOAP -->
    <div class="border rounded-lg p-6 mb-4">

        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Diagnosis & Management</h3>

        <div class="mb-4">{!! $field('Diagnosis', $consultation->diagnosis) !!}</div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            {!! $field("Doctor's Order", $consultation->doctors_order) !!}
            {!! $field('Interventions', $consultation->interventions) !!}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            {!! $field('S> Subjective', $consultation->soap_subjective) !!}
            {!! $field('O> Objective', $consultation->soap_objective) !!}
            {!! $field('A> Assessment', $consultation->soap_assessment) !!}
            {!! $field('P> Plan', $consultation->soap_plan) !!}
        </div>

        {!! $field('Attending Physician', $consultation->attending_physician) !!}

    </div>

    <!-- MEDICINES DISPENSED -->
    <div class="border rounded-lg p-6 mb-4">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-semibold text-gray-800">💊 Medicines Dispensed</h3>
            @if($canManage)
                <button type="button" onclick="openDispenseModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    ➕ Dispense Medicine
                </button>
            @endif
        </div>

        @if($canManage && ($outOfStockCount > 0 || $expiredCount > 0 || $expiringSoonCount > 0))
            <div class="flex flex-wrap gap-2 mb-4">
                @if($outOfStockCount > 0)
                    <span class="bg-red-100 text-red-700 text-sm font-semibold px-3 py-1.5 rounded-full">
                        ⚠ {{ $outOfStockCount }} medicine{{ $outOfStockCount === 1 ? '' : 's' }} out of stock
                    </span>
                @endif
                @if($expiredCount > 0)
                    <span class="bg-red-100 text-red-700 text-sm font-semibold px-3 py-1.5 rounded-full">
                        ⚠ {{ $expiredCount }} medicine{{ $expiredCount === 1 ? '' : 's' }} expired
                    </span>
                @endif
                @if($expiringSoonCount > 0)
                    <span class="bg-yellow-100 text-yellow-700 text-sm font-semibold px-3 py-1.5 rounded-full">
                        ⏳ {{ $expiringSoonCount }} medicine{{ $expiringSoonCount === 1 ? '' : 's' }} expiring soon
                    </span>
                @endif
                <a href="{{ route('clinic-medicines.index') }}" class="text-sm text-blue-600 hover:underline self-center">
                    View Clinic Medicine Inventory →
                </a>
            </div>
        @endif

        @if($consultation->medicines->isEmpty())

            <p class="text-gray-500 text-base">No medicines dispensed for this visit yet.</p>

        @else

            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-base">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Medicine</th>
                            <th class="p-3 text-center">Quantity</th>
                            <th class="p-3 text-left">Notes</th>
                            <th class="p-3 text-left">Dispensed By</th>
                            <th class="p-3 text-center">Date</th>
                            @if($canManage)
                                <th class="p-3 text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($consultation->medicines as $log)
                            <tr>
                                <td class="p-3">{{ $log->medicine_name }}</td>
                                <td class="p-3 text-center">{{ $log->quantity }} {{ $log->unit }}</td>
                                <td class="p-3">{{ $log->notes ?: '-' }}</td>
                                <td class="p-3">{{ $log->dispenser->name ?? '-' }}</td>
                                <td class="p-3 text-center">{{ $log->created_at->format('M d, Y') }}</td>
                                @if($canManage)
                                    <td class="p-3 text-center">
                                        <form method="POST" action="{{ route('health-consultations.medicines.destroy', [$consultation->id, $log->id]) }}"
                                              onsubmit="return genservisConfirm(event, 'Remove this dispensing entry? The stock quantity will be restored.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline font-semibold">Remove</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif

    </div>

    <p class="text-sm text-gray-400 mt-6">
        Recorded by {{ $consultation->creator->name ?? 'Unknown' }} on {{ $consultation->created_at->format('M d, Y g:i A') }}
    </p>

</div>

@if($canManage)

<!-- DISPENSE MEDICINE MODAL -->
<div id="dispenseModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-5xl max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center border-b px-8 py-5">
            <h2 class="text-2xl font-bold flex items-center gap-3">
                💊 Dispense Medicine
                <span id="dispense-item-count-badge" class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">1 item</span>
            </h2>
            <button type="button" onclick="closeDispenseModal()" class="text-gray-500 hover:text-red-600 text-2xl">✕</button>
        </div>

        <form method="POST" action="{{ route('health-consultations.medicines.store', $consultation->id) }}" class="flex-1 overflow-y-auto">
            @csrf

            <div class="p-8">

                <div class="overflow-x-auto border rounded-lg mb-4">
                    <table class="w-full text-base">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left text-lg" style="min-width:280px;">Medicine</th>
                                <th class="p-3 text-left text-lg" style="min-width:120px;">Quantity</th>
                                <th class="p-3 text-left text-lg" style="min-width:200px;">Notes</th>
                                <th class="p-3 text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="dispense-items-container" class="divide-y-2 divide-gray-200">

                            <tr class="dispense-item-row">

                                <td class="p-3 align-top">
                                    <select name="clinic_medicine_id[]" class="medicine-select w-full" required>
                                        <option value="">🔍 Click to search medicine...</option>
                                    </select>
                                    <p class="medicine-meta text-sm text-gray-500 mt-2"></p>
                                </td>

                                <td class="p-3 align-top">
                                    <input type="number" name="quantity[]" min="1" value="1" class="dispense-quantity-input w-full border rounded-lg p-4 text-lg" required>
                                    <p class="dispense-stock-warning text-red-500 text-sm mt-1 hidden">Exceeds available stock.</p>
                                </td>

                                <td class="p-3 align-top">
                                    <input type="text" name="notes[]" class="w-full border rounded-lg p-4 text-lg">
                                </td>

                                <td class="p-3 text-center align-top">
                                    <button type="button" class="remove-dispense-item hidden bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg text-sm font-semibold">Remove</button>
                                </td>

                            </tr>

                        </tbody>
                    </table>
                </div>

                <button type="button" id="add-dispense-item" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg text-lg font-semibold">
                    ➕ Add Another Medicine
                </button>

            </div>

            <div class="flex justify-end gap-3 border-t px-8 py-5">
                <button type="button" onclick="closeDispenseModal()" class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-lg text-lg">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg font-semibold">💾 Dispense</button>
            </div>

        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

<style>

    .ts-control {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        padding: 1rem !important;
        min-height: 58px !important;
        box-shadow: none !important;
        font-size: 18px !important;
    }

    .ts-control input {
        font-size: 18px !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .ts-wrapper.single .ts-control {
        background: white !important;
    }

    .ts-control:focus-within {
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 2px rgba(96,165,250,0.3) !important;
    }

    .ts-dropdown {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        overflow: hidden;
        font-size: 16px !important;
        /* Tom Select's dropdown defaults to z-index:10, but this picker
           lives inside a z-50 modal — since dropdownParent:'body' makes
           both siblings of <body>, the modal's higher z-index was
           painting on top of the dropdown, hiding it completely. */
        z-index: 9999 !important;
    }

    .ts-dropdown .option {
        padding: 0.75rem 1rem !important;
    }

</style>

<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>

    // ✅ MASTER MEDICINE DATA — status computed server-side once per page
    // load (out_of_stock / expired / expiring_soon / ok), same convention
    // as Material Request's materialsForJs.
    const allMedicines = @json($medicinesForJs);

    const medicinesById = {};

    allMedicines.forEach(function (m) {
        medicinesById[m.id] = m;
    });

    function statusLabel(status) {
        return {
            out_of_stock: 'Out of Stock',
            expired: 'Expired',
            expiring_soon: 'Expiring Soon',
            ok: 'OK',
        }[status] || 'OK';
    }

    function statusColorClass(status) {
        return {
            out_of_stock: 'text-red-600',
            expired: 'text-red-600',
            expiring_soon: 'text-yellow-600',
            ok: 'text-green-600',
        }[status] || 'text-green-600';
    }

    function populateMedicineSelect(tom) {

        const currentValue = tom.getValue();

        tom.clear(true);
        tom.clearOptions();

        tom.addOption({ value: '', text: '🔍 Click to search medicine...' });

        allMedicines.forEach(function (m) {

            let text = m.name + (m.brand ? ` (${m.brand})` : '') + ' — Stock: ' + m.stock + ' ' + m.unit;

            if (m.status === 'out_of_stock') {
                text += ' (Out of Stock)';
            } else if (m.status === 'expired') {
                text += ' (Expired)';
            } else if (m.status === 'expiring_soon') {
                text += ' (Expiring Soon)';
            }

            tom.addOption({ value: String(m.id), text: text, disabled: m.blocked });
        });

        tom.refreshOptions(false);

        if (currentValue && medicinesById[currentValue]) {
            tom.setValue(currentValue, true);
        }
    }

    function initMedicineTomSelect(element) {

        const tom = new TomSelect(element, {
            create: false,
            dropdownParent: 'body',
            disabledField: 'disabled',
            sortField: { field: 'text', direction: 'asc' },
        });

        populateMedicineSelect(tom);

        element.addEventListener('change', function () {

            const medicine = medicinesById[element.value];

            const row = element.closest('.dispense-item-row');
            const quantityInput = row.querySelector('.dispense-quantity-input');
            const meta = row.querySelector('.medicine-meta');

            if (!medicine) {
                meta.innerHTML = '';
                quantityInput.max = 0;
                return;
            }

            meta.innerHTML =
                `Unit: <strong>${medicine.unit}</strong> &nbsp;•&nbsp; Stock: <strong>${medicine.stock} ${medicine.unit}</strong>` +
                (medicine.expiration_date ? ` &nbsp;•&nbsp; Exp: <strong>${medicine.expiration_date}</strong>` : '') +
                ` &nbsp;•&nbsp; <span class="${statusColorClass(medicine.status)} font-semibold">${statusLabel(medicine.status)}</span>`;

            quantityInput.max = medicine.stock;
        });
    }

    // ✅ ADD ITEM
    document.getElementById('add-dispense-item').addEventListener('click', function () {

        const tbody = document.getElementById('dispense-items-container');
        const newRow = document.createElement('tr');
        newRow.className = 'dispense-item-row';

        newRow.innerHTML = `
            <td class="p-3 align-top">
                <select name="clinic_medicine_id[]" class="medicine-select w-full" required>
                    <option value="">🔍 Click to search medicine...</option>
                </select>
                <p class="medicine-meta text-sm text-gray-500 mt-2"></p>
            </td>
            <td class="p-3 align-top">
                <input type="number" name="quantity[]" min="1" value="1" class="dispense-quantity-input w-full border rounded-lg p-4 text-lg" required>
                <p class="dispense-stock-warning text-red-500 text-sm mt-1 hidden">Exceeds available stock.</p>
            </td>
            <td class="p-3 align-top">
                <input type="text" name="notes[]" class="w-full border rounded-lg p-4 text-lg">
            </td>
            <td class="p-3 text-center align-top">
                <button type="button" class="remove-dispense-item bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg text-sm font-semibold">Remove</button>
            </td>
        `;

        tbody.appendChild(newRow);

        initMedicineTomSelect(newRow.querySelector('.medicine-select'));

        updateDispenseItemCount();
    });

    // ✅ REMOVE ITEM
    document.addEventListener('click', function (e) {

        if (e.target.closest('.remove-dispense-item')) {

            const rows = document.querySelectorAll('.dispense-item-row');

            if (rows.length > 1) {
                e.target.closest('.dispense-item-row').remove();
                updateDispenseItemCount();
            }
        }
    });

    // ✅ DUPLICATE MEDICINE GUARD
    document.addEventListener('change', function (e) {

        if (!e.target.classList.contains('medicine-select')) return;

        const currentSelect = e.target;
        const currentValue = currentSelect.value;

        if (currentValue === '') return;

        let duplicateFound = false;

        document.querySelectorAll('.medicine-select').forEach(select => {
            if (select !== currentSelect && select.value === currentValue) {
                duplicateFound = true;
            }
        });

        if (duplicateFound) {

            alert('This medicine is already in the list.');

            if (currentSelect.tomselect) {
                currentSelect.tomselect.clear();
            } else {
                currentSelect.value = '';
            }
        }
    });

    // ✅ LIVE QUANTITY VALIDATION
    document.addEventListener('input', function (e) {

        if (!e.target.classList.contains('dispense-quantity-input')) return;

        const input = e.target;
        const row = input.closest('.dispense-item-row');
        const warning = row.querySelector('.dispense-stock-warning');
        const max = parseInt(input.max || 0);
        const value = parseInt(input.value || 0);

        if (value > max) {
            warning.classList.remove('hidden');
            input.classList.add('border-red-500', 'ring-2', 'ring-red-300');
        } else {
            warning.classList.add('hidden');
            input.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
        }
    });

    // ✅ ITEM COUNT BADGE
    function updateDispenseItemCount() {

        const badge = document.getElementById('dispense-item-count-badge');
        const rowCount = document.querySelectorAll('.dispense-item-row').length;

        if (badge) {
            badge.innerText = rowCount + (rowCount === 1 ? ' item' : ' items');
        }
    }

    document.addEventListener('change', updateDispenseItemCount);

    let dispenseModalInitialized = false;

    function openDispenseModal() {

        document.getElementById('dispenseModal').classList.remove('hidden');

        // Tom Select must be created only once the modal is actually
        // visible — initializing it while the modal is still display:none
        // makes it measure a zero-size control, which silently breaks its
        // own click-to-open behavior (rows added later via "Add Another
        // Medicine" are fine since the modal is already open by then).
        if (!dispenseModalInitialized) {
            document.querySelectorAll('.medicine-select').forEach(select => initMedicineTomSelect(select));
            dispenseModalInitialized = true;
        }
    }

    function closeDispenseModal() {
        document.getElementById('dispenseModal').classList.add('hidden');
    }

</script>

@endif

@endsection
