@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                📝 Apply for Leave
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Submit a leave request for your supervisor's approval.
            </p>
        </div>

        <a href="{{ route('leave.history') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            📄 My Leave Requests
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/leave">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 font-semibold">Leave Type</label>
                <select name="leave_type" id="leave_type" class="w-full border rounded-lg p-4">
                    @foreach(\App\Models\LeaveRequest::LEAVE_TYPES as $type)
                        <option value="{{ $type }}" {{ old('leave_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Date Range</label>

                <input type="text" id="date_range" readonly autocomplete="off"
                    class="w-full border rounded-lg p-4 cursor-pointer bg-white"
                    placeholder="Click to pick your leave dates">

                <p id="total_days" class="text-sm text-gray-500 mt-1"></p>
                <p id="backdate_hint" class="text-xs text-gray-400 mt-1 hidden">
                    This leave type can be filed for recent past dates (up to 30 days back), since it's often documented after the fact.
                </p>

                <input type="hidden" name="start_date" id="start_date">
                <input type="hidden" name="end_date" id="end_date">
            </div>

        </div>

        <div class="mt-6">
            <label class="block mb-2 font-semibold">Reason</label>
            <textarea name="reason" rows="4" placeholder="Briefly explain the reason for your leave."
                class="w-full border rounded-lg p-4">{{ old('reason') }}</textarea>
        </div>

        <div class="mt-8">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow">
                📤 Submit Leave Request
            </button>
        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

    function toISODate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    // Sick/Emergency Leave, and Holiday/Official Business/Wellness Leave/
    // Day Off (typically documented while reviewing a DTR after the fact),
    // allow a lookback window instead of forcing every request to start
    // today or later.
    const BACKDATABLE_TYPES = @json(\App\Models\LeaveRequest::BACKDATABLE_TYPES);
    const BACKDATE_WINDOW_DAYS = 30;

    function minDateFor(leaveType) {
        return BACKDATABLE_TYPES.includes(leaveType)
            ? new Date(new Date().setDate(new Date().getDate() - BACKDATE_WINDOW_DAYS))
            : "today";
    }

    const datePicker = flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "M j, Y",
        minDate: minDateFor(document.getElementById('leave_type').value),

        onChange: function (selectedDates) {

            const totalDaysText = document.getElementById('total_days');

            if (selectedDates.length === 1) {
                const iso = toISODate(selectedDates[0]);
                document.getElementById('start_date').value = iso;
                document.getElementById('end_date').value = iso;
                totalDaysText.innerText = "Total: 1 day";
            }

            if (selectedDates.length === 2) {
                const start = selectedDates[0];
                const end = selectedDates[1];

                document.getElementById('start_date').value = toISODate(start);
                document.getElementById('end_date').value = toISODate(end);

                const days = Math.round((end - start) / 86400000) + 1;
                totalDaysText.innerText = "Total: " + days + " day(s)";
            }
        }
    });

    const leaveTypeSelect = document.getElementById('leave_type');
    const backdateHint = document.getElementById('backdate_hint');

    function updateForLeaveType() {

        const type = leaveTypeSelect.value;

        datePicker.set('minDate', minDateFor(type));
        datePicker.clear();
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        document.getElementById('total_days').innerText = '';

        backdateHint.classList.toggle('hidden', !BACKDATABLE_TYPES.includes(type));
    }

    leaveTypeSelect.addEventListener('change', updateForLeaveType);
    updateForLeaveType();

</script>

@endsection
