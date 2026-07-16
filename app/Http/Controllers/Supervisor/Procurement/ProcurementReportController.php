<?php

namespace App\Http\Controllers\Supervisor\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPlanItem;
use Illuminate\Http\Request;

class ProcurementReportController extends Controller
{
    public function budgetMonitoring(Request $request)
    {
        return view(
            'supervisor.procurement.budget-monitoring.index',
            $this->budgetMonitoringData($this->resolveYear($request))
        );
    }

    public function budgetMonitoringPrint(Request $request)
    {
        return view(
            'supervisor.procurement.budget-monitoring.print',
            $this->budgetMonitoringData($this->resolveYear($request))
        );
    }

    public function purchaseForecast(Request $request)
    {
        return view(
            'supervisor.procurement.purchase-forecast.index',
            $this->purchaseForecastData($this->resolveYear($request))
        );
    }

    public function purchaseForecastPrint(Request $request)
    {
        return view(
            'supervisor.procurement.purchase-forecast.print',
            $this->purchaseForecastData($this->resolveYear($request))
        );
    }

    public function calendar(Request $request)
    {
        return view(
            'supervisor.procurement.calendar.index',
            $this->calendarData($this->resolveYear($request))
        );
    }

    public function calendarPrint(Request $request)
    {
        return view(
            'supervisor.procurement.calendar.print',
            $this->calendarData($this->resolveYear($request))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Data Helpers
    |--------------------------------------------------------------------------
    */

    private function resolveYear(Request $request): int
    {
        return (int) $request->input('year', now()->year);
    }

    private function budgetMonitoringData(int $year): array
    {
        $plans = ProcurementPlan::with(['department', 'items'])
            ->where('year', $year)
            ->get();

        $totalAllocated = $plans->sum('allocated_budget');
        $totalApproved = $plans->where('status', 'Approved')->sum('approved_budget');
        $totalPlanned = $plans->sum(fn ($plan) => $plan->total_planned_cost);
        $totalRemaining = $totalAllocated - $totalPlanned;

        $byDepartment = $plans
            ->groupBy(fn ($plan) => $plan->department->department_name ?? 'Unassigned')
            ->map(function ($group) {
                $allocated = $group->sum('allocated_budget');
                $planned = $group->sum(fn ($plan) => $plan->total_planned_cost);
                $utilization = $allocated > 0 ? round(($planned / $allocated) * 100, 2) : 0;

                return [
                    'plans_count' => $group->count(),
                    'allocated' => $allocated,
                    'planned' => $planned,
                    'remaining' => $allocated - $planned,
                    'utilization' => $utilization,
                ];
            })
            ->sortByDesc('allocated');

        return compact(
            'year',
            'plans',
            'totalAllocated',
            'totalApproved',
            'totalPlanned',
            'totalRemaining',
            'byDepartment'
        );
    }

    private function purchaseForecastData(int $year): array
    {
        $items = ProcurementPlanItem::with(['plan.department', 'material'])
            ->whereHas('plan', fn ($query) => $query->where('year', $year))
            ->get();

        $quarterTotals = [];

        foreach (['q1', 'q2', 'q3', 'q4'] as $quarter) {
            $quantity = $items->sum($quarter);
            $cost = $items->sum(fn ($item) => $item->$quarter * $item->estimated_unit_cost);

            $quarterTotals[strtoupper($quarter)] = [
                'quantity' => $quantity,
                'cost' => $cost,
            ];
        }

        $annualTotal = $items->sum('annual_cost');

        $byDepartment = $items
            ->groupBy(fn ($item) => $item->plan->department->department_name ?? 'Unassigned')
            ->map(function ($group) {
                $row = ['total' => $group->sum('annual_cost')];

                foreach (['q1', 'q2', 'q3', 'q4'] as $quarter) {
                    $row[$quarter] = $group->sum(fn ($item) => $item->$quarter * $item->estimated_unit_cost);
                }

                return $row;
            })
            ->sortByDesc('total');

        return compact('year', 'quarterTotals', 'annualTotal', 'byDepartment');
    }

    private function calendarData(int $year): array
    {
        $plans = ProcurementPlan::with('department')
            ->where('year', $year)
            ->get();

        $events = collect();

        foreach ($plans as $plan) {
            $events->push([
                'date' => $plan->created_at,
                'label' => 'Plan Created',
                'plan' => $plan,
                'type' => 'created',
            ]);

            if ($plan->submitted_at) {
                $events->push([
                    'date' => $plan->submitted_at,
                    'label' => 'Submitted for Approval',
                    'plan' => $plan,
                    'type' => 'submitted',
                ]);
            }

            if ($plan->status === 'Approved' && $plan->approved_at) {
                $events->push([
                    'date' => $plan->approved_at,
                    'label' => 'Approved',
                    'plan' => $plan,
                    'type' => 'approved',
                ]);
            }

            if ($plan->status === 'Rejected') {
                $events->push([
                    'date' => $plan->updated_at,
                    'label' => 'Rejected',
                    'plan' => $plan,
                    'type' => 'rejected',
                ]);
            }
        }

        $events = $events->sortByDesc('date')->values();

        return compact('year', 'events');
    }
}
