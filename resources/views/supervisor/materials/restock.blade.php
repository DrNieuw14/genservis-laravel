@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-4xl font-bold text-white flex items-center gap-3">

            📦 Restock Material

        </h2>

        <p class="text-white/80 mt-2">

            Add stock quantity to inventory materials.

        </p>

    </div>

    <!-- ALERT -->
    @if ($errors->any())

        <div class="bg-red-500 text-white p-4 rounded-xl mb-6 shadow">

            <ul class="list-disc pl-5">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-2xl p-8">

        <!-- MATERIAL INFO -->
        <div class="mb-8">

            <h3 class="text-2xl font-bold text-gray-800">

                {{ $material->name }}

            </h3>

            <p class="text-gray-500 mt-2">

                Current Stock:

                <span class="font-bold text-blue-600">

                    {{ $material->quantity }}

                </span>

            </p>

        </div>

        <!-- FORM -->
        <form method="POST"
              action="{{ route('materials.restock', $material->id) }}">

            @csrf

            <!-- ADD STOCK -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold text-gray-700">

                    Add Stock Quantity

                </label>

                <input type="number"
                       name="added_stock"
                       min="1"
                       required
                       class="w-full border border-gray-300
                              rounded-xl px-4 py-3
                              focus:ring-2 focus:ring-blue-400
                              focus:outline-none">

            </div>

            <!-- SUPPLIER -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold text-gray-700">

                    Supplier

                </label>

                <input type="text"
                       name="supplier"
                       placeholder="Optional supplier name"
                       class="w-full border border-gray-300
                              rounded-xl px-4 py-3
                              focus:ring-2 focus:ring-blue-400
                              focus:outline-none">

            </div>

            <!-- REMARKS -->
            <div class="mb-6">

                <label class="block mb-2 font-semibold text-gray-700">

                    Remarks

                </label>

                <textarea name="remarks"
                          rows="4"
                          placeholder="Optional remarks..."
                          class="w-full border border-gray-300
                                 rounded-xl px-4 py-3
                                 focus:ring-2 focus:ring-blue-400
                                 focus:outline-none"></textarea>

            </div>

            <!-- BUTTONS -->
            <div class="flex items-center gap-3">

                <!-- SUBMIT -->
                <button type="submit"
                        class="bg-gradient-to-r from-green-500 to-blue-500
                               hover:scale-105 transition
                               text-white px-6 py-3 rounded-xl
                               shadow-lg font-semibold">

                    ➕ Restock Material

                </button>

                <!-- BACK -->
                <a href="{{ route('materials.index') }}"
                   class="bg-gray-200 hover:bg-gray-300
                          text-gray-700 px-6 py-3
                          rounded-xl shadow font-semibold">

                    ← Back

                </a>

            </div>

        </form>

    </div>

</div>

@endsection