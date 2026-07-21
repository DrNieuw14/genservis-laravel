<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\UtilitySchedule;
use Illuminate\Http\Request;

class AttendanceKioskController extends Controller
{
    // 📷 Public scan station — no login required, since this runs on a
    // shared device before anyone signs in. Identity comes from possessing
    // the employee's own QR code, the same trust model as a physical badge
    // scanner (matches the trust-based, no-proof-required self check-in
    // already used on My Schedule).
    public function index()
    {
        return view('attendance_kiosk.index');
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'qr_data' => 'required|string',
            'confirm_checkout' => 'nullable|boolean',
            'undo_checkout' => 'nullable|boolean',
        ]);

        // The QR encodes the employee's own profile URL (…/employees/{id}) —
        // reusing it means no separate badge/code needs to be printed. Every
        // action (including confirm/undo) re-checks this, so identity stays
        // tied to actually possessing the QR, not a bare id a client could
        // otherwise forge.
        if (!preg_match('#/employees/(\d+)#', $validated['qr_data'], $matches)) {
            return response()->json([
                'success' => false,
                'message' => 'Unrecognized QR code. Please scan an employee ID QR code.',
            ], 422);
        }

        $personnel = Personnel::find($matches[1]);

        if (!$personnel || !Personnel::utilityStaff()->where('id', $personnel->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This QR code is not registered for Utility attendance.',
            ], 422);
        }

        // Included in every response below so the kiosk can display the
        // person's own photo alongside their name — a quick visual check
        // that the QR being scanned actually belongs to whoever's standing
        // at the kiosk, on top of the trust-based possession check.
        $identity = ['name' => $personnel->fullname, 'photo_url' => $personnel->photo_url];

        $entry = UtilitySchedule::where('personnel_id', $personnel->id)
            ->whereDate('schedule_date', today())
            ->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => $personnel->fullname . ' has no schedule for today.',
            ] + $identity, 422);
        }

        // Undo a check-out just performed by mistake (e.g. an accidental
        // second scan) — offered by the kiosk UI for a few seconds only.
        if ($request->boolean('undo_checkout')) {
            $result = $entry->undoCheckOut();

            return response()->json($result + $identity + ['action' => 'undo_checkout']);
        }

        // Not checked in yet — check in directly, no confirmation needed
        // (low risk; there's nothing to accidentally undo by checking in).
        if (!$entry->time_in) {
            $result = $entry->performCheckIn();

            return response()->json($result + $identity + ['action' => 'check_in']);
        }

        if ($entry->time_out) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked out for this schedule.',
                'action' => 'already_done',
            ] + $identity, 422);
        }

        // Checked in, not yet out — an accidental re-scan shouldn't
        // silently clock someone out, so require an explicit confirm tap
        // before actually checking out.
        if (!$request->boolean('confirm_checkout')) {
            return response()->json([
                'success' => true,
                'action' => 'needs_confirmation',
                'message' => 'You are currently checked in.',
            ] + $identity);
        }

        $result = $entry->performCheckOut();

        return response()->json($result + $identity + ['action' => 'check_out']);
    }
}
