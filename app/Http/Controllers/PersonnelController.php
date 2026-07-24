<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\User;
use App\Models\ProcurementPlan;
use App\Models\MaterialRequest;
use App\Models\Material;
use App\Models\JobRequest;
use App\Models\LeaveRequest;
use App\Models\ProjectEstimate;
use App\Models\SportsEquipment;
use App\Models\SportsEquipmentBorrow;
use App\Models\PropertyIssuance;
use App\Models\HealthConsultation;
use App\Models\ClinicMedicine;
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

        // ─────────────────────────────────────────────────────────────
        // SELF-SERVICE — any personnel/supervisor account, matching the
        // sidebar's own self-service section gating exactly.
        // ─────────────────────────────────────────────────────────────

        if (in_array($user->role, ['personnel', 'supervisor'])) {

            $myJobRequestsInProgress = JobRequest::where('user_id', $user->id)
                ->whereNotIn('status', ['completed', 'rejected'])
                ->count();

            $cards[] = [
                'href' => route('job-requests.create'),
                'icon' => '🛠️',
                'title' => 'Job Request',
                'subtitle' => 'Submit a Physical Plant or Utility job request',
                'color' => 'blue',
                'badge' => $myJobRequestsInProgress > 0 ? "🔄 {$myJobRequestsInProgress} in progress" : null,
                'badgeColor' => 'yellow',
            ];

            if ($personnel) {

                $myAssignedJobsPending = JobRequest::whereHas(
                    'assignedPersonnel',
                    fn ($q) => $q->where('personnel.id', $personnel->id)
                )->where('status', 'assigned')->count();

                if ($myAssignedJobsPending > 0) {

                    $cards[] = [
                        'href' => route('job-requests.my-assigned'),
                        'icon' => '🔧',
                        'title' => 'My Assigned Jobs',
                        'subtitle' => 'Job requests assigned to you as work crew',
                        'color' => 'orange',
                        'badge' => "⏳ {$myAssignedJobsPending} awaiting completion",
                        'badgeColor' => 'red',
                    ];
                }
            }

            $myBorrowsPending = $personnel
                ? SportsEquipmentBorrow::where('user_id', $user->id)->where('status', 'pending')->count()
                : 0;

            $cards[] = [
                'href' => route('sports-equipment.my-borrows'),
                'icon' => '🏀',
                'title' => 'My Borrowed Equipment',
                'subtitle' => 'Sports equipment you\'ve requested to borrow',
                'color' => 'orange',
                'badge' => $myBorrowsPending > 0 ? "⏳ {$myBorrowsPending} pending" : null,
                'badgeColor' => 'yellow',
            ];

            $myPropertyCount = $personnel
                ? PropertyIssuance::where('recipient_personnel_id', $personnel->id)->count()
                : 0;

            $cards[] = [
                'href' => route('property-issuances.mine'),
                'icon' => '🧾',
                'title' => 'My Property Accountability',
                'subtitle' => 'ICS/PAR slips for property issued to you',
                'color' => 'purple',
                'badge' => $myPropertyCount > 0 ? "{$myPropertyCount} on record" : null,
                'badgeColor' => 'gray',
            ];

            $cards[] = [
                'href' => route('health-consultations.mine'),
                'icon' => '🩺',
                'title' => 'My Health Consultations',
                'subtitle' => 'Your visit history with Health Services',
                'color' => 'green',
                'badge' => null,
                'badgeColor' => 'gray',
            ];

            $myLeavePending = $personnel
                ? LeaveRequest::where('personnel_id', $personnel->id)->where('status', 'Pending')->count()
                : 0;

            $cards[] = [
                'href' => route('leave.index'),
                'icon' => '📝',
                'title' => 'Apply Leave',
                'subtitle' => 'Submit and track your leave requests',
                'color' => 'yellow',
                'badge' => $myLeavePending > 0 ? "⏳ {$myLeavePending} pending" : null,
                'badgeColor' => 'yellow',
            ];

            // Utility & Maintenance Staff only — same pool-membership check
            // as the sidebar's own $isUtilityStaffMember gate.
            $isUtilityStaffMember = $personnel && Personnel::utilityStaff()->where('id', $personnel->id)->exists();

            if ($isUtilityStaffMember) {

                $cards[] = [
                    'href' => route('utility-schedule.my'),
                    'icon' => '📅',
                    'title' => 'My Schedule',
                    'subtitle' => 'Your upcoming utility duty roster',
                    'color' => 'blue',
                    'badge' => null,
                    'badgeColor' => 'gray',
                ];

                $cards[] = [
                    'href' => route('utility-dtr.my'),
                    'icon' => '🗓️',
                    'title' => 'My DTR',
                    'subtitle' => 'Your Daily Time Record',
                    'color' => 'green',
                    'badge' => null,
                    'badgeColor' => 'gray',
                ];
            }
        }

        // ─────────────────────────────────────────────────────────────
        // MANAGEMENT / APPROVAL — each card gated by the exact same
        // permission the sidebar link itself checks, so a card never
        // shows up promising access the account doesn't actually have.
        // ─────────────────────────────────────────────────────────────

        if ($user->hasPermission('approve-job-requests-physical-plant') || $user->hasPermission('approve-job-requests-utility')) {

            $categories = [];

            if ($user->hasPermission('approve-job-requests-physical-plant')) {
                $categories[] = 'physical_plant';
            }

            if ($user->hasPermission('approve-job-requests-utility')) {
                $categories[] = 'utility';
            }

            $pendingJobRequests = JobRequest::whereIn('category', $categories)->where('status', 'pending')->count();

            $cards[] = [
                'href' => route('job-requests.index'),
                'icon' => '🛠️',
                'title' => 'Job Request Approvals',
                'subtitle' => 'Review and approve submitted job requests',
                'color' => 'orange',
                'badge' => $pendingJobRequests > 0 ? "⏳ {$pendingJobRequests} pending" : null,
                'badgeColor' => 'yellow',
            ];
        }

        if ($user->hasPermission('manage-utility-schedule')) {

            $cards[] = [
                'href' => route('utility-schedule.index'),
                'icon' => '📅',
                'title' => 'Utility Scheduling',
                'subtitle' => 'Build the weekly utility duty roster',
                'color' => 'blue',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('manage-project-estimates')) {

            $ongoingEstimates = ProjectEstimate::where('status', 'ongoing')->count();

            $cards[] = [
                'href' => route('project-estimates.index'),
                'icon' => '🧾',
                'title' => 'Project Estimates',
                'subtitle' => 'Prepare repair/project cost estimates',
                'color' => 'purple',
                'badge' => $ongoingEstimates > 0 ? "🔄 {$ongoingEstimates} ongoing" : null,
                'badgeColor' => 'yellow',
            ];
        }

        if ($user->hasPermission('manage-building-inspections')) {

            $cards[] = [
                'href' => route('building-inspections.index'),
                'icon' => '🏢',
                'title' => 'Building Inspections',
                'subtitle' => 'Conduct facility inspection checklists',
                'color' => 'orange',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('approve-utility-leave')) {

            $pendingUtilityLeave = LeaveRequest::whereIn('personnel_id', Personnel::utilityStaff()->pluck('id'))
                ->where('status', 'Pending')
                ->count();

            $cards[] = [
                'href' => route('utility-leave.index'),
                'icon' => '📄',
                'title' => 'Utility Leave Requests',
                'subtitle' => "Approve utility staff's leave requests",
                'color' => 'yellow',
                'badge' => $pendingUtilityLeave > 0 ? "⏳ {$pendingUtilityLeave} pending" : null,
                'badgeColor' => 'red',
            ];
        }

        if ($user->hasPermission('approve-leave-requests')) {

            $pendingLeave = LeaveRequest::where('status', 'Pending')->count();

            $cards[] = [
                'href' => route('leave.requests'),
                'icon' => '📄',
                'title' => 'Leave Requests',
                'subtitle' => 'Approve employee leave requests',
                'color' => 'yellow',
                'badge' => $pendingLeave > 0 ? "⏳ {$pendingLeave} pending" : null,
                'badgeColor' => 'red',
            ];
        }

        if ($user->hasPermission('approve-dtr')) {

            $cards[] = [
                'href' => route('utility-dtr.hr.pending'),
                'icon' => '🏁',
                'title' => 'DTR Approvals',
                'subtitle' => 'Review submitted Daily Time Records',
                'color' => 'green',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('approve-users')) {

            $pendingUsers = User::where('status', 'pending')->count();

            $cards[] = [
                'href' => route('admin.users.pending'),
                'icon' => '👥',
                'title' => 'User Approval',
                'subtitle' => 'Approve newly registered accounts',
                'color' => 'red',
                'badge' => $pendingUsers > 0 ? "⏳ {$pendingUsers} pending" : null,
                'badgeColor' => 'red',
            ];
        }

        if ($user->hasPermission('manage-energy-reports')) {

            $cards[] = [
                'href' => route('energy-reports.index'),
                'icon' => '💡',
                'title' => 'Energy Conservation Report',
                'subtitle' => 'Monthly DOE energy conservation report',
                'color' => 'yellow',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('manage-water-bills')) {

            $cards[] = [
                'href' => route('water-bills.index'),
                'icon' => '🚰',
                'title' => 'Water Bill Report',
                'subtitle' => 'Track monthly water consumption/billing',
                'color' => 'blue',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('manage-property-inventory')) {

            $cards[] = [
                'href' => route('property-inventory.index'),
                'icon' => '🏠',
                'title' => 'Room Inventory of Property',
                'subtitle' => 'Fixed/durable property tracked per room',
                'color' => 'purple',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('manage-property-issuance')) {

            $cards[] = [
                'href' => route('property-issuances.index'),
                'icon' => '🧾',
                'title' => 'Property Issuances (ICS/PAR)',
                'subtitle' => 'Generate ICS/PAR slips for endorsed property',
                'color' => 'purple',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('manage-sports-equipment-inventory')) {

            $lowSportsEquipment = SportsEquipment::get()->filter(fn ($e) => $e->availableQuantity() <= 0)->count();

            $cards[] = [
                'href' => route('sports-equipment.index'),
                'icon' => '🏀',
                'title' => 'Sports Equipment Inventory',
                'subtitle' => 'Manage the borrowable equipment catalog',
                'color' => 'orange',
                'badge' => $lowSportsEquipment > 0 ? "❌ {$lowSportsEquipment} fully borrowed" : null,
                'badgeColor' => 'red',
            ];
        }

        if ($user->hasPermission('approve-sports-equipment-borrows')) {

            $pendingBorrows = SportsEquipmentBorrow::where('status', 'pending')->count();

            $cards[] = [
                'href' => route('sports-equipment.borrows.index'),
                'icon' => '🔄',
                'title' => 'Sports Equipment Borrow Requests',
                'subtitle' => 'Approve, reject, and log equipment returns',
                'color' => 'orange',
                'badge' => $pendingBorrows > 0 ? "⏳ {$pendingBorrows} pending" : null,
                'badgeColor' => 'yellow',
            ];
        }

        if ($user->hasPermission('manage-health-consultations')) {

            $todayConsultations = HealthConsultation::whereDate('consultation_date', now()->toDateString())->count();

            $cards[] = [
                'href' => route('health-consultations.index'),
                'icon' => '🩺',
                'title' => 'Health Consultations',
                'subtitle' => 'Record and manage clinic visit consultations',
                'color' => 'green',
                'badge' => $todayConsultations > 0 ? "📋 {$todayConsultations} today" : null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('manage-clinic-medicines')) {

            $medicines = ClinicMedicine::all();

            $medicineAlerts = $medicines->filter(fn ($m) => $m->isOutOfStock() || $m->isExpired())->count();

            $cards[] = [
                'href' => route('clinic-medicines.index'),
                'icon' => '💊',
                'title' => 'Clinic Medicine Inventory',
                'subtitle' => 'Stock of clinic medicines and supplies',
                'color' => 'red',
                'badge' => $medicineAlerts > 0 ? "⚠ {$medicineAlerts} out of stock/expired" : null,
                'badgeColor' => 'red',
            ];
        }

        if ($user->hasPermission('manage-admission-applicants')) {

            $cards[] = [
                'href' => route('admission-years.index'),
                'icon' => '🎓',
                'title' => 'Admission Testing',
                'subtitle' => 'Roster, exam results, rankings, final admissions',
                'color' => 'blue',
                'badge' => null,
                'badgeColor' => 'gray',
            ];
        }

        if ($user->hasPermission('view-employees')) {

            $cards[] = [
                'href' => route('employees.index'),
                'icon' => '👥',
                'title' => 'Employee Master',
                'subtitle' => 'Manage employee profiles and records',
                'color' => 'blue',
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