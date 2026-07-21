<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    const LEAVE_TYPES = [
        'Vacation Leave', 'Sick Leave', 'Emergency Leave',
        'Holiday', 'Official Business', 'Wellness Leave', 'Day Off',
        'Other',
    ];

    // These, like Sick/Emergency Leave, are typically documented after the
    // fact (e.g. while reviewing a DTR) rather than requested in advance.
    const BACKDATABLE_TYPES = ['Sick Leave', 'Emergency Leave', 'Holiday', 'Official Business', 'Wellness Leave', 'Day Off'];

    protected $fillable = [
        'personnel_id', // ✅ FIXED
        'leave_type',
        'reason',
        'start_date',
        'end_date',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * ✅ Link to Personnel (MAIN FIX)
     */
    public function personnel()
    {
        return $this->belongsTo(\App\Models\Personnel::class, 'personnel_id');
    }

    /**
     * ✅ Access user THROUGH personnel (optional but powerful)
     */
    public function user()
    {
        return $this->hasOneThrough(
            \App\Models\User::class,
            \App\Models\Personnel::class,
            'id',        // Foreign key on personnel table
            'id',        // Foreign key on users table
            'personnel_id', // Local key on leave_requests
            'user_id'    // Local key on personnel
        );
    }

    /**
     * ✅ Supervisor who approved
     */
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    /**
     * Does this person have APPROVED leave covering the given date? Used to
     * cross-check Utility Scheduling (don't silently schedule someone over
     * their own approved leave) and Attendance (a day within approved leave
     * should read as "On Leave", not "No Show"). Status is stored
     * capitalized ('Approved') — matching that exactly here, not 'approved'.
     */
    public static function hasApprovedLeaveOn(int $personnelId, $date): bool
    {
        return static::where('personnel_id', $personnelId)
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();
    }
}