@extends('layouts.app')

@section('content')

<div class="w-full min-w-0">

@if(session('success'))

<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">

{{ session('success') }}

</div>

@endif

@if(session('error'))

<div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">

{{ session('error') }}

</div>

@endif

<!-- Header -->

<div class="bg-white rounded-xl shadow">

<div class="border-b px-6 py-4 flex justify-between items-center">

<div>

<h2 class="text-2xl font-bold">

📄 Procurement Plan

</h2>

<p class="text-gray-500">

Working Document

</p>

</div>

@include('supervisor.procurement.plans.partials._status_badge', ['status' => $plan->status, 'size' => 'lg'])

</div>

@if($plan->status === 'Draft' || $plan->status === 'Submitted')

<div class="border-b px-6 py-4 flex flex-wrap gap-3">

@if($plan->status === 'Draft')

@if(auth()->user()->hasPermission('submit-ppmp'))

@php
    $approvedItemCount = $plan->items->where('is_approved', true)->count();
    $totalItemCount = $plan->items->count();
    $allItemsApproved = $totalItemCount > 0 && $approvedItemCount === $totalItemCount;
@endphp

<form id="submitPlanForm" action="{{ route('procurement.plans.submit', $plan->id) }}" method="POST" class="inline">
@csrf
</form>

<div>

    <button
    type="button"
    @if($allItemsApproved) onclick="confirmSubmitPlan()" @endif
    @disabled(! $allItemsApproved)
    class="px-5 py-2 rounded
        {{ $allItemsApproved
            ? 'bg-green-600 hover:bg-green-700 text-white'
            : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}">

    ✅ Submit for Approval

    </button>

    @unless($allItemsApproved)

    <p class="text-xs text-gray-500 mt-1">

        {{ $approvedItemCount }} of {{ $totalItemCount }} item(s) approved - all items must be approved first.

    </p>

    @endunless

</div>

@endif

@if(auth()->user()->hasPermission('edit-ppmp'))

<a
href="{{ route('procurement.plans.edit', $plan->id) }}"
class="bg-white border px-5 py-2 rounded hover:bg-gray-50">

✏️ Edit

</a>

@endif

@if(auth()->user()->hasPermission('delete-ppmp'))

<form id="deletePlanForm" action="{{ route('procurement.plans.destroy', $plan->id) }}" method="POST" class="inline">
@csrf
@method('DELETE')
</form>

<button
type="button"
onclick="confirmDeletePlan()"
class="bg-white border border-red-300 text-red-600 px-5 py-2 rounded hover:bg-red-50">

🗑 Delete

</button>

@endif

@elseif($plan->status === 'Submitted')

@if(auth()->user()->hasPermission('approve-ppmp'))

<form id="approvePlanForm" action="{{ route('procurement.plans.approve', $plan->id) }}" method="POST" class="inline">
@csrf
</form>

<button
type="button"
onclick="confirmApprovePlan()"
class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">

✔️ Approve

</button>

@endif

@if(auth()->user()->hasPermission('reject-ppmp'))

<form id="rejectPlanForm" action="{{ route('procurement.plans.reject', $plan->id) }}" method="POST" class="inline">
@csrf
<input type="hidden" name="reason" id="rejectPlanReason">
</form>

<button
type="button"
onclick="confirmRejectPlan()"
class="bg-white border border-red-300 text-red-600 px-5 py-2 rounded hover:bg-red-50">

✖️ Reject

</button>

@endif

@endif

</div>

@endif

<div class="p-6">

<div class="grid md:grid-cols-2 gap-6">

<div>

<label class="text-gray-500">

PPMP Number

</label>

<div class="font-bold text-xl">

{{ $plan->plan_number }}

</div>

</div>

<div>

<label class="text-gray-500">

Planning Year

</label>

<div class="font-bold">

{{ $plan->year }}

</div>

</div>

<div>

<label class="text-gray-500">

Department

</label>

<div class="font-bold">

{{ $plan->department->department_name }}

</div>

</div>

<div>

<label class="text-gray-500">

Allocated Budget

</label>

<div class="font-bold text-green-700">

₱ {{ number_format($plan->allocated_budget,2) }}

</div>

</div>

</div>

</div>

</div>

<!-- Header Card -->

</div>

    <!-- Budget Summary -->
    @include('supervisor.procurement.plans.partials._budget_cards')

    <!-- Budget Utilization -->

    @include('supervisor.procurement.plans.partials._budget_progress')

    <!-- Procurement Toolbar -->

    <div class="bg-white rounded-xl shadow mt-6">

        <div class="p-5 flex flex-wrap gap-3">

            <button
                type="button"
                onclick="openAddMaterialModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">

                ➕ Add Material

            </button>

            <button
                disabled
                class="bg-gray-200 text-gray-500 px-5 py-2 rounded cursor-not-allowed">

                📥 Import Excel

            </button>

            <button
                disabled
                class="bg-gray-200 text-gray-500 px-5 py-2 rounded cursor-not-allowed">

                📤 Export Excel

            </button>

            <button
                disabled
                class="bg-gray-200 text-gray-500 px-5 py-2 rounded cursor-not-allowed">

                🖨 Print PPMP

            </button>

        </div>

    </div>

<!-- Procurement Items -->
@include('supervisor.procurement.plans.partials._procurement_table')

<!-- Item History -->
@include('supervisor.procurement.plans.partials._item_history')



<!-- Procurement Modal -->
@include('supervisor.procurement.plans.partials._procurement_modal')



<script>

    window.procurementRoutes = {

        storeItem:
            "{{ route('procurement.plans.items.store', $plan->id) }}",

        showItem:
            "{{ route('procurement.plans.items.show', ':id') }}",

        updateItem:
             "{{ route('procurement.plans.items.update', ':id') }}",

        materialDetails:
            "{{ route('procurement.materials.details', ':id') }}",

        quickCreateMaterial:
            "{{ route('procurement.materials.quick-create', $plan->id) }}"

    };

</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        initializeProcurementWorkspace();
    });

        [
        'estimated_unit_cost',
        'q1',
        'q2',
        'q3',
        'q4'
    ].forEach(function(id) {

        document
            .getElementById(id)
            .addEventListener('input', updateProcurementSummary);

    });

    function initializeProcurementWorkspace()
        {
            registerMaterialSelection();

            new TomSelect('#material_id', {
                create: false,
                sortField: { field: 'text', direction: 'asc' },
            });

            new TomSelect('#newMaterialClassification', {
                create: false,
                sortField: { field: 'text', direction: 'asc' },
            });

            new TomSelect('#assignClassificationSelect', {
                create: false,
                sortField: { field: 'text', direction: 'asc' },
            });
        }

    function registerMaterialSelection()
        {
        const materialSelect = document.getElementById('materialSelect');

            if (!materialSelect) {
                return;
            }

         materialSelect.addEventListener('change', function () {

            const option = this.options[this.selectedIndex];

            document.getElementById('categoryDisplay').value =
                option.dataset.category || '';

             document.getElementById('unitDisplay').value =
                 option.dataset.unit || '';

            document.getElementById('stockDisplay').value =
                option.dataset.stock || '';
            });
        }

</script>

<script>

    async function loadMaterialDetails(materialId)
        
        {
        if (!materialId) {
            document
                .getElementById('materialInfoCard')
                .classList.add('hidden');

            document
                .getElementById('assignClassificationCard')
                .classList.add('hidden');

                return;
            }

            const url =
            window.procurementRoutes.materialDetails
                .replace(':id', materialId);

            try {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Unable to load material details.');
                }

            const material = await response.json();

            document.getElementById('materialCategory').innerText =
                material.category ?? '-';

            document.getElementById('materialUnit').innerText =
                material.unit ?? '-';

            document.getElementById('materialStock').innerText =
                material.quantity;

            document.getElementById('materialThreshold').innerText =
                material.threshold;

            document
                .getElementById('materialInfoCard')
                .classList.remove('hidden');

            const classificationCard = document.getElementById('assignClassificationCard');

            if (material.classification_id) {

                classificationCard.classList.add('hidden');

                document.getElementById('assignClassificationSelect').tomselect?.clear();

            } else {

                classificationCard.classList.remove('hidden');

            }

            } catch (error) {

                console.error(error);

            }
        }

    function toggleNewMaterialForm()
        {
            document
                .getElementById('newMaterialForm')
                .classList.toggle('hidden');

            document
                .getElementById('newMaterialError')
                .classList.add('hidden');
        }

    async function createNewMaterial()
        {
            const errorEl = document.getElementById('newMaterialError');

            errorEl.classList.add('hidden');

            const payload = {
                name: document.getElementById('newMaterialName').value,
                category_id: document.getElementById('newMaterialCategory').value,
                unit_id: document.getElementById('newMaterialUnit').value,
                classification_id: document.getElementById('newMaterialClassification').value,
            };

            try {

                const response = await fetch(window.procurementRoutes.quickCreateMaterial, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                if (!response.ok) {

                    const errorData = await response.json().catch(() => null);

                    const message = errorData?.message
                        || Object.values(errorData?.errors ?? {}).flat().join(' ')
                        || 'Unable to create material.';

                    errorEl.innerText = message;

                    errorEl.classList.remove('hidden');

                    return;

                }

                const material = await response.json();

                const materialSelect = document.getElementById('material_id');
                const materialTom = materialSelect.tomselect;

                if (materialTom) {
                    materialTom.addOption({ value: material.id, text: material.name });
                    materialTom.refreshOptions(false);
                    materialTom.setValue(material.id);
                } else {
                    const option = document.createElement('option');
                    option.value = material.id;
                    option.innerText = material.name;
                    materialSelect.appendChild(option);
                    materialSelect.value = material.id;
                }

                toggleNewMaterialForm();

                await loadMaterialDetails(material.id);

            } catch (error) {

                errorEl.innerText = 'Unable to create material.';

                errorEl.classList.remove('hidden');

                console.error(error);

            }
        }

    function updateProcurementSummary()
        {
            const unitCost =
                parseFloat(document.getElementById('estimated_unit_cost').value) || 0;

            const q1 =
                parseInt(document.getElementById('q1').value) || 0;

            const q2 =
                parseInt(document.getElementById('q2').value) || 0;

            const q3 =
                parseInt(document.getElementById('q3').value) || 0;

            const q4 =
                parseInt(document.getElementById('q4').value) || 0;

            const totalQuantity =
                q1 + q2 + q3 + q4;

            const estimatedTotal =
                totalQuantity * unitCost;

            const remainingBudget =
            {{ $plan->remaining_budget_computed }};

            document.getElementById('totalQuantityDisplay').innerText =
                totalQuantity;

            document.getElementById('estimatedTotalDisplay').innerText =
                '₱' + estimatedTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                const remainingBalance =
                remainingBudget - estimatedTotal;

            document.getElementById('remainingBudgetDisplay').innerText =
                '₱' + remainingBalance.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        }

    function openProcurementModal()
        {
            document
                .getElementById('addMaterialModal')
                .classList.remove('hidden');
        }

    function closeProcurementModal()
        {
            document
                .getElementById('addMaterialModal')
                .classList.add('hidden');
        }

    function resetProcurementModal()
        {
            const form = document.getElementById('procurementItemForm');

            form.reset();

            document.getElementById('procurementItemId').value = '';

            document.getElementById('formMethod').value = 'POST';

            document.getElementById('material_id').tomselect?.clear();

            document
                .getElementById('materialInfoCard')
                ?.classList.add('hidden');

            document
                .getElementById('assignClassificationCard')
                ?.classList.add('hidden');

            document.getElementById('assignClassificationSelect').tomselect?.clear();

            updateProcurementSummary();
        }

        
    function switchToCreateMode()
        {
            document.getElementById('procurementModalTitle').innerText =
                'Add Procurement Item';

            document.getElementById('procurementSubmitButton').innerText =
                'Save Procurement Item';

            const form =
                document.getElementById('procurementItemForm');

            form.action =
                window.procurementRoutes.storeItem;

            document.getElementById('formMethod').value = 'POST';

            document.getElementById('editReasonField').classList.add('hidden');
        }

    function openAddMaterialModal()
        {
            resetProcurementModal();

            switchToCreateMode();

            document.getElementById('estimated_unit_cost').value = '';

            document.getElementById('q1').value = 0;
            document.getElementById('q2').value = 0;
            document.getElementById('q3').value = 0;
            document.getElementById('q4').value = 0;

            openProcurementModal();
        }
        
    function confirmSubmitPlan()
        {
            Swal.fire({

                title: 'Submit for Approval?',

                text: 'This will lock the plan for editing until it is approved or rejected.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#16a34a',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Yes, submit it!',

                cancelButtonText: 'Cancel'

            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('submitPlanForm').submit();

                }

            });
        }

    function confirmApprovePlan()
        {
            Swal.fire({

                title: 'Approve Procurement Plan?',

                text: 'This action cannot be undone.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#16a34a',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Yes, approve it!',

                cancelButtonText: 'Cancel'

            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('approvePlanForm').submit();

                }

            });
        }

    function confirmRejectPlan()
        {
            Swal.fire({

                title: 'Reject Procurement Plan?',

                input: 'textarea',

                inputLabel: 'Reason (optional)',

                inputPlaceholder: 'Explain why this plan is being rejected...',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Yes, reject it!',

                cancelButtonText: 'Cancel'

            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('rejectPlanReason').value = result.value || '';

                    document.getElementById('rejectPlanForm').submit();

                }

            });
        }

    function confirmDeletePlan()
        {
            Swal.fire({

                title: 'Delete Procurement Plan?',

                html:
                    '<strong>' + {{ Js::from($plan->plan_number) }} + '</strong><br><br>' +
                    'This action cannot be undone.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Delete',

                cancelButtonText: 'Cancel'

            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('deletePlanForm').submit();

                }

            });
        }

    function confirmDelete(itemId, materialName)
        {
            Swal.fire({

                title: 'Delete Procurement Item?',

                html: '<strong>' + materialName + '</strong>',

                input: 'textarea',

                inputLabel: 'Reason for deleting this item (required)',

                inputPlaceholder: 'Let the person who added this item know why it was removed...',

                inputValidator: (value) => {

                    if (!value || !value.trim()) {
                        return 'A reason is required.';
                    }

                },

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Delete',

                cancelButtonText: 'Cancel'

            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('deleteReason' + itemId).value = result.value;

                    document
                        .getElementById('deleteForm' + itemId)
                        .submit();

                }

            });
        }

    function switchToEditMode(item)
        {
            document.getElementById('procurementModalTitle').innerText =
                'Edit Procurement Item';

            document.getElementById('procurementSubmitButton').innerText =
                'Update Procurement Item';

            const form =
                document.getElementById('procurementItemForm');

            form.action =
                window.procurementRoutes.updateItem
                    .replace(':id', item.id);

            document.getElementById('formMethod').value = 'PUT';

            document.getElementById('editReasonField').classList.remove('hidden');
        }

    function populateProcurementModal(item)
        {
            document.getElementById('procurementItemId').value =
                item.id;

            const materialTom = document.getElementById('material_id').tomselect;

            if (materialTom) {
                materialTom.setValue(item.material_id);
            } else {
                document.getElementById('material_id').value = item.material_id;
            }

            document.getElementById('estimated_unit_cost').value =
                item.estimated_unit_cost;

            document.getElementById('q1').value = item.q1;
            document.getElementById('q2').value = item.q2;
            document.getElementById('q3').value = item.q3;
            document.getElementById('q4').value = item.q4;

            document.getElementById('procurement_method').value =
                item.procurement_method || '';

            updateProcurementSummary();
        }
        

    async function editProcurementItem(itemId)
        {
            try {

                const url =
                    window.procurementRoutes.showItem
                        .replace(':id', itemId);

                const response = await fetch(url);

                if (!response.ok) {
                    console.error(response.status);

                    const text = await response.text();

                    console.error(text);

                    throw new Error('Unable to load procurement item.');
                }

                const item = await response.json();

                switchToEditMode(item);

                populateProcurementModal(item);

                openProcurementModal();

            } catch (error) {

                alert(error.message);

            }
        }

</script>

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

<style>

    .ts-control {
        border-radius: 0.375rem !important;
        border: 1px solid #d1d5db !important;
        padding: 0.5rem 0.75rem !important;
        box-shadow: none !important;
    }

    .ts-control input {
        font-size: 14px !important;
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
        border-radius: 0.375rem !important;
        border: 1px solid #d1d5db !important;
        overflow: hidden;
    }

</style>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection