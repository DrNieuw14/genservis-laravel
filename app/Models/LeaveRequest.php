<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    protected $fillable = [
        'personnel_id', // ✅ FIXED
        'reason',
        'start_date',
        'end_date',
        'status',
        'approved_by',
        'approved_at'
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
}