@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📦 Material Request
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Request materials from the Centralized Stockroom for your department.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-4 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-4 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-4 rounded-lg text-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="materialRequestForm" method="POST" action="/material-request">
        @csrf

        <!-- DEPARTMENT -->
        <div class="mb-6">

            <label class="block mb-2 font-semibold text-lg">
                Destination Department / Office
            </label>

            <p class="text-gray-500 text-base mb-2">
                Materials will be requested from the Centralized Stockroom and assigned to the selected department.
            </p>

            <select
                id="departmentSelect"
                name="department_id"
                class="w-full border rounded-lg p-4 text-lg"
                required>

                <option value="">
                    -- Select Department --
                </option>

                @foreach($departments as $department)

                    <option value="{{ $department->id }}">
                        {{ $department->department_name }}
                    </option>

                @endforeach

            </select>

        </div>

        <!-- SOURCE & DESTINATION -->
        <div class="border border-blue-200 bg-blue-50 rounded-xl p-5 mb-6">

            <h3 class="text-xl font-semibold text-blue-700 mb-4">
                🏢 Source & Destination
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div>
                    <p class="text-gray-500 text-base">
                        Source Location
                    </p>

                    <p class="font-bold text-xl">
                        Centralized Stockroom
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-base">
                        Requested For
                    </p>

                    <p
                        id="departmentPreview"
                        class="font-bold text-xl">

                        Selected Department / Office

                    </p>
                </div>

            </div>

        </div>

        <!-- MATERIALS NEEDED -->
        <div class="border rounded-lg p-5 mb-6 bg-gray-50">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">

                <h3 class="text-xl font-semibold text-blue-700 flex items-center gap-2">
                    🧾 Materials Needed
                    <span id="item-count-badge" class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        1 item
                    </span>
                </h3>

                <div class="w-full sm:w-72">
                    <select id="category-filter"
                        class="w-full border rounded-lg p-3 text-base">

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>
                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full border rounded-lg overflow-hidden" id="materialsTable">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="p-3 text-left text-lg">
                                Material
                            </th>

                            <th class="p-3 text-left text-lg w-48">
                                Quantity
                            </th>

                            <th class="p-3 text-center text-lg w-32">
                                Action
                            </th>
                        </tr>

                    </thead>

                    <tbody id="items-container" class="divide-y-2 divide-gray-300">

                        <!-- ITEM ROW -->
                        <tr class="item-row">

                            <td class="p-3 align-top">

                                <select name="material_id[]"
                                    class="material-select w-full"
                                    required>

                                    <option value="">🔍 Click to search materials...</option>

                                </select>

                                <p class="material-meta text-base text-gray-500 mt-1"></p>

                            </td>

                            <td class="p-3 align-top">

                                <input type="number"
                                    name="quantity[]"
                                    min="1"
                                    class="quantity-input w-full border rounded-lg p-4 text-lg"
                                    required>

                                <p class="stock-warning text-red-500 text-base mt-1 hidden">
                                    Exceeds available stock.
                                </p>

                            </td>

                            <td class="p-3 text-center align-top">

                                <button
                                    type="button"
                                    class="remove-item hidden bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg text-base font-semibold">

                                    Remove

                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <button
                type="button"
                id="add-item"
                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg text-lg font-semibold">

                ➕ Add Material

            </button>

        </div>

        <!-- REQUEST DETAILS -->
        <div class="border rounded-lg p-5 mb-6 bg-gray-50">

            <h3 class="text-xl font-semibold text-green-700 mb-4">
                📝 Request Details
            </h3>

            <label class="block mb-2 font-semibold text-lg">
                Room / Location
            </label>

            <p class="text-gray-500 text-base mb-2">
                Optional — where the materials will be used, e.g. "Room 201".
            </p>

            <input
                type="text"
                name="room"
                placeholder="Example: Room 201"
                class="w-full border rounded-lg p-4 text-lg mb-4">

            <label class="block mb-2 font-semibold text-lg">
                Purpose of Request
            </label>

            <textarea
                name="purpose"
                rows="4"
                placeholder="Example: Cleaning of ICT Laboratory"
                class="w-full border rounded-lg p-4 text-lg"
                required></textarea>

        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex gap-3">

            <button
                type="button"
                onclick="showConfirmationModal()"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-4 rounded-lg shadow text-lg">

                📨 Submit Material Requisition

            </button>

            <a href="{{ route('material-request.history') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-8 py-4 rounded-lg shadow text-lg">

                📋 View History

            </a>

        </div>

    </form>

</div>

<!-- Confirmation Modal -->
<div
    id="confirmationModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999] p-4">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 lg:p-8">

        <h2 class="text-2xl font-bold text-yellow-600 mb-4">
            ⚠ Confirm Material Requisition
        </h2>

        <div class="space-y-2 text-gray-700 text-lg">

           <p>
                <strong>Source Location:</strong>
                Centralized Stockroom
            </p>

            <p>
                <strong>Destination:</strong>
                <span id="confirmDepartment"></span>
            </p>

            <p id="confirmRoomRow">
                <strong>Room / Location:</strong>
                <span id="confirmRoom"></span>
            </p>

            <p>
                <strong>Purpose:</strong>
                <span id="confirmPurpose"></span>
            </p>

        </div>

        <div class="mt-5">

            <h3 class="font-semibold text-gray-800 mb-2 text-lg">
                Materials
            </h3>

            <div
                class="border rounded-lg p-3 bg-gray-50 max-h-64 overflow-y-auto">

                <table class="w-full text-lg">

                    <thead>

                        <tr class="border-b">

                            <th class="text-left py-2">
                                Material
                            </th>

                            <th class="text-right py-2">
                                Qty
                            </th>

                        </tr>

                    </thead>

                    <tbody id="confirmMaterials">

                    </tbody>

                </table>

            </div>

        </div>

        <div class="flex justify-end gap-3 mt-6">

            <button
                id="cancelRequestBtn"
                type="button"
                onclick="closeConfirmationModal()"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-lg">

                Cancel

            </button>

            <button
                id="confirmRequestBtn"
                type="button"
                onclick="submitMaterialRequestForm()"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg">

                Confirm Request

            </button>

        </div>

    </div>

</div>

<!-- Processing Modal -->
<div
    id="processingModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">

    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md text-center">

        <div class="flex justify-center mb-4">

            <svg
                class="animate-spin h-12 w-12 text-green-600"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24">

                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>

            </svg>

        </div>

        <h2 class="text-xl font-bold text-green-700">
            Submitting Material Requisition
        </h2>

        <p class="text-gray-600 mt-2 text-lg">
            Please wait...
        </p>

    </div>

</div>

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

<style>

    .ts-control {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        padding: 1rem !important;
        min-height: 58px !important;
        box-shadow: none !important;
        font-size: 18px !important;
        --ts-pr-min: 2.75rem;
    }

    .ts-control input {
        font-size: 18px !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .ts-wrapper.single .ts-control {
        background: white !important;
        position: relative;
    }

    /* Chevron so this reads as a dropdown, matching the native Department/Category selects */
    .ts-wrapper.single .ts-control::after {
        content: "";
        position: absolute;
        right: 1.1rem;
        top: 50%;
        width: 10px;
        height: 10px;
        margin-top: -6px;
        border-right: 2px solid #6b7280;
        border-bottom: 2px solid #6b7280;
        transform: rotate(45deg);
        pointer-events: none;
    }

    .ts-control:focus-within {
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 2px rgba(96,165,250,0.3) !important;
    }

    .ts-dropdown {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        overflow: hidden;
        font-size: 17px !important;
    }

    .ts-dropdown .option {
        padding: 0.6rem 1rem !important;
    }

    /* Zebra striping so item rows read as distinct records, not one blur */
    #materialsTable tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

</style>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>

    // ✅ MASTER MATERIAL DATA (single source of truth — avoids relying on
    // Tom Select re-syncing custom data attributes when options are rebuilt)
    const allMaterials = @json($materialsForJs);

    const materialsById = {};

    allMaterials.forEach(function (m) {
        materialsById[m.id] = m;
    });

    function getSelectedDepartmentId() {

        const value =
            document.getElementById('departmentSelect').value;

        return value ? parseInt(value) : null;
    }

    // ✅ (RE)BUILD A MATERIAL SELECT'S OPTIONS BASED ON CURRENT FILTERS
    function populateMaterialSelect(tom, categoryId) {

        const departmentId = getSelectedDepartmentId();

        const currentValue = tom.getValue();

        tom.clear(true);
        tom.clearOptions();

        tom.addOption({
            value: '',
            text: '🔍 Click to search materials...'
        });

        allMaterials.forEach(function (m) {

            if (categoryId && String(categoryId) !== String(m.category_id)) {
                return;
            }

            let text = m.name + ' — Stock: ' + m.stock;

            let disabled = false;

            if (m.stock <= 0) {

                text += ' (Out of Stock)';

                disabled = true;

            } else if (departmentId && m.department_id === departmentId) {

                text += ' (Already in this department)';

                disabled = true;

            }

            tom.addOption({
                value: String(m.id),
                text: text,
                disabled: disabled
            });

        });

        tom.refreshOptions(false);

        // Keep the previous selection if it's still valid under the new filters
        if (currentValue && materialsById[currentValue]) {
            tom.setValue(currentValue, true);
        }

    }

    // ✅ INIT TOMSELECT
    function initTomSelect(element) {

        const tom = new TomSelect(element, {
            create: false,
            dropdownParent: 'body',
            disabledField: 'disabled',
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        populateMaterialSelect(
            tom,
            document.getElementById('category-filter').value
        );

        // ✅ STOCK CHECK
        element.addEventListener('change', function () {

            const material = materialsById[element.value];

            const row =
                element.closest('.item-row');

            const quantityInput =
                row.querySelector('.quantity-input');

            const meta = row.querySelector('.material-meta');

            if (!meta) {
                return;
            }

            if (!material) {
                meta.innerHTML = '';
                quantityInput.max = 0;
                return;
            }

            let status = 'Available';
            let statusColor = 'text-green-600';

            if(material.stock <= 0){
                status = 'Out of Stock';
                statusColor = 'text-red-600';
            }
            else if(material.stock <= material.threshold){
                status = 'Low Stock';
                statusColor = 'text-yellow-600';
            }

            meta.innerHTML =
                `Category: <strong>${material.category_name}</strong> &nbsp;•&nbsp; Unit: <strong>${material.unit}</strong> &nbsp;•&nbsp; Available: <strong>${material.stock} ${material.unit}</strong> &nbsp;•&nbsp; <span class="${statusColor} font-semibold">${status}</span>`;

            quantityInput.max = material.stock;

        });

    }

    // ✅ INITIALIZE FIRST SELECT
    document.querySelectorAll('.material-select').forEach(select => {
        initTomSelect(select);
    });

    // ✅ ADD ITEM
    document.getElementById('add-item').addEventListener('click', function () {

        const tbody = document.getElementById('items-container');

        const newRow = document.createElement('tr');

        newRow.className = 'item-row';

        newRow.innerHTML = `

            <td class="p-3 align-top">

                <select
                    name="material_id[]"
                    class="material-select w-full"
                    required>

                    <option value="">🔍 Click to search materials...</option>

                </select>

                <p class="material-meta text-base text-gray-500 mt-1"></p>

            </td>

            <td class="p-3 align-top">

                <input type="number"
                    name="quantity[]"
                    min="1"
                    class="quantity-input w-full border rounded-lg p-4 text-lg"
                    required>

                <p class="stock-warning text-red-500 text-base mt-1 hidden">
                    Exceeds available stock.
                </p>

            </td>

            <td class="p-3 text-center align-top">

                <button
                    type="button"
                    class="remove-item bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg text-base font-semibold">

                    Remove

                </button>

            </td>
        `;

        tbody.appendChild(newRow);

        // INIT TOMSELECT
        initTomSelect(
            newRow.querySelector('.material-select')
        );

        updateItemCount();

    });

    // ✅ REMOVE ITEM
    document.addEventListener('click', function(e){

        if(e.target.closest('.remove-item')){

            const rows = document.querySelectorAll('.item-row');

            if(rows.length > 1){

                e.target.closest('.item-row').remove();

                updateItemCount();

            }

        }

    });

</script>

<script>

    // ✅ LIVE QUANTITY VALIDATION
    document.addEventListener('input', function(e){

        if(e.target.classList.contains('quantity-input')){

            const input = e.target;

            const row = input.closest('.item-row');

            const warning =
                row.querySelector('.stock-warning');

            const max =
                parseInt(input.max || 0);

            const value =
                parseInt(input.value || 0);

            // ❌ EXCEEDS STOCK
            if(value > max){

                warning.classList.remove('hidden');

                input.classList.add(
                    'border-red-500',
                    'ring-2',
                    'ring-red-300'
                );

            }

            // ✅ VALID
            else {

                warning.classList.add('hidden');

                input.classList.remove(
                    'border-red-500',
                    'ring-2',
                    'ring-red-300'
                );

            }

        }

    });

</script>

<script>

document.addEventListener('change', function(e){

    if(!e.target.classList.contains('material-select')) {
        return;
    }

    const currentSelect = e.target;

    const currentValue = currentSelect.value;

    if(currentValue === '') {
        return;
    }

    let duplicateFound = false;

    document.querySelectorAll('.material-select').forEach(select => {

        if(
            select !== currentSelect &&
            select.value === currentValue
        ){
            duplicateFound = true;
        }

    });

    if(duplicateFound){

        alert('This material is already selected.');

        if(currentSelect.tomselect){
            currentSelect.tomselect.clear();
        }else{
            currentSelect.value = '';
        }

    }

});

</script>

<script>

    // ✅ ITEM COUNT BADGE
    function updateItemCount() {

        const itemCountBadge =
            document.getElementById('item-count-badge');

        const rowCount =
            document.querySelectorAll('.item-row').length;

        if(itemCountBadge){
            itemCountBadge.innerText = rowCount + (rowCount === 1 ? ' item' : ' items');
        }
    }

    document.addEventListener('change', updateItemCount);

</script>

<script>

document.getElementById('category-filter')
.addEventListener('change', function () {

    const categoryId = this.value;

    document.querySelectorAll('.material-select').forEach(select => {

        const tom = select.tomselect;

        if (!tom) return;

        populateMaterialSelect(tom, categoryId);

    });

});

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const departmentSelect =
        document.getElementById('departmentSelect');

    const departmentPreview =
        document.getElementById('departmentPreview');

    departmentSelect.addEventListener('change', function () {

        departmentPreview.textContent =
            this.options[this.selectedIndex].text;

        const departmentId = getSelectedDepartmentId();

        const categoryId =
            document.getElementById('category-filter').value;

        let clearedMaterials = [];

        document.querySelectorAll('.material-select').forEach(select => {

            const tom = select.tomselect;

            if (!tom) return;

            const currentValue = tom.getValue();

            const currentMaterial = materialsById[currentValue];

            // If the material picked in this row is already assigned
            // to the newly selected department, clear it and warn.
            if (
                currentMaterial &&
                departmentId &&
                currentMaterial.department_id === departmentId
            ) {

                clearedMaterials.push(currentMaterial.name);

                tom.clear(true);

            }

            populateMaterialSelect(tom, categoryId);

        });

        if (clearedMaterials.length > 0) {

            alert(
                'These materials were removed because they are already assigned to the selected department: '
                + clearedMaterials.join(', ')
            );

        }

    });

});

function showConfirmationModal()
{
    const departmentSelect =
        document.getElementById('departmentSelect');

    const department =
        departmentSelect.options[
            departmentSelect.selectedIndex
        ].text;

    const room =
        document.querySelector(
            'input[name="room"]'
        ).value;

    const purpose =
        document.querySelector(
            'textarea[name="purpose"]'
        ).value;

    if(
        !departmentSelect.value ||
        !purpose
    )
    {
        alert(
            'Please complete the department and purpose fields.'
        );

        return;
    }

    let materialRows = '';

    const emptyRows = [];

    document.querySelectorAll(
        '#materialsTable tbody tr'
    ).forEach(row => {

        const select =
            row.querySelector('.material-select');

        const quantity =
            row.querySelector(
                'input[name="quantity[]"]'
            );

        if(
            select.value &&
            parseInt(quantity.value) > 0
        )
        {
            materialRows += `
                <tr class="border-b">
                    <td class="py-2">
                        ${select.options[select.selectedIndex].text}
                    </td>
                    <td class="text-right py-2">
                        ${quantity.value}
                    </td>
                </tr>
            `;
        }
        else
        {
            // Row has no material picked (or no quantity) — it's an
            // unused "Add Material" row, not a real request. Drop it
            // so it never gets submitted and never trips server validation.
            emptyRows.push(row);
        }
    });

    if(materialRows === '')
    {
        alert(
            'Please add at least one material.'
        );

        return;
    }

    emptyRows.forEach(row => row.remove());

    updateItemCount();

    document.getElementById(
        'confirmDepartment'
    ).textContent = department;

    document.getElementById(
        'confirmRoomRow'
    ).classList.toggle('hidden', !room);

    document.getElementById(
        'confirmRoom'
    ).textContent = room;

    document.getElementById(
        'confirmPurpose'
    ).textContent = purpose;

    document.getElementById(
        'confirmMaterials'
    ).innerHTML = materialRows;

    document.getElementById(
        'confirmationModal'
    ).classList.remove('hidden');

    document.getElementById(
        'confirmationModal'
    ).classList.add('flex');
}

function closeConfirmationModal()
{
    document.getElementById(
        'confirmationModal'
    ).classList.add('hidden');

    document.getElementById(
        'confirmationModal'
    ).classList.remove('flex');
}

let isSubmitting = false;

function submitMaterialRequestForm()
{
    if (isSubmitting)
    {
        return;
    }

    isSubmitting = true;

    document.getElementById(
        'confirmRequestBtn'
    ).disabled = true;

    document.getElementById(
        'cancelRequestBtn'
    ).disabled = true;

    document.getElementById(
        'confirmationModal'
    ).classList.add('hidden');

    document.getElementById(
        'confirmationModal'
    ).classList.remove('flex');

    document.getElementById(
        'processingModal'
    ).classList.remove('hidden');

    document.getElementById(
        'processingModal'
    ).classList.add('flex');

    document.getElementById(
        'materialRequestForm'
    ).submit();
}

</script>

@endsection
