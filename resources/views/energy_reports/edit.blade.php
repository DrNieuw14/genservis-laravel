@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ✏ Edit Report — {{ $report->monthLabel() }}
            </h2>
        </div>

        <x-back-button :href="route('energy-reports.show', $report->id)" />
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

    <form method="POST" action="{{ route('energy-reports.update', $report->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Campus</label>
            <input type="text" name="campus" value="{{ old('campus', $report->campus) }}"
                   class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <h3 class="font-bold text-lg mt-8 mb-3">I. Energy Consumption Monitoring</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Previous Month — Electricity Bill (₱)</label>
                <input type="number" step="0.01" min="0" name="previous_month_bill" value="{{ old('previous_month_bill', $report->previous_month_bill) }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Month — Electricity Bill (₱)</label>
                <input type="number" step="0.01" min="0" name="current_month_bill" value="{{ old('current_month_bill', $report->current_month_bill) }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Previous Month — Consumption (kWh)</label>
                <input type="number" step="0.01" min="0" name="previous_month_consumption" value="{{ old('previous_month_consumption', $report->previous_month_consumption) }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Month — Consumption (kWh)</label>
                <input type="number" step="0.01" min="0" name="current_month_consumption" value="{{ old('current_month_consumption', $report->current_month_consumption) }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks/Analysis</label>
                <textarea name="remarks_analysis" rows="3"
                          class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('remarks_analysis', $report->remarks_analysis) }}</textarea>
            </div>

        </div>

        <h3 class="font-bold text-lg mt-8 mb-3">II. Energy Conservation Measures Implemented</h3>

        <div class="border rounded-lg p-5 bg-gray-50">
            @php $selectedMeasures = old('measures_implemented', $report->measures_implemented ?? []); @endphp
            @foreach(\App\Models\EnergyConservationReport::MEASURES as $key => $label)
                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" name="measures_implemented[]" value="{{ $key }}" @checked(in_array($key, $selectedMeasures))>
                    {{ $label }}
                </label>
            @endforeach

            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Other Measures</label>
                <input type="text" name="other_measures" value="{{ old('other_measures', $report->other_measures) }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>
        </div>

        <h3 class="font-bold text-lg mt-8 mb-3">V. Summary of Accomplishments</h3>

        <textarea name="summary_of_accomplishments" rows="4"
                  class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('summary_of_accomplishments', $report->summary_of_accomplishments) }}</textarea>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow">
                ✔ Save Changes
            </button>

            <a href="{{ route('energy-reports.show', $report->id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-xl">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
