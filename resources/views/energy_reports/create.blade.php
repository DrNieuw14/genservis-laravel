@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ➕ New Monthly Report
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Pick the reporting month to start. You'll fill in the rest on the next page.
            </p>
        </div>

        <x-back-button :href="route('energy-reports.index')" />
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

    <form method="POST" action="{{ route('energy-reports.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reporting Month</label>
                <input type="text" id="report_month_picker" placeholder="Click to select month"
                       readonly autocomplete="off"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 cursor-pointer bg-white focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="hidden" name="report_month" id="report_month" value="{{ old('report_month', now()->format('Y-m')) }}">

                @if($existingMonths->isNotEmpty())
                    <p class="text-xs text-gray-400 mt-1">
                        Already reported: {{ $existingMonths->map(fn($m) => \Illuminate\Support\Carbon::parse($m . '-01')->format('M Y'))->join(', ') }}
                    </p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Campus</label>
                <input type="text" name="campus" value="{{ old('campus', 'CvSU Carmona Campus') }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow">
                ✔ Create Report
            </button>

            <a href="{{ route('energy-reports.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-xl">
                Cancel
            </a>
        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<script>

    const takenMonths = @json($existingMonths);
    const reportMonthInput = document.getElementById('report_month');

    const monthPicker = flatpickr("#report_month_picker", {

        plugins: [new monthSelectPlugin({
            shorthand: true,
            dateFormat: "Y-m",
            altFormat: "F Y",
        })],

        defaultDate: reportMonthInput.value,

        onChange: function (selectedDates, dateStr) {

            if (takenMonths.includes(dateStr)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Already reported',
                    text: 'A report for this month already exists — pick a different one.',
                    confirmButtonColor: '#2563eb',
                });
                monthPicker.clear();
                reportMonthInput.value = '';
                return;
            }

            reportMonthInput.value = dateStr;

        },

    });

</script>

@endsection
