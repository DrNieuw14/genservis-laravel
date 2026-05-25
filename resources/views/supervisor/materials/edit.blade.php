@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto mt-8">

    <div class="bg-white rounded-2xl shadow-2xl p-8">

        <!-- TITLE -->
        <h2 class="text-3xl font-bold mb-6 text-gray-800">
            ✏️ Edit Material
        </h2>

        <!-- ERRORS -->
        @if ($errors->any())

            <div class="bg-red-500 text-white p-4 rounded-xl mb-6">

                <ul class="list-disc ml-5">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <!-- FORM -->
        <form action="{{ route('materials.update', $material->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <!-- NAME -->
            <div class="mb-5">

                <label class="block text-gray-700 font-semibold mb-2">
                    Material Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ $material->name }}"
                       class="w-full border rounded-xl p-3">

            </div>

            <!-- CATEGORY -->
            <div class="mb-5">

                <label class="block text-gray-700 font-semibold mb-2">
                    Category
                </label>

                <select name="category_id"
                        class="w-full border rounded-xl p-3">

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}"
                            {{ $material->category_id == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- UNIT -->
            <div class="mb-5">

                <label class="block text-gray-700 font-semibold mb-2">
                    Unit
                </label>

                <select name="unit_id"
                        class="w-full border rounded-xl p-3">

                    @foreach($units as $unit)

                        <option value="{{ $unit->id }}"
                            {{ $material->unit_id == $unit->id ? 'selected' : '' }}>

                            {{ $unit->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- DEPARTMENT -->
            <div class="mb-5">

                <label class="block text-gray-700 font-semibold mb-2">
                    Department
                </label>

                <select name="department_id"
                        class="w-full border rounded-xl p-3">

                    @foreach($departments as $department)

                        <option value="{{ $department->id }}"
                            {{ $material->department_id == $department->id ? 'selected' : '' }}>

                            {{ $department->department_name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- QUANTITY -->
            <div class="mb-5">

                <label class="block text-gray-700 font-semibold mb-2">
                    Quantity
                </label>

                <input type="number"
                       name="quantity"
                       value="{{ $material->quantity }}"
                       class="w-full border rounded-xl p-3">

            </div>

            <!-- THRESHOLD -->
            <div class="mb-6">

                <label class="block text-gray-700 font-semibold mb-2">
                    Low Stock Threshold
                </label>

                <input type="number"
                       name="threshold"
                       value="{{ $material->threshold }}"
                       class="w-full border rounded-xl p-3">

            </div>

            <!-- BUTTON -->
            <button
                class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-6 py-3 rounded-xl shadow-lg hover:scale-105 transition">

                💾 Update Material

            </button>

        </form>

    </div>

</div>

@endsection