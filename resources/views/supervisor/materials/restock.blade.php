@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto mt-10">

    <div class="bg-white rounded-2xl shadow-2xl p-8">

        <!-- TITLE -->
        <div class="mb-6">

            <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                📦 Restock Material
            </h2>

            <p class="text-gray-500 mt-2">
                Add new stock for this material
            </p>

        </div>

        <!-- MATERIAL INFO -->
        <div class="bg-gray-50 rounded-xl p-5 mb-6 border">

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <p class="text-sm text-gray-500">Material Name</p>

                    <p class="font-semibold text-lg">
                        {{ $material->name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Current Stock</p>

                    <p class="font-semibold text-lg text-blue-600">
                        {{ $material->quantity }}
                    </p>
                </div>

            </div>

        </div>

        <!-- FORM -->
        <form action="{{ route('materials.restock', $material->id) }}"
              method="POST">

            @csrf

            <!-- QUANTITY -->
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Restock Quantity
                </label>

                <input type="number"
                       name="quantity"
                       min="1"
                       required
                       class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-green-400">

            </div>

            <!-- SUPPLIER -->
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Supplier Name
                </label>

                <input type="text"
                       name="supplier"
                       placeholder="Example: ABC Cleaning Supplies"
                       class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-green-400">

            </div>

            <!-- REMARKS -->
            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="4"
                    placeholder="Optional remarks..."
                    class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-green-400"></textarea>

            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('materials.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-3 rounded-xl">

                    Cancel
                </a>

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow-lg">

                    📦 Save Restock
                </button>

            </div>

        </form>

    </div>

</div>

@endsection