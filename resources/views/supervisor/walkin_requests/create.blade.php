@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">
                📦 Walk-In Material Issuance
            </h2>

            <p class="text-gray-500 mt-1">
                Issue materials directly to departments without creating a request.
            </p>
        </div>
    </div>

    <form
    id="walkinForm"
    action="{{ route('walkin.store') }}"
    method="POST">
        @csrf

        <!-- Destination Department -->
        <div class="mb-6">

            <label class="block mb-2 font-semibold">
                Destination Department / Office
            </label>

            <select
                id="departmentSelect"
                name="department_id"
                class="w-full border rounded-lg p-3"
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

        <!-- Source & Destination -->
        <div class="border border-blue-200 bg-blue-50 rounded-xl p-5 mb-6">

            <h3 class="text-lg font-semibold text-blue-700 mb-4">
                🏢 Source & Destination
            </h3>

            <div class="grid grid-cols-2 gap-6">

                <div>
                    <p class="text-gray-500 text-sm">
                        Source Location
                    </p>

                    <p class="font-bold text-lg">
                        Centralized Stockroom
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">
                        Requested For
                    </p>

                    <p
                        id="departmentPreview"
                        class="font-bold text-lg">

                        Selected Department / Office

                    </p>
                </div>

            </div>

        </div>

        <div class="border rounded-lg p-5 mb-6 bg-gray-50">

    <h3 class="text-lg font-semibold text-green-700 mb-4">
        📝 Issuance Information
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="font-semibold">
                    Employee Name
                </label>

                <button
                    type="button"
                    onclick="openQuickAddModal()"
                    class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                    ➕ Add New Employee
                </button>
            </div>

            <select
                id="employeeSelect"
                name="personnel_id"
                class="employee-select w-full"
                required>

                <option value="">
                    -- Select Employee --
                </option>

                @foreach($employees as $employee)
                    <option
                        value="{{ $employee->id }}"
                        data-name="{{ $employee->fullname }}">

                        {{ $employee->fullname }}
                        ({{ $employee->employee_id }})

                    </option>
                @endforeach

            </select>
        </div>

        <div>
            <label class="block mb-2 font-semibold">
                Room
            </label>

            <input
                type="text"
                name="room"
                class="w-full border rounded-lg p-3"
                required>
        </div>

        <div>
            <label class="block mb-2 font-semibold">
                Purpose
            </label>

            <input
                type="text"
                name="purpose"
                class="w-full border rounded-lg p-3"
                required>
        </div>

    </div>

</div>

        <!-- Material Information -->
<div class="border rounded-lg p-5 bg-gray-50">

    <h3 class="text-lg font-semibold text-blue-700 mb-4">
        Material Information
    </h3>

    <table
        class="w-full border rounded-lg overflow-hidden"
        id="materialsTable">

        <thead class="bg-gray-100">

            <tr>
                <th class="p-3 text-left">
                    Material
                </th>

                <th class="p-3 text-left">
                    Quantity
                </th>

                <th class="p-3 text-center">
                    Action
                </th>
            </tr>

        </thead>

        <tbody>

            <tr>

                <td class="p-2">

                    <select
                        name="material_id[]"
                        class="material-select w-full"
                        required>

                        <option value="">
                            🔍 Click to search materials...
                        </option>

                    </select>

                    <img class="material-thumb hidden w-12 h-12 object-cover rounded-lg border mt-1" alt="">

                </td>

                <td class="p-2">

                    <input
                        type="number"
                        name="quantity[]"
                        min="1"
                        class="w-full border rounded-lg p-3"
                        required>

                </td>

                <td class="text-center">

                    <button
                        type="button"
                        onclick="removeRow(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">

                        Remove

                    </button>

                </td>

            </tr>

        </tbody>

    </table>

    <button
        type="button"
        onclick="addRow()"
        class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

        ➕ Add Material

    </button>

</div>

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-3">

            <button
                type="button"
                onclick="showConfirmationModal()"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

                📤 Issue Material

            </button>

            <a href="{{ route('walkin.history') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

                📋 View History

            </a>

        </div>

    </form>

</div>

<script>

function addRow()
{
    const tbody =
        document.querySelector(
            '#materialsTable tbody'
        );

    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td class="p-2">
            <select
                name="material_id[]"
                class="material-select w-full"
                required>
                <option value="">🔍 Click to search materials...</option>
            </select>
            <img class="material-thumb hidden w-12 h-12 object-cover rounded-lg border mt-1" alt="">
        </td>

        <td class="p-2">
            <input
                type="number"
                name="quantity[]"
                min="1"
                class="w-full border rounded-lg p-3"
                required>
        </td>

        <td class="text-center">
            <button
                type="button"
                onclick="removeRow(this)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">
                Remove
            </button>
        </td>
    `;

    tbody.appendChild(newRow);

    initMaterialTomSelect(
        newRow.querySelector('.material-select')
    );
}

function removeRow(button)
{
    let table =
        document.querySelector(
            '#materialsTable tbody'
        );

    if(table.rows.length > 1)
    {
        button.closest('tr').remove();
    }
}

function showConfirmationModal()
{
    const employeeSelect =
        document.getElementById('employeeSelect');

    const employee = employeeSelect.value
        ? employeeSelect.options[employeeSelect.selectedIndex].dataset.name
        : '';

    const room =
        document.querySelector(
            'input[name="room"]'
        ).value;

    const purpose =
        document.querySelector(
            'input[name="purpose"]'
        ).value;

    const departmentSelect =
        document.querySelector(
            'select[name="department_id"]'
        );

    const department =
        departmentSelect.options[
            departmentSelect.selectedIndex
        ].text;

    if(
        !employee ||
        !room ||
        !purpose ||
        !departmentSelect.value
    )
    {
        alert(
            'Please complete all request information.'
        );

        return;
    }

    document.getElementById(
        'confirmEmployee'
    ).textContent = employee;

    document.getElementById(
        'confirmDepartment'
    ).textContent = department;

    document.getElementById(
        'confirmRoom'
    ).textContent = room;

    document.getElementById(
        'confirmPurpose'
    ).textContent = purpose;

    let materialRows = '';

    document.querySelectorAll(
        '#materialsTable tbody tr'
    ).forEach(row => {

        let materialSelect =
            row.querySelector('select[name="material_id[]"]');

        let quantity =
            row.querySelector(
                'input[name="quantity[]"]'
            );

        const material =
            materialsById[materialSelect.value];

        if(
            material &&
            parseInt(quantity.value) > 0
        )
        {
            materialRows += `
                <tr class="border-b">
                    <td class="py-2">
                        ${material.name}
                    </td>
                    <td class="text-right py-2">
                        ${quantity.value}
                    </td>
                </tr>
            `;
        }
    });

    if(materialRows === '')
    {
        alert(
            'Please add at least one material.'
        );

        return;
    }

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

function submitWalkinForm()
{
    if (isSubmitting)
    {
        return;
    }

    isSubmitting = true;

    document.getElementById(
        'confirmIssueBtn'
    ).disabled = true;

    document.getElementById(
        'cancelIssueBtn'
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
        'walkinForm'
    ).submit();
}

document.addEventListener('DOMContentLoaded', function () {

    const departmentSelect =
        document.getElementById('departmentSelect');

    const departmentPreview =
        document.getElementById('departmentPreview');

    departmentSelect.addEventListener('change', function () {

        departmentPreview.textContent =
            this.options[this.selectedIndex].text;

    });

});

</script>

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

<style>

    .ts-control {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        padding: 0.75rem !important;
        min-height: 50px !important;
        box-shadow: none !important;
        font-size: 16px !important;
        --ts-pr-min: 2.75rem;
    }

    .ts-control input {
        font-size: 16px !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .ts-wrapper.single .ts-control {
        background: white !important;
        position: relative;
    }

    /* Chevron so this reads as a dropdown, matching the native selects on this page */
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
        font-size: 15px !important;
    }

    .ts-dropdown .option {
        padding: 0.6rem 1rem !important;
    }

</style>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>

    const employeeTomSelect = new TomSelect('#employeeSelect', {
        create: false,
        dropdownParent: 'body',
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    });

    // ✅ MATERIAL SEARCH (Tom Select) — single source of truth, mirrors
    // the Material Request form's picker so behavior stays consistent
    const allMaterials = @json($materialsForJs);

    const materialsById = {};

    allMaterials.forEach(function (m) {
        materialsById[m.id] = m;
    });

    function populateMaterialSelect(tom)
    {
        const currentValue = tom.getValue();

        tom.clear(true);
        tom.clearOptions();

        tom.addOption({
            value: '',
            text: '🔍 Click to search materials...'
        });

        allMaterials.forEach(function (m) {

            let text = m.name + ' (Available: ' + m.stock + ')';

            let disabled = false;

            if (m.stock <= 0) {
                text += ' — Out of Stock';
                disabled = true;
            }

            tom.addOption({
                value: String(m.id),
                text: text,
                disabled: disabled
            });

        });

        tom.refreshOptions(false);

        if (currentValue && materialsById[currentValue]) {
            tom.setValue(currentValue, true);
        }
    }

    function initMaterialTomSelect(element)
    {
        const tom = new TomSelect(element, {
            create: false,
            dropdownParent: 'body',
            disabledField: 'disabled',
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });

        populateMaterialSelect(tom);

        element.addEventListener('change', function () {

            const thumb = element.closest('td').querySelector('.material-thumb');

            if (!thumb) {
                return;
            }

            const material = materialsById[element.value];

            if (material && material.image_url) {
                thumb.src = material.image_url;
                thumb.alt = material.name;
                thumb.classList.remove('hidden');
            } else {
                thumb.classList.add('hidden');
                thumb.src = '';
            }

        });

        return tom;
    }

    document.querySelectorAll('.material-select').forEach(function (select) {
        initMaterialTomSelect(select);
    });

    function openQuickAddModal()
    {
        document.getElementById('quickAddForm').reset();

        document.getElementById('quickAddEmployeeId').value = '';

        document.getElementById('quickAddPosition').innerHTML =
            '<option value="">Select Employment Type First</option>';

        document.getElementById('quickAddError').classList.add('hidden');

        document.getElementById('quickAddModal')
            .classList.remove('hidden');

        document.getElementById('quickAddModal')
            .classList.add('flex');
    }

    function closeQuickAddModal()
    {
        document.getElementById('quickAddModal')
            .classList.add('hidden');

        document.getElementById('quickAddModal')
            .classList.remove('flex');
    }

    function showEmployeeCreatedModal(data)
    {
        document.getElementById('createdEmployeeName').textContent =
            `${data.fullname} (${data.employee_id})`;

        document.getElementById('createdUsername').textContent =
            data.username;

        document.getElementById('createdPassword').textContent =
            data.temporary_password;

        document.getElementById('copyCredentialsBtn').textContent =
            '📋 Copy Credentials';

        document.getElementById('employeeCreatedModal')
            .classList.remove('hidden');

        document.getElementById('employeeCreatedModal')
            .classList.add('flex');
    }

    function closeEmployeeCreatedModal()
    {
        document.getElementById('employeeCreatedModal')
            .classList.add('hidden');

        document.getElementById('employeeCreatedModal')
            .classList.remove('flex');
    }

    function copyCredentials()
    {
        const username =
            document.getElementById('createdUsername').textContent;

        const password =
            document.getElementById('createdPassword').textContent;

        const text = `Username: ${username}\nTemporary Password: ${password}`;

        navigator.clipboard.writeText(text).then(() => {

            const btn = document.getElementById('copyCredentialsBtn');

            btn.textContent = '✔ Copied!';

            setTimeout(() => {
                btn.textContent = '📋 Copy Credentials';
            }, 1500);

        });
    }

    // Deferred until DOMContentLoaded: the Quick Add modal markup sits at the
    // bottom of the page (after this script), so getElementById would return
    // null and addEventListener would silently no-op if run immediately here.
    document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('quickAddEmploymentType')
        .addEventListener('change', function () {

        const employmentTypeId = this.value;

        const employeeIdField =
            document.getElementById('quickAddEmployeeId');

        const positionField =
            document.getElementById('quickAddPosition');

        if (!employmentTypeId) {
            employeeIdField.value = '';
            positionField.innerHTML =
                '<option value="">Select Employment Type First</option>';
            return;
        }

        fetch(`{{ url('/walkin-requests/generate-employee-id') }}/${employmentTypeId}`)
            .then(response => response.json())
            .then(data => {
                employeeIdField.value = data.employee_id;
            })
            .catch(() => {
                employeeIdField.value = '';
            });

        fetch(`{{ url('/walkin-requests/employment-type') }}/${employmentTypeId}/positions`)
            .then(response => response.json())
            .then(data => {

                positionField.innerHTML =
                    '<option value="">Select Position</option>';

                data.forEach(item => {
                    positionField.innerHTML += `
                        <option value="${item.id}">
                            ${item.position_name}
                        </option>
                    `;
                });

            })
            .catch(() => {
                positionField.innerHTML =
                    '<option value="">Unable to load positions</option>';
            });

    });

    document.getElementById('quickAddForm')
        .addEventListener('submit', function (e) {

        e.preventDefault();

        const submitBtn =
            document.getElementById('quickAddSubmitBtn');

        const errorBox =
            document.getElementById('quickAddError');

        errorBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';

        const formData = new FormData(this);

        fetch('{{ route('walkin.quick-add-employee') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {

            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            return data;
        })
        .then(data => {

            employeeTomSelect.addOption({
                value: String(data.id),
                text: `${data.fullname} (${data.employee_id})`
            });

            employeeTomSelect.addItem(String(data.id));

            closeQuickAddModal();

            showEmployeeCreatedModal(data);

        })
        .catch(error => {

            let message = error.message || 'Unable to create employee. Please check the form and try again.';

            if (error.errors) {
                message = Object.values(error.errors)
                    .map(messages => messages[0])
                    .join(' ');
            }

            errorBox.textContent = message;
            errorBox.classList.remove('hidden');

        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = '✔ Create Employee';
        });

    });

    }); // end DOMContentLoaded

</script>

<!-- Confirmation Modal -->
<div
    id="confirmationModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">

        <h2 class="text-2xl font-bold text-yellow-600 mb-4">
            ⚠ Confirm Material Issuance
        </h2>

        <div class="space-y-2 text-gray-700">

           <p>
                <strong>Source Location:</strong>
                Centralized Stockroom
            </p>

            <p>
                <strong>Destination:</strong>
                <span id="confirmDepartment"></span>
            </p>

            <p>
                <strong>Requested By:</strong>
                <span id="confirmEmployee"></span>
            </p>

            <p>
                <strong>Room:</strong>
                <span id="confirmRoom"></span>
            </p>

            <p>
                <strong>Purpose:</strong>
                <span id="confirmPurpose"></span>
            </p>
            
        </div>

        <div class="mt-5">

            <h3 class="font-semibold text-gray-800 mb-2">
                Materials
            </h3>

            <div
                class="border rounded-lg p-3 bg-gray-50 max-h-64 overflow-y-auto">

                <table class="w-full">

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

        <div
            class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">

            This action will deduct inventory stock.

        </div>

        <div class="flex justify-end gap-3 mt-6">

            <button
                id="cancelIssueBtn"
                type="button"
                onclick="closeConfirmationModal()"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">

                Cancel

            </button>

            <button
                id="confirmIssueBtn"
                type="button"
                onclick="submitWalkinForm()"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">

                Confirm Issue

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
            Processing Material Issuance
        </h2>

        <p class="text-gray-600 mt-2">
            Please wait...
        </p>

    </div>

</div>

<!-- Quick Add Employee Modal -->
<div
    id="quickAddModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">

        <h2 class="text-2xl font-bold text-gray-800 mb-1">
            ➕ Add New Employee
        </h2>

        <p class="text-gray-500 mb-4">
            Not in the list? Create their employee record so this
            issuance — and any future one — can be traced back to them.
        </p>

        <div
            id="quickAddError"
            class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
        </div>

        <form id="quickAddForm">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="block mb-1 font-semibold text-sm">
                        Full Name
                    </label>
                    <input
                        type="text"
                        name="fullname"
                        class="w-full border rounded-lg p-3"
                        required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">
                        Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        class="w-full border rounded-lg p-3"
                        required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">
                        Username
                    </label>
                    <input
                        type="text"
                        name="username"
                        class="w-full border rounded-lg p-3"
                        required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">
                        Employment Type
                    </label>
                    <select
                        id="quickAddEmploymentType"
                        name="employment_type_id"
                        class="w-full border rounded-lg p-3"
                        required>

                        <option value="" selected disabled>
                            Select Employment Type
                        </option>

                        @foreach($employmentTypes as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">
                        Employee ID
                    </label>
                    <input
                        id="quickAddEmployeeId"
                        type="text"
                        readonly
                        placeholder="Auto-generated"
                        class="w-full border rounded-lg p-3 bg-gray-100">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">
                        Department
                    </label>
                    <select
                        name="department_id"
                        class="w-full border rounded-lg p-3"
                        required>

                        <option value="" selected disabled>
                            Select Department
                        </option>

                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">
                                {{ $department->department_name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-sm">
                        Position
                    </label>
                    <select
                        id="quickAddPosition"
                        name="position_id"
                        class="w-full border rounded-lg p-3"
                        required>

                        <option value="">
                            Select Employment Type First
                        </option>

                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-6">

                <button
                    type="button"
                    onclick="closeQuickAddModal()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">
                    Cancel
                </button>

                <button
                    id="quickAddSubmitBtn"
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                    ✔ Create Employee
                </button>

            </div>

        </form>

    </div>

</div>

<!-- Employee Created Modal -->
<div
    id="employeeCreatedModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 text-center">

        <div class="flex justify-center mb-4">

            <div class="bg-green-100 rounded-full p-4">
                <svg
                    class="h-8 w-8 text-green-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2.5">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7" />

                </svg>
            </div>

        </div>

        <h2 class="text-xl font-bold text-gray-800">
            Employee Created Successfully
        </h2>

        <p id="createdEmployeeName" class="text-gray-500 mt-1 mb-5"></p>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-left space-y-3">

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Username
                </p>
                <p id="createdUsername" class="font-mono text-lg text-gray-800"></p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Temporary Password
                </p>
                <p id="createdPassword" class="font-mono text-lg text-gray-800"></p>
            </div>

        </div>

        <button
            id="copyCredentialsBtn"
            type="button"
            onclick="copyCredentials()"
            class="text-sm text-blue-600 hover:text-blue-800 font-semibold mt-3 mb-5">
            📋 Copy Credentials
        </button>

        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg p-3 mb-6 text-left">
            ⚠ Share these credentials with the employee directly so they can log in and set their own password.
        </div>

        <button
            type="button"
            onclick="closeEmployeeCreatedModal()"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            Got It
        </button>

    </div>

</div>

@endsection