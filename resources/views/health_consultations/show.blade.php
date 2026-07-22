@extends('layouts.app')

@section('content')

@php
    $field = fn ($label, $value) => '<p class="text-sm text-gray-500">' . $label . '</p><p class="font-semibold text-lg mt-1">' . ($value !== null && $value !== '' ? e($value) : '-') . '</p>';
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

            <x-back-button :href="route('health-consultations.index')" />

            <a href="{{ route('health-consultations.edit', $consultation->id) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                ✏️ Edit
            </a>

            <a href="{{ route('health-consultations.print', $consultation->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

            <form method="POST" action="{{ route('health-consultations.destroy', $consultation->id) }}"
                  onsubmit="return confirm('Delete this consultation record? This cannot be undone.')">
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

    <!-- PATIENT INFO -->
    <div class="border rounded-lg p-6 bg-gray-50 mb-8">

        <h3 class="text-xl font-semibold text-gray-800 mb-4">Patient Information</h3>

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
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Person (Emergency)</h3>
            <div class="space-y-3">
                {!! $field('Name', $consultation->emergency_contact_name) !!}
                {!! $field('Relationship', $consultation->emergency_contact_relationship) !!}
                {!! $field('Contact No.', $consultation->emergency_contact_no) !!}
            </div>
        </div>

        <div class="border rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Previous Consultation</h3>
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

        <h3 class="text-xl font-semibold text-gray-800 mb-4">Assessment Section</h3>

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
                <p class="text-sm text-gray-500">Vital Signs</p>
                <div class="grid grid-cols-4 gap-3">
                    <div><p class="text-xs text-gray-500">BP</p><p class="font-semibold">{{ $consultation->vital_bp ?: '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Temp</p><p class="font-semibold">{{ $consultation->vital_temp ?: '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">PR</p><p class="font-semibold">{{ $consultation->vital_pr ?: '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">RR</p><p class="font-semibold">{{ $consultation->vital_rr ?: '-' }}</p></div>
                </div>

                <p class="text-sm text-gray-500 mt-4">Glasgow Coma Scale</p>
                <div class="grid grid-cols-3 gap-3">
                    <div><p class="text-xs text-gray-500">Eye</p><p class="font-semibold">{{ $consultation->gcs_eye ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Verbal</p><p class="font-semibold">{{ $consultation->gcs_verbal ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Motor</p><p class="font-semibold">{{ $consultation->gcs_motor ?? '-' }}</p></div>
                </div>
                <p class="mt-2"><span class="text-sm text-gray-500">Total Score:</span> <span class="font-semibold text-lg">{{ $consultation->gcsTotal() ?? '-' }}{{ $consultation->gcsTotal() ? ' / 15' : '' }}</span></p>
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

        <h3 class="text-xl font-semibold text-gray-800 mb-4">Diagnosis & Management</h3>

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
            <h3 class="text-xl font-semibold text-gray-800">💊 Medicines Dispensed</h3>
            <button type="button" onclick="openDispenseModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                ➕ Dispense Medicine
            </button>
        </div>

        @if($consultation->medicines->isEmpty())

            <p class="text-gray-500 text-sm">No medicines dispensed for this visit yet.</p>

        @else

            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Medicine</th>
                            <th class="p-2 text-center">Quantity</th>
                            <th class="p-2 text-left">Notes</th>
                            <th class="p-2 text-left">Dispensed By</th>
                            <th class="p-2 text-center">Date</th>
                            <th class="p-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($consultation->medicines as $log)
                            <tr>
                                <td class="p-2">{{ $log->medicine_name }}</td>
                                <td class="p-2 text-center">{{ $log->quantity }} {{ $log->unit }}</td>
                                <td class="p-2">{{ $log->notes ?: '-' }}</td>
                                <td class="p-2">{{ $log->dispenser->name ?? '-' }}</td>
                                <td class="p-2 text-center">{{ $log->created_at->format('M d, Y') }}</td>
                                <td class="p-2 text-center">
                                    <form method="POST" action="{{ route('health-consultations.medicines.destroy', [$consultation->id, $log->id]) }}"
                                          onsubmit="return confirm('Remove this dispensing entry? The stock quantity will be restored.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Remove</button>
                                    </form>
                                </td>
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

<!-- DISPENSE MEDICINE MODAL -->
<div id="dispenseModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 class="text-xl font-bold">Dispense Medicine</h2>
            <button type="button" onclick="closeDispenseModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>

        <form method="POST" action="{{ route('health-consultations.medicines.store', $consultation->id) }}">
            @csrf

            <div class="p-6 space-y-4">

                <div>
                    <label class="block mb-1 font-semibold text-sm">Medicine</label>
                    <select id="dispenseMedicineSelect" name="clinic_medicine_id" class="w-full border rounded-lg p-3" required>
                        <option value="">-- Select medicine --</option>
                        @foreach($availableMedicines as $med)
                            <option value="{{ $med->id }}" data-stock="{{ $med->current_stock }}" data-unit="{{ $med->unit }}">
                                {{ $med->name }}{{ $med->brand ? " ({$med->brand})" : '' }} — {{ $med->current_stock }} {{ $med->unit }} in stock
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Quantity</label>
                    <input type="number" name="quantity" min="1" value="1" class="w-full border rounded-lg p-3" required>
                    <p id="dispenseStockHint" class="text-xs text-gray-500 mt-1"></p>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">Notes (optional)</label>
                    <input type="text" name="notes" class="w-full border rounded-lg p-3">
                </div>

            </div>

            <div class="flex justify-end gap-2 border-t px-6 py-4">
                <button type="button" onclick="closeDispenseModal()" class="bg-gray-300 hover:bg-gray-400 px-5 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">💾 Dispense</button>
            </div>

        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>

    function openDispenseModal() {
        document.getElementById('dispenseModal').classList.remove('hidden');
    }

    function closeDispenseModal() {
        document.getElementById('dispenseModal').classList.add('hidden');
    }

    const dispenseSelect = new TomSelect('#dispenseMedicineSelect', {
        create: false,
        dropdownParent: 'body',
        sortField: { field: 'text', direction: 'asc' },
    });

    dispenseSelect.on('change', function (value) {
        const option = document.querySelector(`#dispenseMedicineSelect option[value="${value}"]`);
        const hint = document.getElementById('dispenseStockHint');

        if (option && value) {
            hint.textContent = `${option.dataset.stock} ${option.dataset.unit || ''} currently in stock`;
        } else {
            hint.textContent = '';
        }
    });

</script>

@endsection
