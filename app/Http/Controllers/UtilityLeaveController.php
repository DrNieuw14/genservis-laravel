<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Helpers\ActivityLogger;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilityLeaveController extends Controller
{
    // 📄 Leave requests, scoped to the Utility & Maintenance Staff pool —
    // Mark approves leave for his own staff, not every employee org-wide
    // (that stays behind the separate, unrelated general Leave Management
    // page gated to the legacy supervisor account).
    public function index(Request $request)
    {
        $utilityPersonnelIds = Personnel::utilityStaff()->pluck('id');

        $status = $request->status;
        $search = $request->search;

        $query = LeaveRequest::with('personnel')
            ->whereIn('personnel_id', $utilityPersonnelIds);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('personnel', fn ($q) => $q->where('fullname', 'like', "%{$search}%"));
        }

        $leaves = $query->latest()->get();

        return view('utility_leave.index', compact('leaves', 'status', 'search'));
    }

    public function approve($id)
    {
        $leave = $this->findScopedOrFail($id);

        $leave->update([
            'status' => 'Approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        ActivityLogger::log(
            'Leave',
            'Approved Leave',
            'Approved leave request of ' . ($leave->personnel->fullname ?? 'Unknown Personnel')
        );

        $this->notifyOutcome($leave, 'Leave Approved', 'Your leave request has been approved.');

        return back()->with('success', 'Leave request approved.');
    }

    public function reject(Request $request, $id)
    {
        $leave = $this->findScopedOrFail($id);

        // Optional — a rejection is valid with or without a note (e.g.
        // "already covered that day" vs. no explanation given at all).
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $leave->update([
            'status' => 'Rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        ActivityLogger::log(
            'Leave',
            'Rejected Leave',
            'Rejected leave request of ' . ($leave->personnel->fullname ?? 'Unknown Personnel')
                . (!empty($validated['rejection_reason']) ? ' — ' . $validated['rejection_reason'] : '')
        );

        $message = 'Your leave request has been rejected.';

        if (!empty($validated['rejection_reason'])) {
            $message .= ' Reason: ' . $validated['rejection_reason'];
        }

        $this->notifyOutcome($leave, 'Leave Rejected', $message);

        return back()->with('success', 'Leave request rejected.');
    }

    // Defense in depth — a leave id belonging to a non-utility employee
    // must 404 here even if someone guesses the URL directly, not just be
    // hidden from the index listing.
    private function findScopedOrFail($id): LeaveRequest
    {
        $utilityPersonnelIds = Personnel::utilityStaff()->pluck('id');

        return LeaveRequest::whereIn('personnel_id', $utilityPersonnelIds)->findOrFail($id);
    }

    private function notifyOutcome(LeaveRequest $leave, string $title, string $message): void
    {
        $userId = $leave->personnel->user_id ?? null;

        if (!$userId) {
            return;
        }

        $notif = Notification::create([
            'user_id' => $userId,
            'type' => 'leave',
            'title' => $title,
            'message' => $message,
            'url' => route('leave.history', [], false),
            'is_read' => 0,
        ]);

        event(new NewNotificationEvent($notif));
    }

    // 📊 Report — for Mark's own reporting/records, same pattern as every
    // other module here.
    public function report(Request $request)
    {
        return view('utility_leave.report', $this->reportData($request));
    }

    public function reportPrint(Request $request)
    {
        return view('utility_leave.report_print', $this->reportData($request));
    }

    private function reportData(Request $request): array
    {
        $dateFrom = $request->date('date_from');
        $dateTo = $request->date('date_to');

        $utilityPersonnelIds = Personnel::utilityStaff()->pluck('id');

        $leaves = LeaveRequest::with('personnel')
            ->whereIn('personnel_id', $utilityPersonnelIds)
            ->when($dateFrom, fn ($q) => $q->whereDate('start_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('end_date', '<=', $dateTo))
            ->orderByDesc('start_date')
            ->get();

        return [
            'leaves' => $leaves,
            'dateFrom' => $dateFrom?->format('Y-m-d'),
            'dateTo' => $dateTo?->format('Y-m-d'),
            'pendingCount' => $leaves->where('status', 'Pending')->count(),
            'approvedCount' => $leaves->where('status', 'Approved')->count(),
            'rejectedCount' => $leaves->where('status', 'Rejected')->count(),
        ];
    }
}
