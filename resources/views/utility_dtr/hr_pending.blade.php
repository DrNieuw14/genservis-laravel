@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            🏁 DTR Approvals
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Utility staff DTRs checked by the General Services Officer, awaiting your final approval.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Employee</th>
                    <th class="p-3 text-left">Period</th>
                    <th class="p-3 text-center">Checked By</th>
                    <th class="p-3 text-center">Checked On</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($submissions as $submission)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $submission->personnel->fullname ?? '-' }}</td>
                        <td class="p-3">{{ $submission->periodLabel() }}</td>
                        <td class="p-3 text-center">{{ $submission->markChecker->fullname ?? $submission->markChecker->name ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $submission->mark_checked_at?->format('M d, Y g:i A') ?? '-' }}</td>
                        <td class="p-3 text-center">

                            <div class="flex gap-2 justify-center">

                                <a href="{{ route('utility-dtr.show', ['personnelId' => $submission->personnel_id, 'start_date' => $submission->period_start->toDateString(), 'end_date' => $submission->period_end->toDateString()]) }}"
                                   class="text-blue-600 hover:underline text-sm">
                                    👁 View
                                </a>

                                <form method="POST" action="{{ route('utility-dtr.approve', ['personnelId' => $submission->personnel_id]) }}"
                                      onsubmit="return confirm('Approve this DTR?')">
                                    @csrf
                                    <input type="hidden" name="start_date" value="{{ $submission->period_start->toDateString() }}">
                                    <input type="hidden" name="end_date" value="{{ $submission->period_end->toDateString() }}">
                                    <button type="submit" class="text-green-600 hover:underline text-sm">
                                        ✅ Approve
                                    </button>
                                </form>

                                <button type="button"
                                        onclick="document.getElementById('rejectModal{{ $submission->id }}').classList.remove('hidden')"
                                        class="text-red-600 hover:underline text-sm">
                                    ❌ Send Back
                                </button>

                            </div>

                            <div id="rejectModal{{ $submission->id }}" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
                                <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 text-left">
                                    <h3 class="text-lg font-bold mb-3">Send Back for Correction</h3>
                                    <form method="POST" action="{{ route('utility-dtr.reject', ['personnelId' => $submission->personnel_id]) }}">
                                        @csrf
                                        <input type="hidden" name="start_date" value="{{ $submission->period_start->toDateString() }}">
                                        <input type="hidden" name="end_date" value="{{ $submission->period_end->toDateString() }}">
                                        <label class="block mb-2 font-semibold text-sm">Reason (optional)</label>
                                        <textarea name="rejection_reason" rows="3" class="w-full border rounded-lg p-3"></textarea>
                                        <div class="flex justify-end gap-2 mt-4">
                                            <button type="button"
                                                    onclick="document.getElementById('rejectModal{{ $submission->id }}').classList.add('hidden')"
                                                    class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">Cancel</button>
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Send Back</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            No DTRs waiting for HR approval right now.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
