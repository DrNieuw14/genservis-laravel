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

    <form action="{{ route('walkin.store') }}" method="POST">
        @csrf

        <!-- Request Information -->
        <div class="border rounded-lg p-5 mb-6 bg-gray-50">

            <h3 class="text-lg font-semibold text-green-700 mb-4">
                Request Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

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
                        Department
                    </label>

                    <select
                        name="department_id"
                        class="w-full border rounded-lg p-3"
                        required>

                        <option value="">
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

                            <option value="{{ $material->id }}">
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
                type="submit"
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

</script>

@endsection