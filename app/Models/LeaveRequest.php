<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    protected $fillable = [
        'user_id', // ✅ NEW
        'reason',
        'start_date',
        'end_date',
        'status',
        'approved_by',
        'approved_at'
    ];

    // ✅ Direct link to user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // ✅ Supervisor who approved
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
}