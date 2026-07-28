<?php

namespace App\Http\Controllers\Supervisor\Procurement;

use App\Http\Controllers\Controller;
use App\Models\PreAllocation;
use App\Models\PreAllocationLine;
use App\Models\PreUtilizationEntry;
use App\Models\ProcurementPlan;
use App\Models\ProgramReceiptExpenditure;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramReceiptExpenditureController extends Controller
{
    private const PPAS = ['GASS', 'STO', 'MFO1', 'MFO2', 'MFO3', 'MFO4'];

    private const FUND_SOURCES = ['164', '101'];

    public function index()
    {
        $pres = ProgramReceiptExpenditure::withCount('allocations')
            ->latest('year')
            ->get();

        return view('supervisor.procurement.pre.index', compact('pres'));
    }

    public function create()
    {
        return view('supervisor.procurement.pre.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4|unique:program_receipt_expenditures,year',
            'total_projected_income' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $pre = ProgramReceiptExpenditure::create([
            'year' => $request->year,
            'total_projected_income' => $request->total_projected_income,
            'status' => 'Draft',
            'prepared_by' => Auth::id(),
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('procurement.pre.show', $pre->id)
            ->with('success', 'Program of Receipts and Expenditures created. Add the PPA allocation ceilings below.');
    }

    public function show(string $id)
    {
        $pre = ProgramReceiptExpenditure::with(['allocations', 'allocationLines', 'preparedBy'])
            ->findOrFail($id);

        $allocations = $pre->allocations
            ->groupBy('fund_source')
            ->map(fn ($rows) => $rows->keyBy('ppa'));

        // Allotment Class/Object of Expenditure detail — grouped the same way
        // the source PRE document itself groups it (Personal Services / MOOE /
        // Capital Outlay), each row a UACS line with per-PPA amounts.
        $ppaOrder = ['GASS', 'STO', 'MFO1', 'MFO2', 'MFO3', 'MFO4'];

        $utilizationData = [];

        $allocationLines = $pre->allocationLines
            ->groupBy('allotment_class')
            ->map(function ($rows, $class) use ($ppaOrder, &$utilizationData) {
                return $rows
                    ->groupBy(fn ($row) => $row->uacs_code . '|' . $row->description)
                    ->values()
                    ->map(function ($cells, $rowIndex) use ($ppaOrder, $class, &$utilizationData) {

                        $byPpa = $cells->keyBy('ppa');
                        $uacsCode = $cells->first()->uacs_code;

                        [$utilizedPerPpa, $detail] = $this->utilizationBreakdown($uacsCode, $byPpa, $ppaOrder);

                        $lineIds = collect($ppaOrder)->mapWithKeys(
                            fn ($ppa) => [$ppa => $byPpa[$ppa]->id ?? null]
                        );

                        $rowKey = \Illuminate\Support\Str::slug($class) . '-' . $rowIndex;

                        $utilizationData[$rowKey] = [
                            'uacs_code' => $uacsCode,
                            'description' => $cells->first()->description,
                            'is_personal_services' => $class === 'Personal Services',
                            'line_ids' => $lineIds,
                            'detail' => $detail,
                        ];

                        return [
                            'row_key' => $rowKey,
                            'uacs_code' => $uacsCode,
                            'description' => $cells->first()->description,
                            'is_personal_services' => $class === 'Personal Services',
                            'amounts' => collect($ppaOrder)->mapWithKeys(
                                fn ($ppa) => [$ppa => (float) ($byPpa[$ppa]->amount ?? 0)]
                            ),
                            'total' => $cells->sum('amount'),
                            'total_utilized' => array_sum($utilizedPerPpa),
                        ];
                    });
            });

        // Reconciliation — every campus-wide PPMP for the same year, tagged items only.
        $plans = ProcurementPlan::where('year', $pre->year)
            ->with('department')
            ->get()
            ->map(fn ($plan) => [
                'plan' => $plan,
                'reconciliation' => $plan->reconcileAgainst($pre),
            ])
            ->filter(fn ($row) => $row['reconciliation'] !== []);

        return view(
            'supervisor.procurement.pre.show',
            compact('pre', 'allocations', 'allocationLines', 'ppaOrder', 'plans', 'utilizationData')
        );
    }

    public function edit(string $id)
    {
        $pre = ProgramReceiptExpenditure::findOrFail($id);

        return view('supervisor.procurement.pre.edit', compact('pre'));
    }

    public function update(Request $request, string $id)
    {
        $pre = ProgramReceiptExpenditure::findOrFail($id);

        $request->validate([
            'year' => 'required|digits:4|unique:program_receipt_expenditures,year,' . $pre->id,
            'total_projected_income' => 'required|numeric|min:0',
            'status' => 'required|in:Draft,Approved',
            'remarks' => 'nullable|string',
        ]);

        $pre->update($request->only([
            'year',
            'total_projected_income',
            'status',
            'remarks',
        ]));

        return redirect()
            ->route('procurement.pre.show', $pre->id)
            ->with('success', 'Program of Receipts and Expenditures updated.');
    }

    public function destroy(string $id)
    {
        $pre = ProgramReceiptExpenditure::findOrFail($id);

        $pre->delete();

        return redirect()
            ->route('procurement.pre.index')
            ->with('success', 'Program of Receipts and Expenditures deleted.');
    }

    /**
     * Per-UACS-line utilization. Two sources feed every line now, added
     * together: Approved/Completed Purchase Requests (for whatever was
     * actually procured through the PPMP — mainly goods/equipment/repairs),
     * and manually logged entries (for everything that never goes through a
     * PR at all — salaries, but also plenty of real MOOE lines like
     * Electricity/Security Services/Insurance that are recurring contractual
     * payments, not discrete purchases). Personal Services simply never has
     * PR hits (PPMP items are never classified under a PS UACS code), so in
     * practice it ends up manual-entry-only without needing a special case.
     * Returns both the per-PPA totals AND the flat contributing-record list
     * in one pass (avoids running the same queries twice for the summary
     * number and its drill-down).
     *
     * @return array{0: array<string, float>, 1: array<int, array>}
     */
    private function utilizationBreakdown(string $uacsCode, $byPpa, array $ppaOrder): array
    {
        $perPpa = [];
        $detail = [];

        foreach ($ppaOrder as $ppa) {

            if (! isset($byPpa[$ppa])) {
                $perPpa[$ppa] = 0.0;
                continue;
            }

            $lineTotal = 0.0;

            $entries = $byPpa[$ppa]->utilizationEntries()->with('recordedBy')->get();

            $lineTotal += (float) $entries->sum('amount');

            foreach ($entries as $entry) {
                $detail[] = [
                    'ppa' => $ppa,
                    'source' => 'Manual Entry',
                    'date' => $entry->entry_date->format('M d, Y'),
                    'description' => $entry->note ?: '—',
                    'amount' => (float) $entry->amount,
                    'recorded_by' => $entry->recordedBy->name ?? '—',
                    'entry_id' => $entry->id,
                    'line_id' => $byPpa[$ppa]->id,
                ];
            }

            $items = PurchaseRequestItem::query()
                ->whereHas('purchaseRequest', fn ($q) => $q->whereIn('status', ['Approved', 'Completed']))
                ->whereHas('planItem', fn ($q) => $q->where('ppa', $ppa)->whereHas(
                    'material.classification',
                    fn ($q2) => $q2->where('uacs_code', $uacsCode)
                ))
                ->with(['purchaseRequest', 'planItem'])
                ->get();

            $lineTotal += (float) $items->sum('total_cost');

            foreach ($items as $item) {
                $detail[] = [
                    'ppa' => $ppa,
                    'source' => $item->purchaseRequest->pr_number,
                    'date' => $item->purchaseRequest->pr_date->format('M d, Y'),
                    'description' => $item->planItem->material_name,
                    'amount' => (float) $item->total_cost,
                    'recorded_by' => null,
                    'entry_id' => null,
                    'line_id' => null,
                ];
            }

            $perPpa[$ppa] = $lineTotal;

        }

        return [$perPpa, $detail];
    }

    /**
     * Log a manual Personal Services utilization entry (e.g. a payroll
     * disbursement) against one specific PPA+UACS line.
     */
    public function storeUtilizationEntry(Request $request, string $id, string $lineId)
    {
        $pre = ProgramReceiptExpenditure::findOrFail($id);

        $line = PreAllocationLine::where('pre_id', $pre->id)->findOrFail($lineId);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        PreUtilizationEntry::create([
            'pre_allocation_line_id' => $line->id,
            'amount' => $validated['amount'],
            'entry_date' => $validated['entry_date'],
            'note' => $validated['note'] ?? null,
            'recorded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Utilization entry logged.');
    }

    public function destroyUtilizationEntry(string $id, string $lineId, string $entryId)
    {
        $pre = ProgramReceiptExpenditure::findOrFail($id);

        $line = PreAllocationLine::where('pre_id', $pre->id)->findOrFail($lineId);

        PreUtilizationEntry::where('pre_allocation_line_id', $line->id)
            ->where('id', $entryId)
            ->delete();

        return back()->with('success', 'Utilization entry removed.');
    }

    /**
     * Bulk-save every PPA x Fund allocation ceiling row from the show page's
     * editable table in one submit — the PRE's PPA/fund grid is fixed-shape
     * (6 PPAs x 2 funds), not an arbitrary list, so one save beats per-row CRUD.
     */
    public function updateAllocations(Request $request, string $id)
    {
        $pre = ProgramReceiptExpenditure::findOrFail($id);

        $request->validate([
            'rows' => 'required|array',
            'rows.*.fund_source' => 'required|in:164,101',
            'rows.*.ppa' => 'required|in:' . implode(',', self::PPAS),
            'rows.*.personal_services' => 'nullable|numeric|min:0',
            'rows.*.mooe' => 'nullable|numeric|min:0',
            'rows.*.capital_outlay' => 'nullable|numeric|min:0',
            'rows.*.infrastructure' => 'nullable|numeric|min:0',
        ]);

        foreach ($request->rows as $row) {

            PreAllocation::updateOrCreate(
                [
                    'pre_id' => $pre->id,
                    'fund_source' => $row['fund_source'],
                    'ppa' => $row['ppa'],
                ],
                [
                    'personal_services' => $row['personal_services'] ?? 0,
                    'mooe' => $row['mooe'] ?? 0,
                    'capital_outlay' => $row['capital_outlay'] ?? 0,
                    'infrastructure' => $row['infrastructure'] ?? 0,
                ]
            );

        }

        return redirect()
            ->route('procurement.pre.show', $pre->id)
            ->with('success', 'PPA allocation ceilings saved.');
    }
}
