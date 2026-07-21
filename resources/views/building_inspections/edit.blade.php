@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✏️ Edit Inspection
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $inspection->reference_no }}
            </p>
        </div>

        <x-back-button :href="route('building-inspections.show', $inspection->id)" />
    </div>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('building-inspections.update', $inspection->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 font-semibold">Building Name</label>
                <input type="text" name="building_name" value="{{ old('building_name', $inspection->building_name) }}"
                    class="w-full border rounded-lg p-4" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Building In-Charge (optional)</label>
                <input type="text" name="building_in_charge" value="{{ old('building_in_charge', $inspection->building_in_charge) }}"
                    class="w-full border rounded-lg p-4">
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <div>
                <label class="block mb-2 font-semibold">Inspection Date</label>
                <input type="date" name="inspection_date" value="{{ old('inspection_date', $inspection->inspection_date->toDateString()) }}"
                    class="w-full border rounded-lg p-4" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Noted By (optional)</label>
                <input type="text" name="noted_by" value="{{ old('noted_by', $inspection->noted_by) }}"
                    class="w-full border rounded-lg p-4">
            </div>

        </div>

        <div class="mt-8">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                💾 Save Changes
            </button>
        </div>

    </form>

</div>

@endsection
