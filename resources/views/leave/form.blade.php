
<x-app-layout>

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    

<div class="max-w-2xl mx-auto mt-10">

    <!-- CARD -->
    <div class="bg-white shadow-xl rounded-xl p-6">

        <h2 class="text-2xl font-bold mb-6 text-gray-700">
            📝 Leave Request
        </h2>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div id="successAlert"
                class="bg-green-500 text-white p-3 mb-4 rounded shadow transition">
                {{ session('success') }}
            </div>
        @endif

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
        <form method="POST" action="/leave" id="leaveForm">
            @csrf

            <!-- TYPE -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Leave Type</label>
                <select name="type" class="w-full border rounded-lg p-2">
                    <option value="Sick">Sick</option>
                    <option value="Vacation">Vacation</option>
                    <option value="Emergency">Emergency</option>
                </select>
            </div>

            <!-- REASON -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Reason</label>
                <textarea name="reason" class="w-full border rounded-lg p-2"></textarea>
            </div>

            <!-- DATES -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Select Date Range</label>

                <input type="text" id="date_range"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400"
                    placeholder="Select date range">

                <p id="total_days" class="text-sm text-gray-600 mt-2"></p>

                <!-- Hidden fields (VERY IMPORTANT) -->
                <input type="hidden" name="date_from" id="date_from">
                <input type="hidden" name="date_to" id="date_to">
            </div>

            

            <!-- BUTTON -->
            <button id="submitBtn"
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Submit Leave
            </button>

        </form>
    </div>
</div>

<!-- JS ENHANCEMENTS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
flatpickr("#date_range", {
    mode: "range",
    dateFormat: "Y-m-d",
    minDate: "today",

    onChange: function(selectedDates) {

        let totalDaysText = document.getElementById('total_days');

        if (selectedDates.length === 0) {
            totalDaysText.innerText = "";
        }

        if (selectedDates.length === 1) {
            let date = selectedDates[0].toISOString().split('T')[0];

            document.getElementById('date_from').value = date;
            document.getElementById('date_to').value = date;

            totalDaysText.innerText = "Total Days: 1 day";
        }

        if (selectedDates.length === 2) {
            let start = selectedDates[0];
            let end = selectedDates[1];

            document.getElementById('date_from').value =
                start.toISOString().split('T')[0];

            document.getElementById('date_to').value =
                end.toISOString().split('T')[0];

            // 🔥 CALCULATE DAYS
            let diffTime = end - start;
            let days = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

            totalDaysText.innerText = "Total Days: " + days + " day(s)";
        }
    }
});
</script>

</x-app-layout>