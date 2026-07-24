<?php

namespace App\Http\Controllers\Supervisor\Procurement;

use App\Http\Controllers\Controller;
use App\Models\PreAllocation;
use App\Models\ProcurementPlan;
use App\Models\ProgramReceiptExpenditure;
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

        $allocationLines = $pre->allocationLines
            ->groupBy('allotment_class')
            ->map(function ($rows) use ($ppaOrder) {
                return $rows
                    ->groupBy(fn ($row) => $row->uacs_code . '|' . $row->description)
                    ->map(function ($cells) use ($ppaOrder) {
                        $byPpa = $cells->keyBy('ppa');
                        return [
                            'uacs_code' => $cells->first()->uacs_code,
                            'description' => $cells->first()->description,
                            'amounts' => collect($ppaOrder)->mapWithKeys(
                                fn ($ppa) => [$ppa => (float) ($byPpa[$ppa]->amount ?? 0)]
                            ),
                            'total' => $cells->sum('amount'),
                        ];
                    })
                    ->values();
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
            compact('pre', 'allocations', 'allocationLines', 'ppaOrder', 'plans')
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
