@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                💡 Energy Conservation Report — {{ $report->monthLabel() }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $report->campus }}
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('energy-reports.index')" />

            <a href="{{ route('energy-reports.edit', $report->id) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                ✏️ Edit
            </a>

            <a href="{{ route('energy-reports.print', $report->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

            <form method="POST" action="{{ route('energy-reports.destroy', $report->id) }}"
                  onsubmit="return confirm('Delete this report and everything in it? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    🗑 Delete
                </button>
            </form>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    <!-- STATUS -->
    <div class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="flex flex-wrap items-center justify-between gap-4">

            <div>
                <span class="text-sm px-3 py-1 rounded-full font-semibold {{ $report->status === 'submitted' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $report->status === 'submitted' ? '🏁 Submitted' : '📝 Draft' }}
                </span>

                @if($report->status === 'submitted')
                    <span class="text-gray-500 text-sm ml-2">
                        on {{ $report->submitted_at?->format('M d, Y g:i A') }}
                        @if($report->reviewed_by_name)
                            — reviewed by {{ $report->reviewed_by_name }}
                        @endif
                    </span>
                @endif
            </div>

            @if($report->status === 'draft')
                <button type="button" onclick="document.getElementById('submitModal').classList.remove('hidden')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    ✅ Mark as Submitted
                </button>
            @endif

        </div>

    </div>

    <div id="submitModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-3">Mark as Submitted</h3>
            <form method="POST" action="{{ route('energy-reports.submit', $report->id) }}">
                @csrf
                <label class="block mb-2 font-semibold text-sm">Reviewed by (Campus Administrator)</label>
                <input type="text" name="reviewed_by_name" value="{{ $report->reviewed_by_name }}"
                       placeholder="Name of Campus Administrator" class="w-full border rounded-lg p-3">
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="document.getElementById('submitModal').classList.add('hidden')"
                            class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- I. ENERGY CONSUMPTION MONITORING -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-lg">I. Energy Consumption Monitoring</h3>
        <button type="button" onclick="openConsumptionModal()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
            ✏️ Edit
        </button>
    </div>

    <div class="overflow-x-auto border rounded-lg mb-3">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Particulars</th>
                    <th class="p-3 text-center">Previous Month</th>
                    <th class="p-3 text-center">Current Month</th>
                    <th class="p-3 text-center">Difference</th>
                    <th class="p-3 text-center">% Change</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr>
                    <td class="p-3">Electricity Bill (₱)</td>
                    <td class="p-3 text-center">{{ $report->previous_month_bill !== null ? number_format($report->previous_month_bill, 2) : '-' }}</td>
                    <td class="p-3 text-center">{{ $report->current_month_bill !== null ? number_format($report->current_month_bill, 2) : '-' }}</td>
                    <td class="p-3 text-center">{{ $report->billDifference() !== null ? number_format($report->billDifference(), 2) : '-' }}</td>
                    <td class="p-3 text-center">{{ $report->billPercentChange() !== null ? $report->billPercentChange() . '%' : '-' }}</td>
                </tr>
                <tr>
                    <td class="p-3">Electricity Consumption (kWh)</td>
                    <td class="p-3 text-center">{{ $report->previous_month_consumption !== null ? number_format($report->previous_month_consumption, 2) : '-' }}</td>
                    <td class="p-3 text-center">{{ $report->current_month_consumption !== null ? number_format($report->current_month_consumption, 2) : '-' }}</td>
                    <td class="p-3 text-center">{{ $report->consumptionDifference() !== null ? number_format($report->consumptionDifference(), 2) : '-' }}</td>
                    <td class="p-3 text-center">{{ $report->consumptionPercentChange() !== null ? $report->consumptionPercentChange() . '%' : '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="text-gray-600 mb-8"><strong>Remarks/Analysis:</strong> {{ $report->remarks_analysis ?: '-' }}</p>

    <!-- II. MEASURES IMPLEMENTED -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-lg">II. Energy Conservation Measures Implemented</h3>
        <button type="button" onclick="openMeasuresModal()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
            ✏️ Edit
        </button>
    </div>

    <div class="border rounded-lg p-5 bg-gray-50 mb-8">
        <ul class="space-y-1">
            @foreach(\App\Models\EnergyConservationReport::MEASURES as $key => $label)
                <li>
                    {{ in_array($key, $report->measures_implemented ?? []) ? '☑' : '☐' }} {{ $label }}
                </li>
            @endforeach
            <li>
                {{ $report->other_measures ? '☑' : '☐' }} Other Measures: {{ $report->other_measures ?: '-' }}
            </li>
        </ul>
    </div>

    <!-- III. ACTIVITIES CONDUCTED -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-lg">III. Energy Conservation Activities Conducted</h3>
        <button type="button" onclick="openActivityModal('add')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
            ➕ Add Activity
        </button>
    </div>

    <div class="overflow-x-auto border rounded-lg mb-8">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Activity</th>
                    <th class="p-3 text-left">Participants</th>
                    <th class="p-3 text-left">Remarks</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($report->activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $activity->activity_date->format('M d, Y') }}</td>
                        <td class="p-3">{{ $activity->activity }}</td>
                        <td class="p-3">{{ $activity->participants ?? '-' }}</td>
                        <td class="p-3">{{ $activity->remarks ?? '-' }}</td>
                        <td class="p-3 text-center">
                            <div class="flex gap-2 justify-center">
                                <button type="button"
                                    onclick='openActivityModal("edit", {{ $activity->id }}, {{ json_encode($activity->activity_date->format("Y-m-d")) }}, {{ json_encode($activity->activity) }}, {{ json_encode($activity->participants) }}, {{ json_encode($activity->remarks) }})'
                                    class="text-blue-600 hover:underline text-sm">✏️ Edit</button>
                                <form method="POST" action="{{ route('energy-reports.activities.destroy', [$report->id, $activity->id]) }}"
                                      onsubmit="return confirm('Remove this activity?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">🗑 Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No activities recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- IV. ISSUES, CONCERNS, RECOMMENDATIONS -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-lg">IV. Issues, Concerns, and Recommendations</h3>
        <button type="button" onclick="openIssueModal('add')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
            ➕ Add Issue
        </button>
    </div>

    <div class="overflow-x-auto border rounded-lg mb-8">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Issue/Concern</th>
                    <th class="p-3 text-left">Action Taken</th>
                    <th class="p-3 text-left">Recommendation</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($report->issues as $issue)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $issue->issue_concern }}</td>
                        <td class="p-3">{{ $issue->action_taken ?? '-' }}</td>
                        <td class="p-3">{{ $issue->recommendation ?? '-' }}</td>
                        <td class="p-3 text-center">
                            <div class="flex gap-2 justify-center">
                                <button type="button"
                                    onclick='openIssueModal("edit", {{ $issue->id }}, {{ json_encode($issue->issue_concern) }}, {{ json_encode($issue->action_taken) }}, {{ json_encode($issue->recommendation) }})'
                                    class="text-blue-600 hover:underline text-sm">✏️ Edit</button>
                                <form method="POST" action="{{ route('energy-reports.issues.destroy', [$report->id, $issue->id]) }}"
                                      onsubmit="return confirm('Remove this issue?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">🗑 Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">No issues recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- V. SUMMARY -->
    <h3 class="font-bold text-lg mb-3">V. Summary of Accomplishments</h3>
    <p class="text-gray-600 mb-8">{{ $report->summary_of_accomplishments ?: '-' }}</p>

    <!-- VI. ATTACHMENTS -->
    <h3 class="font-bold text-lg mb-3">VI. Attachments</h3>

    <div class="border rounded-lg p-5 bg-gray-50">

        <form method="POST" action="{{ route('energy-reports.attachments.store', $report->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-sm">Type</label>
                    <select name="type" class="border rounded-lg p-3">
                        <option value="electric_bill">🧾 Copy of Monthly Electric Bill</option>
                        <option value="photo">📷 Photo Documentation</option>
                        <option value="other">📎 Other Supporting Document</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block mb-1 font-semibold text-sm">File(s)</label>
                    <input type="file" name="files[]" multiple class="w-full border rounded-lg p-2 bg-white">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                    ⬆️ Upload
                </button>
            </div>
        </form>

        @if($report->attachments->isNotEmpty())
            <div class="mt-6 space-y-2">
                @foreach($report->attachments as $attachment)
                    <div class="flex items-center justify-between border rounded-lg p-3 bg-white">
                        <div>
                            <a href="{{ $attachment->url }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $attachment->typeLabel() }}
                            </a>
                            <span class="text-xs text-gray-500">— {{ $attachment->uploader->fullname ?? $attachment->uploader->name ?? '-' }}</span>
                        </div>
                        <form method="POST" action="{{ route('energy-reports.attachments.destroy', [$report->id, $attachment->id]) }}"
                              onsubmit="return confirm('Remove this file?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">🗑 Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm mt-4">No attachments yet.</p>
        @endif

    </div>

</div>

<!-- ACTIVITY MODAL -->
<div id="activityModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 id="activityModalTitle" class="text-xl font-bold">Add Activity</h2>
            <button type="button" onclick="closeActivityModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>
        <form id="activityForm" method="POST" action="{{ route('energy-reports.activities.store', $report->id) }}">
            @csrf
            <input type="hidden" id="activityFormMethod" name="_method" value="POST">
            <div class="p-6 grid grid-cols-1 gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-sm">Date</label>
                    <input type="date" name="activity_date" id="activityDate" class="w-full border rounded-lg p-3" required>
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Activity</label>
                    <input type="text" name="activity" id="activityTitle" class="w-full border rounded-lg p-3" required>
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Participants</label>
                    <input type="text" name="participants" id="activityParticipants" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Remarks</label>
                    <textarea name="remarks" id="activityRemarks" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>
            </div>
            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeActivityModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">💾 Save</button>
            </div>
        </form>
    </div>
</div>

<!-- ISSUE MODAL -->
<div id="issueModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 id="issueModalTitle" class="text-xl font-bold">Add Issue</h2>
            <button type="button" onclick="closeIssueModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>
        <form id="issueForm" method="POST" action="{{ route('energy-reports.issues.store', $report->id) }}">
            @csrf
            <input type="hidden" id="issueFormMethod" name="_method" value="POST">
            <div class="p-6 grid grid-cols-1 gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-sm">Issue/Concern</label>
                    <textarea name="issue_concern" id="issueConcern" rows="2" class="w-full border rounded-lg p-3" required></textarea>
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Action Taken</label>
                    <textarea name="action_taken" id="issueActionTaken" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Recommendation</label>
                    <textarea name="recommendation" id="issueRecommendation" rows="2" class="w-full border rounded-lg p-3"></textarea>
                </div>
            </div>
            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeIssueModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">💾 Save</button>
            </div>
        </form>
    </div>
</div>

<!-- CONSUMPTION MODAL -->
<div id="consumptionModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 class="text-xl font-bold">Edit Energy Consumption Monitoring</h2>
            <button type="button" onclick="closeConsumptionModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>
        <form method="POST" action="{{ route('energy-reports.consumption.update', $report->id) }}">
            @csrf
            @method('PUT')
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-sm">Previous Month — Electricity Bill (₱)</label>
                    <input type="number" step="0.01" min="0" name="previous_month_bill"
                           value="{{ $report->previous_month_bill }}" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Current Month — Electricity Bill (₱)</label>
                    <input type="number" step="0.01" min="0" name="current_month_bill"
                           value="{{ $report->current_month_bill }}" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Previous Month — Consumption (kWh)</label>
                    <input type="number" step="0.01" min="0" name="previous_month_consumption"
                           value="{{ $report->previous_month_consumption }}" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-sm">Current Month — Consumption (kWh)</label>
                    <input type="number" step="0.01" min="0" name="current_month_consumption"
                           value="{{ $report->current_month_consumption }}" class="w-full border rounded-lg p-3">
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-1 font-semibold text-sm">Remarks/Analysis</label>
                    <textarea name="remarks_analysis" rows="3" class="w-full border rounded-lg p-3">{{ $report->remarks_analysis }}</textarea>
                </div>
            </div>
            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeConsumptionModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">💾 Save</button>
            </div>
        </form>
    </div>
</div>

<!-- MEASURES MODAL -->
<div id="measuresModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 class="text-xl font-bold">Edit Conservation Measures Implemented</h2>
            <button type="button" onclick="closeMeasuresModal()" class="text-gray-500 hover:text-red-600 text-xl">✕</button>
        </div>
        <form method="POST" action="{{ route('energy-reports.measures.update', $report->id) }}">
            @csrf
            @method('PUT')
            <div class="p-6">
                @foreach(\App\Models\EnergyConservationReport::MEASURES as $key => $label)
                    <label class="flex items-center gap-2 mb-2">
                        <input type="checkbox" name="measures_implemented[]" value="{{ $key }}"
                               @checked(in_array($key, $report->measures_implemented ?? []))>
                        {{ $label }}
                    </label>
                @endforeach

                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" id="otherMeasureToggle"
                           @checked(!empty($report->other_measures))
                           onchange="document.getElementById('otherMeasureInput').disabled = !this.checked; if (!this.checked) document.getElementById('otherMeasureInput').value = '';">
                    Other:
                    <input type="text" name="other_measures" id="otherMeasureInput"
                           value="{{ $report->other_measures }}"
                           {{ empty($report->other_measures) ? 'disabled' : '' }}
                           placeholder="Describe other measure" class="flex-1 border rounded-lg px-3 py-1">
                </label>
            </div>
            <div class="border-t px-6 py-4 flex justify-end gap-2">
                <button type="button" onclick="closeMeasuresModal()" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">💾 Save</button>
            </div>
        </form>
    </div>
</div>

<script>

    function openMeasuresModal() {
        document.getElementById('measuresModal').classList.remove('hidden');
    }

    function closeMeasuresModal() {
        document.getElementById('measuresModal').classList.add('hidden');
    }

    function openConsumptionModal() {
        document.getElementById('consumptionModal').classList.remove('hidden');
    }

    function closeConsumptionModal() {
        document.getElementById('consumptionModal').classList.add('hidden');
    }

    function openActivityModal(mode, id, date, activity, participants, remarks) {
        document.getElementById('activityModalTitle').innerText = mode === 'edit' ? 'Edit Activity' : 'Add Activity';
        document.getElementById('activityDate').value = date ?? '';
        document.getElementById('activityTitle').value = activity ?? '';
        document.getElementById('activityParticipants').value = participants ?? '';
        document.getElementById('activityRemarks').value = remarks ?? '';

        const form = document.getElementById('activityForm');
        if (mode === 'edit') {
            form.action = '{{ url('/energy-reports/'.$report->id.'/activities') }}/' + id;
            document.getElementById('activityFormMethod').value = 'PUT';
        } else {
            form.action = '{{ route('energy-reports.activities.store', $report->id) }}';
            document.getElementById('activityFormMethod').value = 'POST';
        }
        document.getElementById('activityModal').classList.remove('hidden');
    }

    function closeActivityModal() {
        document.getElementById('activityModal').classList.add('hidden');
    }

    function openIssueModal(mode, id, concern, actionTaken, recommendation) {
        document.getElementById('issueModalTitle').innerText = mode === 'edit' ? 'Edit Issue' : 'Add Issue';
        document.getElementById('issueConcern').value = concern ?? '';
        document.getElementById('issueActionTaken').value = actionTaken ?? '';
        document.getElementById('issueRecommendation').value = recommendation ?? '';

        const form = document.getElementById('issueForm');
        if (mode === 'edit') {
            form.action = '{{ url('/energy-reports/'.$report->id.'/issues') }}/' + id;
            document.getElementById('issueFormMethod').value = 'PUT';
        } else {
            form.action = '{{ route('energy-reports.issues.store', $report->id) }}';
            document.getElementById('issueFormMethod').value = 'POST';
        }
        document.getElementById('issueModal').classList.remove('hidden');
    }

    function closeIssueModal() {
        document.getElementById('issueModal').classList.add('hidden');
    }

</script>

@endsection
