<?php

namespace App\Http\Controllers;

use App\Models\EnergyConservationAttachment;
use App\Models\EnergyConservationReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EnergyConservationReportController extends Controller
{
    public function index()
    {
        $reports = EnergyConservationReport::orderByDesc('report_month')->paginate(12);

        // Unpaginated, chronological — feeds the trend chart and yearly totals,
        // which need every report regardless of which page the table is on.
        $allReports = EnergyConservationReport::orderBy('report_month')->get();

        $chartData = $allReports->map(fn ($r) => [
            'month' => $r->monthLabel(),
            'bill' => $r->current_month_bill,
            'consumption' => $r->current_month_consumption,
        ])->values();

        $yearlyTotals = $allReports
            ->groupBy(fn ($r) => substr($r->report_month, 0, 4))
            ->map(fn ($group, $year) => [
                'year' => $year,
                'total_bill' => $group->sum('current_month_bill'),
                'total_consumption' => $group->sum('current_month_consumption'),
            ])
            ->sortKeysDesc()
            ->values();

        return view('energy_reports.index', compact('reports', 'chartData', 'yearlyTotals'));
    }

    public function create()
    {
        // So the picker can proactively grey out months that already have
        // a report, instead of only catching the duplicate on submit.
        $existingMonths = EnergyConservationReport::pluck('report_month');

        return view('energy_reports.create', compact('existingMonths'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_month' => 'required|date_format:Y-m|unique:energy_conservation_reports,report_month',
            'campus' => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = Auth::id();

        $report = EnergyConservationReport::create($validated);

        // Carry last month's "current month" figures forward as this
        // report's "previous month" figures, so the coordinator only ever
        // has to type each month's numbers once.
        if ($previous = $report->previousMonthReport()) {
            $report->update([
                'previous_month_bill' => $previous->current_month_bill,
                'previous_month_consumption' => $previous->current_month_consumption,
            ]);
        }

        return redirect()
            ->route('energy-reports.show', $report->id)
            ->with('success', 'Report created. Fill in the sections below.');
    }

    public function show(EnergyConservationReport $energyReport)
    {
        $energyReport->load(['activities', 'issues', 'attachments.uploader']);

        return view('energy_reports.show', ['report' => $energyReport]);
    }

    public function edit(EnergyConservationReport $energyReport)
    {
        return view('energy_reports.edit', ['report' => $energyReport]);
    }

    public function update(Request $request, EnergyConservationReport $energyReport)
    {
        $validated = $request->validate([
            'campus' => 'nullable|string|max:255',
            'previous_month_bill' => 'nullable|numeric|min:0',
            'current_month_bill' => 'nullable|numeric|min:0',
            'previous_month_consumption' => 'nullable|numeric|min:0',
            'current_month_consumption' => 'nullable|numeric|min:0',
            'remarks_analysis' => 'nullable|string',
            'measures_implemented' => 'nullable|array',
            'measures_implemented.*' => 'in:' . implode(',', array_keys(EnergyConservationReport::MEASURES)),
            'other_measures' => 'nullable|string|max:255',
            'summary_of_accomplishments' => 'nullable|string',
        ]);

        $energyReport->update($validated);

        return redirect()
            ->route('energy-reports.show', $energyReport->id)
            ->with('success', 'Report updated.');
    }

    public function updateConsumption(Request $request, EnergyConservationReport $energyReport)
    {
        $validated = $request->validate([
            'previous_month_bill' => 'nullable|numeric|min:0',
            'current_month_bill' => 'nullable|numeric|min:0',
            'previous_month_consumption' => 'nullable|numeric|min:0',
            'current_month_consumption' => 'nullable|numeric|min:0',
            'remarks_analysis' => 'nullable|string',
        ]);

        $energyReport->update($validated);

        return back()->with('success', 'Energy consumption figures updated.');
    }

    public function updateMeasures(Request $request, EnergyConservationReport $energyReport)
    {
        $validated = $request->validate([
            'measures_implemented' => 'nullable|array',
            'measures_implemented.*' => 'in:' . implode(',', array_keys(EnergyConservationReport::MEASURES)),
            'other_measures' => 'nullable|string|max:255',
        ]);

        $energyReport->update([
            'measures_implemented' => $validated['measures_implemented'] ?? [],
            'other_measures' => $validated['other_measures'] ?? null,
        ]);

        return back()->with('success', 'Energy conservation measures updated.');
    }

    public function updateSummary(Request $request, EnergyConservationReport $energyReport)
    {
        $validated = $request->validate([
            'summary_of_accomplishments' => 'nullable|string',
        ]);

        $energyReport->update($validated);

        return back()->with('success', 'Summary of accomplishments updated.');
    }

    public function destroy(EnergyConservationReport $energyReport)
    {
        $energyReport->delete();

        return redirect()
            ->route('energy-reports.index')
            ->with('success', 'Report deleted.');
    }

    public function markSubmitted(Request $request, EnergyConservationReport $energyReport)
    {
        $validated = $request->validate([
            'reviewed_by_name' => 'nullable|string|max:255',
        ]);

        $energyReport->update([
            'status' => 'submitted',
            'reviewed_by_name' => $validated['reviewed_by_name'] ?? $energyReport->reviewed_by_name,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Report marked as submitted.');
    }

    public function print(EnergyConservationReport $energyReport)
    {
        $energyReport->load(['activities', 'issues', 'attachments.uploader']);

        return view('energy_reports.print', ['report' => $energyReport]);
    }

    // --- Activities (Energy Conservation Activities Conducted) ---

    public function storeActivity(Request $request, EnergyConservationReport $energyReport)
    {
        $validated = $this->validateActivity($request);

        $energyReport->activities()->create($validated);

        $response = back()->with('success', 'Activity added.');

        // "Save & Add Another" — flash a flag the show page reads to reopen
        // the Add Activity modal automatically, same pattern as Project
        // Estimate's item modal.
        if ($request->input('action') === 'add_another') {
            $response->with('reopen_add_activity', true);
        }

        return $response;
    }

    public function updateActivity(Request $request, EnergyConservationReport $energyReport, $activityId)
    {
        $activity = $energyReport->activities()->findOrFail($activityId);
        $activity->update($this->validateActivity($request));

        return back()->with('success', 'Activity updated.');
    }

    public function destroyActivity(EnergyConservationReport $energyReport, $activityId)
    {
        $energyReport->activities()->findOrFail($activityId)->delete();

        return back()->with('success', 'Activity removed.');
    }

    // --- Issues (Issues, Concerns, and Recommendations) ---

    public function storeIssue(Request $request, EnergyConservationReport $energyReport)
    {
        $validated = $this->validateIssue($request);

        $energyReport->issues()->create($validated);

        $response = back()->with('success', 'Issue added.');

        // "Save & Add Another" — same pattern as storeActivity()/Project
        // Estimate's item modal.
        if ($request->input('action') === 'add_another') {
            $response->with('reopen_add_issue', true);
        }

        return $response;
    }

    public function updateIssue(Request $request, EnergyConservationReport $energyReport, $issueId)
    {
        $issue = $energyReport->issues()->findOrFail($issueId);
        $issue->update($this->validateIssue($request));

        return back()->with('success', 'Issue updated.');
    }

    public function destroyIssue(EnergyConservationReport $energyReport, $issueId)
    {
        $energyReport->issues()->findOrFail($issueId)->delete();

        return back()->with('success', 'Issue removed.');
    }

    // --- Attachments (electric bill, photo documentation, other) ---

    public function uploadAttachment(Request $request, EnergyConservationReport $energyReport)
    {
        $validated = $request->validate([
            'type' => 'required|in:electric_bill,photo,other',
            'files' => 'required|array',
            'files.*' => 'file|max:10240',
        ], [
            'files.required' => 'Please choose at least one file to upload.',
            'files.*.max' => 'Each file must be 10MB or smaller.',
        ]);

        foreach ($request->file('files', []) as $file) {

            $path = $file->store('energy_reports', 'public');

            $energyReport->attachments()->create([
                'type' => $validated['type'],
                'path' => $path,
                'uploaded_by' => Auth::id(),
            ]);
        }

        return back()->with('success', 'File(s) uploaded.');
    }

    public function destroyAttachment(EnergyConservationReport $energyReport, EnergyConservationAttachment $attachment)
    {
        abort_if($attachment->energy_conservation_report_id !== $energyReport->id, 404);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'File removed.');
    }

    private function validateActivity(Request $request): array
    {
        return $request->validate([
            'activity_date' => 'required|date',
            'activity' => 'required|string|max:255',
            'participants' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);
    }

    private function validateIssue(Request $request): array
    {
        return $request->validate([
            'issue_concern' => 'required|string',
            'action_taken' => 'nullable|string',
            'recommendation' => 'nullable|string',
        ]);
    }
}
