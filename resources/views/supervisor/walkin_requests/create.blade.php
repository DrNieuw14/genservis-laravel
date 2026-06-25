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
            <label class="block mb-2 font-semibold">
                Employee Name
            </label>

            <input
                type="text"
                name="employee_name"
                class="w-full border rounded-lg p-3"
                required>
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
                        class="w-full border rounded-lg p-3"
                        required>

                        <option value="">
                            Select Material
                        </option>

                        @foreach($materials as $material)

                            <option
                                value="{{ $material->id }}"
                                data-name="{{ $material->name }}">

                                {{ $material->name }}
                                (Available: {{ $material->quantity }})

                            </option>

                        @endforeach

                    </select>

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
    let table =
        document.querySelector(
            '#materialsTable tbody'
        );

    let row =
        table.rows[0].cloneNode(true);

    row.querySelectorAll('input')
        .forEach(input => input.value = '');

    row.querySelector('select')
        .selectedIndex = 0;

    table.appendChild(row);
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
    const employee =
        document.querySelector(
            'input[name="employee_name"]'
        ).value;

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

        let material =
            row.querySelector('select');

        let quantity =
            row.querySelector(
                'input[name="quantity[]"]'
            );

        if(
            material.value &&
            parseInt(quantity.value) > 0
        )
        {
            materialRows += `
                <tr class="border-b">
                    <td class="py-2">
                        ${material.options[
                            material.selectedIndex
                        ].dataset.name}
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

@endsection