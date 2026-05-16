@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="max-w-2xl mx-auto mt-10">

    <!-- CARD -->
    <div class="bg-white shadow-xl rounded-xl p-6">

        <h2 class="text-2xl font-bold mb-6 text-gray-700">
            📝 Leave Request
        </h2>

        <!-- SUCCESS -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERRORS -->
        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="/leave">
            @csrf

            <!-- REASON -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Reason</label>
                <textarea name="reason" class="w-full border rounded-lg p-2"></textarea>
            </div>

            <!-- DATE RANGE -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Select Date Range</label>

                <input type="text" id="date_range"
                    class="w-full border rounded-lg p-2"
                    placeholder="Select date range">

                <p id="total_days" class="text-sm text-gray-600 mt-2"></p>

                <input type="hidden" name="start_date" id="start_date">
                <input type="hidden" name="end_date" id="end_date">
            </div>

            <!-- BUTTON -->
            <button class="w-full bg-blue-600 text-white py-2 rounded-lg">
                Submit Leave
            </button>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
flatpickr("#date_range", {
    mode: "range",
    dateFormat: "Y-m-d",
    minDate: "today",

    onChange: function(selectedDates) {

        let totalDaysText = document.getElementById('total_days');

        if (selectedDates.length === 1) {
            let date = selectedDates[0].toISOString().split('T')[0];

            document.getElementById('start_date').value = date;
            document.getElementById('end_date').value = date;

            totalDaysText.innerText = "Total Days: 1 day";
        }

        if (selectedDates.length === 2) {
            let start = selectedDates[0];
            let end = selectedDates[1];

            document.getElementById('start_date').value =
                start.toISOString().split('T')[0];

            document.getElementById('end_date').value =
                end.toISOString().split('T')[0];

            let diffTime = end - start;
            let days = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

            totalDaysText.innerText = "Total Days: " + days + " day(s)";
        }
    }
});
</script>

@endsection