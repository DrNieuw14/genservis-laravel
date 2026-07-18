<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\User;
use App\Models\ProcurementPlan;
use App\Models\MaterialRequest;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PersonnelController extends Controller
{
    public function create()
    {
        // Check if personnel profile already exists
        $existing = Personnel::where('user_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('personnel.dashboard')
                ->with('info', 'Your profile already exists.');
        }
        return view('personnel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'   => ['required', 'string', 'max:50', 'unique:personnel,employee_id'],
            'fullname'      => ['required', 'string', 'max:100'],
            'position'      => ['nullable', 'string', 'max:100'],
            'department'    => ['nullable', 'string', 'max:100'],
            'assigned_area' => ['nullable', 'string', 'max:100'],
        ]);

        Personnel::create([
            'employee_id'   => $request->employee_id,
            'fullname'      => $request->fullname,
            'position'      => $request->position,
            'department'    => $request->department,
            'assigned_area' => $request->assigned_area,
            'status'        => 'Active',
            'user_id'       => Auth::id(),
        ]);

        return redirect()->route('personnel.dashboard')
            ->with('success', 'Personnel profile created successfully!');
    }

    public function dashboard()
    {
        $personnel = Personnel::where('user_id', Auth::id())->first();

        $user = Auth::user();

        // Quick-access cards are built from what the account can actually DO
        // (real permissions), not a fixed per-role guess — a role like
        // Secretary can hold view-materials without process-material-requests
        // or create-walkin-requests, so those cards must not show for her.
        $cards = [];

        // Department-scoped PPMP (Department Chair) — only when they don't
        // already have full PPMP access, which has its own card below.
        if (!$user->hasPermission('view-ppmp') && $user->hasPermission('manage-own-department-ppmp-items')) {

            $planCount = ProcurementPlan::where(
                'department_id',
                $personnel?->department_id ?? 0
            )->count();

            $cards[] = [
                'href' => route('procurement.plans.index'),
                'icon' => '📄',
                'title' => 'My Department PPMP',
                'subtitle' => "Manage your department's procurement plan",
                'color' => 'purple',
                'badge' => $planCount . ' ' . Str::plural('plan', $planCount) . ' on file',
                'badgeColor' => 'gray',
            ];
        }

        // Full Procurement Planning access (Secretary, Procurement Officer, etc.)
        if ($user->hasPermission('view-ppmp')) {

            $badge = null;

            if ($user->hasPermission('approve-ppmp')) {

                $awaiting = ProcurementPlan::where('status', 'submitted')->count();

                if ($awaiting > 0) {
                    $badge = "⏳ {$awaiting} awaiting approval";
                }
            }

            $cards[] = [
                'href' => route('procurement.dashboard'),
                'icon' => '📄',
                'title' => 'Procurement Planning',
                'subtitle' => 'Review annual procurement plans',
                'color' => 'purple',
                'badge' => $badge,
                'badgeColor' => 'yellow',
            ];
        }

        // Materials Inventory
        if ($user->hasPermission('view-materials')) {

            $outOfStock = Material::where('quantity', '<=', 0)->count();

            $lowStock = Material::whereColumn('quantity', '<=', 'threshold')
                ->where('quantity', '>', 0)
                ->count();

            $badge = null;
            $badgeColor = 'gray';

            if ($outOfStock > 0) {
                $badge = "❌ {$outOfStock} out of stock";
                $badgeColor = 'red';
            } elseif ($lowStock > 0) {
                $badge = "⚠️ {$lowStock} low stock";
                $badgeColor = 'yellow';
            }

            $cards[] = [
                'href' => route('materials.index'),
                'icon' => '📦',
                'title' => 'Materials Inventory',
                'subtitle' => 'View and manage stock levels',
                'color' => 'blue',
                'badge' => $badge,
                'badgeColor' => $badgeColor,
            ];
        }

        // Material Requests — processing queue (Inventory Custodian, etc.)
        if ($user->hasPermission('process-material-requests')) {

            $pending = MaterialRequest::where('status', 'pending')->count();

            $cards[] = [
                'href' => url('/supervisor/material-requests'),
                'icon' => '📋',
                'title' => 'Material Requests',
                'subtitle' => 'Review, approve, and release requests',
                'color' => 'orange',
                'badge' => $pending > 0 ? "⏳ {$pending} pending" : null,
                'badgeColor' => 'yellow',
            ];
        }

        // Walk-In Issuance
        if ($user->hasPermission('create-walkin-requests')) {

            $cards[] = [
                'href' => route('walkin.create'),
                'icon' => '🚶',
                'title' => 'Walk-In Issuance',
                'subtitle' => 'Issue materials directly to a department',
                'color' => 'green',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        // Self-service Material Request submission — only for accounts that
        // don't already manage inventory (they request FROM the stockroom,
        // rather than fulfilling requests), otherwise this just duplicates
        // the processing card above.
        if ($user->role === 'personnel' && !$user->hasPermission('view-materials')) {

            $myRequests = MaterialRequest::where('user_id', $user->id)->get();

            $myPending = $myRequests->where('status', 'pending')->count();

            $cards[] = [
                'href' => url('/material-request'),
                'icon' => '📦',
                'title' => 'Material Request',
                'subtitle' => 'Request materials from the Centralized Stockroom',
                'color' => 'blue',
                'badge' => $myPending > 0 ? "⏳ {$myPending} pending" : null,
                'badgeColor' => 'yellow',
            ];

            $cards[] = [
                'href' => route('material-request.history'),
                'icon' => '📜',
                'title' => 'Request History',
                'subtitle' => 'Track approval status of your requests',
                'color' => 'green',
                'badge' => '✅ ' . $myRequests->where('status', 'approved')->count() . ' approved'
                    . ' · 📦 ' . $myRequests->where('status', 'released')->count() . ' released',
                'badgeColor' => 'gray',
            ];
        }

        // Reports Center
        if ($user->hasPermission('view-reports')) {

            $cards[] = [
                'href' => route('reports.index'),
                'icon' => '📊',
                'title' => 'Reports Center',
                'subtitle' => 'Inventory and procurement reports',
                'color' => 'orange',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        return view(
            'personnel.dashboard',
            compact('personnel', 'cards')
        );
    }
}