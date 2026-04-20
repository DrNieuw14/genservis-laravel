<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    protected $fillable = [
        'personnel_id',
        'reason',
        'start_date',
        'end_date',
        'status',
        'approved_by',
        'approved_at'
    ];

    // ✅ Link to user through personnel_id
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'personnel_id', 'personnel_id');
    }

    // ✅ Supervisor who approved
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
}