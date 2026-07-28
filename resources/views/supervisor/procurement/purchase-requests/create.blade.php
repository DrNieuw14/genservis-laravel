@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🧾 New Purchase Request
            </h2>
            <p class="text-gray-500 mt-1 text-lg">Next number: {{ $nextPrNumber }}</p>
        </div>

        <x-back-button :href="route('procurement.purchase-requests.index')" />

    </div>

    <form method="POST" action="{{ route('procurement.purchase-requests.store') }}" class="max-w-xl space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Procurement Plan (PPMP)</label>
            <select name="plan_id" class="w-full border rounded px-4 py-2" required>
                <option value="">-- Select Plan --</option>
                @foreach($plans as $plan)
                <option value="{{ $plan->id }}" @selected(old('plan_id') == $plan->id)>
                    {{ $plan->plan_number }} ({{ $plan->year }})
                </option>
                @endforeach
            </select>
            @error('plan_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">PR Date</label>
            <input type="date" name="pr_date" value="{{ old('pr_date', date('Y-m-d')) }}"
                   class="w-full border rounded px-4 py-2" required>
            @error('pr_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Purpose (optional)</label>
            <textarea name="purpose" rows="3" class="w-full border rounded px-4 py-2">{{ old('purpose') }}</textarea>
        </div>

        <button type="submit"
                class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">
            Save and Add Items
        </button>

    </form>

</div>

@endsection
