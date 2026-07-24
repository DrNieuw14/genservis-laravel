@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                💰 New Program of Receipts and Expenditures
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Start with the year and total projected income. PPA allocation ceilings are added next.
            </p>

        </div>

        <x-back-button :href="route('procurement.pre.index')" />

    </div>

    <form method="POST" action="{{ route('procurement.pre.store') }}" class="max-w-xl space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Planning Year</label>
            <input type="number" name="year" value="{{ old('year', date('Y')) }}"
                   class="w-full border rounded px-4 py-2" required>
            @error('year') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Total Projected Income (all funding sources)</label>
            <input type="number" step="0.01" name="total_projected_income" value="{{ old('total_projected_income') }}"
                   class="w-full border rounded px-4 py-2" required>
            @error('total_projected_income') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks (optional)</label>
            <textarea name="remarks" rows="3" class="w-full border rounded px-4 py-2">{{ old('remarks') }}</textarea>
        </div>

        <button type="submit"
                class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">
            Save and Continue
        </button>

    </form>

</div>

@endsection
