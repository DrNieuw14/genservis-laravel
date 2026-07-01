<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Procurement Planning
    </h2>
</x-slot>

<div class="py-6">

<div class="max-w-7xl mx-auto px-6">

@if(session('success'))

<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">

{{ session('success') }}

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

<span class="px-4 py-2 rounded-full
@if($plan->status=='Draft')
bg-yellow-100 text-yellow-700
@elseif($plan->status=='Approved')
bg-green-100 text-green-700
@else
bg-blue-100 text-blue-700
@endif">

{{ $plan->status }}

</span>

</div>

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
            "{{ route('procurement.materials.details', ':id') }}"

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

            } catch (error) {

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

            document
                .getElementById('materialInfoCard')
                ?.classList.add('hidden');

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
        
    function confirmDelete(itemId, materialName)
        {
            Swal.fire({

                title: 'Delete Procurement Item?',

                html:
                    '<strong>' + materialName + '</strong><br><br>' +
                    'This action cannot be undone.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Delete',

                cancelButtonText: 'Cancel'

            }).then((result) => {

                if (result.isConfirmed) {

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
        }

    function populateProcurementModal(item)
        {
            document.getElementById('procurementItemId').value =
                item.id;

            document.getElementById('material_id').value =
                item.material_id;

            document.getElementById('estimated_unit_cost').value =
                item.estimated_unit_cost;

            document.getElementById('q1').value = item.q1;
            document.getElementById('q2').value = item.q2;
            document.getElementById('q3').value = item.q3;
            document.getElementById('q4').value = item.q4;

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-app-layout>