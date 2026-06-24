@extends('layouts.app')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Walk-In Material Issuance
    </h2>

    <form action="{{ route('walkin.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="block mb-2 font-semibold">
                    Employee Name
                </label>

                <input
                    type="text"
                    name="employee_name"
                    class="w-full border rounded p-2"
                    required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">
                    Department
                </label>

                <select
                    name="department_id"
                    class="w-full border rounded p-2"
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

        </div>

        <div class="mt-4">

            <label class="block mb-2 font-semibold">
                Material
            </label>

            <select
                name="material_id"
                class="w-full border rounded p-2"
                required>

                <option value="">
                    Select Material
                </option>

                @foreach($materials as $material)

                    <option value="{{ $material->id }}">
                        {{ $material->name }}
                        (Stock: {{ $material->quantity }})
                    </option>

                @endforeach

            </select>

        </div>

        <div class="mt-4">

            <label class="block mb-2 font-semibold">
                Quantity
            </label>

            <input
                type="number"
                name="quantity"
                class="w-full border rounded p-2"
                min="1"
                required>

        </div>

        <div class="mt-4">
            <label class="block mb-2 font-semibold">
                Room
            </label>

            <input
                type="text"
                name="room"
                class="w-full border rounded p-2"
                required>
        </div>

        <div class="mt-4">
            <label class="block mb-2 font-semibold">
                Purpose
            </label>

            <input
                type="text"
                name="purpose"
                class="w-full border rounded p-2"
                required>
        </div>

        <div class="mt-6">

            <button
                type="submit"
                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">

                Issue Material

            </button>

        </div>

    </form>

</div>

@endsection