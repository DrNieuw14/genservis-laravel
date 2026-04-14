<x-app-layout>
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
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Date From</label>
                    <input type="date" name="date_from" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Date To</label>
                    <input type="date" name="date_to" class="w-full border rounded-lg p-2">
                </div>
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
<script>
    const form = document.getElementById('leaveForm');
    const btn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {
        btn.innerText = 'Submitting... ⏳';
        btn.disabled = true;
        btn.classList.add('opacity-50');
    });

    // auto-hide success
    setTimeout(() => {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>

</x-app-layout>