@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto mt-8">

    <!-- PAGE TITLE -->
    <div class="mb-6">

        <h2 class="text-3xl font-bold text-white flex items-center gap-2">
            ➕ Add Material
        </h2>

        <p class="text-gray-200 mt-2">
            Create and manage inventory materials for GenServis.
        </p>

    </div>

    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl shadow-2xl p-8">

        <!-- ERROR MESSAGE -->
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
        <form action="{{ route('materials.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- MATERIAL NAME -->
                <div>

                    <label class="block text-gray-700 font-semibold mb-2">
                        Material Name
                    </label>

                    <input type="text"
                           name="name"
                           class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400"
                           placeholder="Enter material name"
                           required>

                </div>

                <!-- QUANTITY -->
                <div>

                    <label class="block text-gray-700 font-semibold mb-2">
                        Quantity
                    </label>

                    <input type="number"
                           name="quantity"
                           class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400"
                           placeholder="Enter quantity"
                           required>

                </div>

                <!-- CATEGORY -->
                <div>

                    <label class="block text-gray-700 font-semibold mb-2">
                        Category
                    </label>

                    <select name="category_id"
                            class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400"
                            required>

                        <option value="">Select Category</option>

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- UNIT -->
                <div>

                    <label class="block text-gray-700 font-semibold mb-2">
                        Unit
                    </label>

                    <select name="unit_id"
                            class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400"
                            required>

                        <option value="">Select Unit</option>

                        @foreach($units as $unit)

                            <option value="{{ $unit->id }}">
                                {{ $unit->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- LOW STOCK THRESHOLD -->
                <div class="md:col-span-2">

                    <label class="block text-gray-700 font-semibold mb-2">
                        Low Stock Threshold
                    </label>

                    <input type="number"
                           name="threshold"
                           class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-yellow-400"
                           placeholder="Example: 5"
                           required>

                    <p class="text-sm text-gray-500 mt-2">
                        System will alert supervisors when stock reaches this level.
                    </p>

                </div>

            </div>

            <!-- BUTTONS -->
            <div class="flex gap-4 mt-8">

                <!-- SAVE -->
                <button type="submit"
                        class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-6 py-3 rounded-xl shadow-lg hover:scale-105 transition">

                    💾 Save Material

                </button>

                <!-- CANCEL -->
                <a href="{{ route('materials.index') }}"
                   class="bg-gray-300 text-gray-800 px-6 py-3 rounded-xl hover:bg-gray-400 transition">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@endsection