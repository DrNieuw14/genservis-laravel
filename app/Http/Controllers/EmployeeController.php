<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\EmploymentType;
use App\Models\Personnel;
use App\Models\Position;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $employees = Personnel::with([
            'user.systemRole',
            'user.additionalRoles',
            'employmentType',
            'departmentRecord',
            'positionRecord',
        ])
            ->when($search, function ($query) use ($search) {
                $query->where('employee_id', 'like', "%{$search}%")
                      ->orWhere('fullname', 'like', "%{$search}%");
            })
            ->orderBy('fullname')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact(
            'employees',
            'search'
        ));
    }

    /**
     * Scoped roster for the General Services Officer: the explicit
     * is_utility_staff pool, without granting the broader Employee Master
     * permission.
     */
    public function utilityStaff(Request $request)
    {
        $search = $request->search;

        $employees = Personnel::with([
            'user.systemRole',
            'user.additionalRoles',
            'employmentType',
            'departmentRecord',
            'positionRecord',
        ])
            ->utilityStaff()
            ->when($search, function ($query) use ($search) {
                $query->where(fn ($q) => $q
                    ->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('fullname', 'like', "%{$search}%"));
            })
            ->orderBy('fullname')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', [
            'employees' => $employees,
            'search' => $search,
            'pageTitle' => '🧰 Utility & Maintenance Staff',
            'pageDescription' => 'Utility personnel and electrical/maintenance staff under General Services.',
            'backRoute' => route('personnel.dashboard'),
            'isUtilityStaffPage' => true,
        ]);
    }

    /**
     * Search personnel NOT already in the pool, for the "Add Staff" modal
     * on the Utility & Maintenance Staff page. Deliberately narrow (name +
     * position + department only) rather than reusing the full Employee
     * Master list, since Mark holds manage-utility-schedule but not the
     * broader view-employees permission — this lets him find someone to
     * add without granting him visibility into the whole directory.
     */
    public function searchForUtilityStaff(Request $request)
    {
        $search = $request->get('q');

        $results = Personnel::with(['positionRecord', 'departmentRecord'])
            ->where('is_utility_staff', false)
            ->when($search, fn ($q) => $q->where('fullname', 'like', "%{$search}%"))
            ->orderBy('fullname')
            ->limit(15)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'fullname' => $p->fullname,
                'position' => $p->positionRecord->position_name ?? '-',
                'department' => $p->departmentRecord->department_name ?? '-',
            ]);

        return response()->json($results);
    }

    // Toggling this pool flag is Mark's own call (manage-utility-schedule),
    // independent of whatever HR does to someone's position/department via
    // edit-employees — that's exactly the gap that motivated this flag:
    // HR relabeling a "Utility Worker" to "Administrative Aide I" used to
    // silently drop that person out of Mark's attendance system.
    public function addUtilityStaff(Request $request)
    {
        $validated = $request->validate([
            'personnel_id' => 'required|exists:personnel,id',
        ]);

        $employee = Personnel::findOrFail($validated['personnel_id']);
        $employee->update(['is_utility_staff' => true]);

        ActivityLogger::log(
            'Employee Master',
            'Updated Employee Information',
            "Added {$employee->fullname} to the Utility & Maintenance Staff pool.",
            $employee->user_id
        );

        return back()->with('success', "{$employee->fullname} added to the Utility & Maintenance Staff pool.");
    }

    public function removeUtilityStaff(Personnel $employee)
    {
        $employee->update(['is_utility_staff' => false]);

        ActivityLogger::log(
            'Employee Master',
            'Updated Employee Information',
            "Removed {$employee->fullname} from the Utility & Maintenance Staff pool.",
            $employee->user_id
        );

        return back()->with('success', "{$employee->fullname} removed from the Utility & Maintenance Staff pool.");
    }

    public function show(Personnel $employee)
    {
        $employee->load([

            /*
            |--------------------------------------------------------------------------
            | User & Employment
            |--------------------------------------------------------------------------
            */
            'user.systemRole',
            'user.additionalRoles',
            'employmentType',
            'departmentRecord',
            'positionRecord',

            /*
            |--------------------------------------------------------------------------
            | Employee Information
            |--------------------------------------------------------------------------
            */
            'profile',
            'contact',

            /*
            |--------------------------------------------------------------------------
            | Employee 201 File
            |--------------------------------------------------------------------------
            */
            'educations',

        ]);

        $qrDataUri = $this->qrCodeDataUri(route('employees.show', $employee->id), 90);

        // Change History — reuses the same generic ActivityLog trail shown
        // on the User Access show page, scoped to this person via
        // target_user_id, so an HR edit made from either page ends up in
        // one shared history rather than two separate logs.
        $history = $employee->user_id
            ? ActivityLog::with('user')
                ->where('target_user_id', $employee->user_id)
                ->latest()
                ->take(50)
                ->get()
            : collect();

        // Actions Performed — the flip side, shown on an HR/admin's own
        // profile: edits THEY made to other people's records. Matches the
        // same section on the User Access show page.
        $performedActions = $employee->user_id
            ? ActivityLog::with('targetUser.personnel')
                ->where('user_id', $employee->user_id)
                ->whereNotNull('target_user_id')
                ->latest()
                ->take(50)
                ->get()
            : collect();

        return view('employees.show', compact('employee', 'qrDataUri', 'history', 'performedActions'));
    }

    public function create(Personnel $employee)
    {
        return view('employees.education.create', compact('employee'));
    }

    public function edit(Personnel $employee)
    {
        $employee->load(['employmentType', 'departmentRecord', 'positionRecord', 'profile']);

        return view('employees.edit', [
            'employee' => $employee,
            'employmentTypes' => EmploymentType::where('is_active', 1)->orderBy('name')->get(),
            'departments' => Department::orderBy('department_name')->get(),
            'positions' => $employee->employment_type_id
                ? Position::where('is_active', 1)
                    ->whereHas('employmentTypes', fn ($q) => $q->where('employment_types.id', $employee->employment_type_id))
                    ->orderByRaw('positions.sort_order IS NULL, positions.sort_order')
                    ->orderBy('position_name')
                    ->get()
                : collect(),
        ]);
    }

    // Logs a human-readable before/after trail (Employee Master module) so
    // an HR edit to someone's employment info — position, department,
    // employment status, name — is traceable later, same pattern as the
    // role-assignment log in UserAccessController::update().
    public function update(Request $request, Personnel $employee)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
            'position_id' => 'nullable|exists:positions,id',
            'status' => 'required|in:Active,Inactive',
            'photo' => 'nullable|image|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        $changes = [];

        if ($employee->fullname !== $validated['fullname']) {
            $changes[] = "Name: \"{$employee->fullname}\" \u{2192} \"{$validated['fullname']}\"";
        }

        if ((int) $employee->department_id !== (int) ($validated['department_id'] ?? 0)) {
            $old = $employee->departmentRecord->department_name ?? 'None';
            $new = optional(Department::find($validated['department_id'] ?? null))->department_name ?? 'None';
            $changes[] = "Department: \"{$old}\" \u{2192} \"{$new}\"";
        }

        if ((int) $employee->employment_type_id !== (int) ($validated['employment_type_id'] ?? 0)) {
            $old = $employee->employmentType->name ?? 'None';
            $new = optional(EmploymentType::find($validated['employment_type_id'] ?? null))->name ?? 'None';
            $changes[] = "Employment Status: \"{$old}\" \u{2192} \"{$new}\"";
        }

        if ((int) $employee->position_id !== (int) ($validated['position_id'] ?? 0)) {
            $old = $employee->positionRecord->position_name ?? 'None';
            $new = optional(Position::find($validated['position_id'] ?? null))->position_name ?? 'None';
            $changes[] = "Position: \"{$old}\" \u{2192} \"{$new}\"";
        }

        if ($employee->status !== $validated['status']) {
            $changes[] = "Status: \"{$employee->status}\" \u{2192} \"{$validated['status']}\"";
        }

        $employee->update(collect($validated)->except(['photo', 'remove_photo'])->toArray());

        // Profile photo — same EmployeeProfile.photo column and disk('public')
        // upload pattern as the self-service ProfileController::updatePhoto(),
        // just reachable from the HR-facing edit page too now.
        if ($request->hasFile('photo') || $request->boolean('remove_photo')) {

            $photoPath = optional($employee->profile)->photo;

            if ($request->hasFile('photo')) {

                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }

                $photoPath = $request->file('photo')->store('profile_photos', 'public');
                $changes[] = 'Photo updated';

            } elseif ($request->boolean('remove_photo') && $photoPath) {

                Storage::disk('public')->delete($photoPath);
                $photoPath = null;
                $changes[] = 'Photo removed';

            }

            $employee->profile()->updateOrCreate([], ['photo' => $photoPath]);
        }

        if (!empty($changes)) {
            ActivityLogger::log(
                'Employee Master',
                'Updated Employee Information',
                "Updated {$employee->fullname}: " . implode('; ', $changes),
                $employee->user_id
            );
        }

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Employee information updated successfully.');
    }

    // 🔗 Printable ID card with a QR code linking straight back to this
    // employee's profile — scanning it opens employees.show for this id.
    public function idCard(Personnel $employee)
    {
        $employee->load(['employmentType', 'departmentRecord', 'positionRecord']);

        $qrDataUri = $this->qrCodeDataUri(route('employees.show', $employee->id), 160);

        return view('employees.id_card', compact('employee', 'qrDataUri'));
    }

    // Rendered server-side (not via a client-side JS/CDN library) so the QR
    // always appears — no dependency on a third-party script actually
    // loading, which is exactly what silently failed before (blocked by a
    // browser's shields/ad-blocker or a restricted network) and left the ID
    // card printing with a blank space where the QR should be.
    private function qrCodeDataUri(string $data, int $size): string
    {
        $qrCode = new QrCode(data: $data, size: $size, margin: 4);

        return (new PngWriter())->write($qrCode)->getDataUri();
    }
}