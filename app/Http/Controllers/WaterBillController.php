<?php

namespace App\Http\Controllers;

use App\Models\WaterBill;
use App\Models\WaterMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaterBillController extends Controller
{
    public function index(Request $request)
    {
        $meterId = $request->query('meter_id');
        $monthFrom = $request->query('month_from');
        $monthTo = $request->query('month_to');

        $baseQuery = WaterBill::query()
            ->when($meterId, fn ($q) => $q->where('water_meter_id', $meterId))
            ->when($monthFrom, fn ($q) => $q->where('report_month', '>=', $monthFrom))
            ->when($monthTo, fn ($q) => $q->where('report_month', '<=', $monthTo));

        $totalWaterBill = (clone $baseQuery)->sum('water_bill');
        $totalEsf = (clone $baseQuery)->sum('esf');
        $totalUsage = (clone $baseQuery)->get()->sum(fn ($b) => $b->usage() ?? 0);
        $overdueCount = (clone $baseQuery)->get()->filter(fn ($b) => $b->isOverdue())->count();

        $bills = (clone $baseQuery)
            ->with('meter')
            ->orderByDesc('report_month')
            ->paginate(15)
            ->withQueryString();

        // Unpaginated, chronological — feeds the trend chart regardless of
        // which page/filter the table is currently on.
        $allBills = WaterBill::orderBy('report_month')->get();

        $chartData = $allBills
            ->groupBy('report_month')
            ->map(fn ($group, $month) => [
                'month' => \Illuminate\Support\Carbon::parse($month . '-01')->format('M Y'),
                'bill' => $group->sum('water_bill'),
                'usage' => $group->sum(fn ($b) => $b->usage() ?? 0),
            ])
            ->values();

        return view('water_bills.index', [
            'bills' => $bills,
            'meters' => WaterMeter::with('bills:id,water_meter_id,report_month')->orderBy('label')->get(),
            'meterId' => $meterId,
            'monthFrom' => $monthFrom,
            'monthTo' => $monthTo,
            'totalWaterBill' => $totalWaterBill,
            'totalEsf' => $totalEsf,
            'totalUsage' => $totalUsage,
            'overdueCount' => $overdueCount,
            'chartData' => $chartData,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateBill($request);
        $validated['created_by'] = Auth::id();

        WaterBill::create($validated);

        return back()->with('success', 'Water bill recorded.');
    }

    public function update(Request $request, WaterBill $bill)
    {
        $bill->update($this->validateBill($request, $bill->id));

        return back()->with('success', 'Water bill updated.');
    }

    public function destroy(WaterBill $bill)
    {
        $bill->delete();

        return back()->with('success', 'Water bill removed.');
    }

    public function print(Request $request)
    {
        $meterId = $request->query('meter_id');
        $monthFrom = $request->query('month_from');
        $monthTo = $request->query('month_to');

        $bills = WaterBill::query()
            ->when($meterId, fn ($q) => $q->where('water_meter_id', $meterId))
            ->when($monthFrom, fn ($q) => $q->where('report_month', '>=', $monthFrom))
            ->when($monthTo, fn ($q) => $q->where('report_month', '<=', $monthTo))
            ->with('meter')
            ->orderBy('report_month')
            ->get();

        return view('water_bills.print', [
            'bills' => $bills,
            'meterId' => $meterId,
            'meter' => $meterId ? WaterMeter::find($meterId) : null,
            'monthFrom' => $monthFrom,
            'monthTo' => $monthTo,
        ]);
    }

    private function validateBill(Request $request, ?int $billId = null): array
    {
        return $request->validate([
            'water_meter_id' => 'required|exists:water_meters,id',
            'report_month' => [
                'required',
                'date_format:Y-m',
                \Illuminate\Validation\Rule::unique('water_bills')
                    ->where('water_meter_id', $request->input('water_meter_id'))
                    ->ignore($billId),
            ],
            'previous_reading' => 'nullable|numeric|min:0',
            'present_reading' => 'nullable|numeric|min:0',
            'water_bill' => 'nullable|numeric|min:0',
            'esf' => 'nullable|numeric|min:0',
            'amount_after_due_date' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'meter_reader_name' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ], [
            'report_month.unique' => 'A bill for this meter and month has already been recorded.',
        ]);
    }
}
