@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✎ Edit {{ $pr->pr_number }}
            </h2>
        </div>

        <x-back-button :href="route('procurement.purchase-requests.show', $pr->id)" />

    </div>

    <form method="POST" action="{{ route('procurement.purchase-requests.update', $pr->id) }}" class="max-w-xl space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">PR Date</label>
            <input type="date" name="pr_date" value="{{ old('pr_date', $pr->pr_date->format('Y-m-d')) }}"
                   class="w-full border rounded px-4 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
            <textarea name="purpose" rows="3" class="w-full border rounded px-4 py-2">{{ old('purpose', $pr->purpose) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="3" class="w-full border rounded px-4 py-2">{{ old('remarks', $pr->remarks) }}</textarea>
        </div>

        <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">
            Save Changes
        </button>

    </form>

</div>

@endsection
