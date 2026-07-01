<?php

namespace App\Http\Controllers\Supervisor\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPlan;

class ProcurementDashboardController extends Controller
{
    public function index()
    {
        $totalPlans = ProcurementPlan::count();

        $draftPlans = ProcurementPlan::where('status', 'Draft')->count();

        $approvedPlans = ProcurementPlan::where('status', 'Approved')->count();

        $submittedPlans = ProcurementPlan::where('status', 'Submitted')->count();

        $allocatedBudget = ProcurementPlan::sum('allocated_budget');

        $approvedBudget = ProcurementPlan::sum('approved_budget');

        return view('supervisor.procurement.dashboard', compact(
            'totalPlans',
            'draftPlans',
            'approvedPlans',
            'submittedPlans',
            'allocatedBudget',
            'approvedBudget'
        ));
    }
}