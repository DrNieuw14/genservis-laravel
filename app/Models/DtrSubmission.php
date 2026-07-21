<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DtrSubmission extends Model
{
    protected $fillable = [
        'personnel_id',
        'month',
        'period_start',
        'period_end',
        'status',
        'employee_verified_at',
        'mark_checked_at',
        'mark_checked_by',
        'hr_approved_at',
        'hr_approved_by',
        'rejected_at',
        'rejected_by',
        'rejected_stage',
        'rejection_reason',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'employee_verified_at' => 'datetime',
        'mark_checked_at' => 'datetime',
        'hr_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function periodLabel(): string
    {
        return $this->period_start->format('M j') . ' – ' . $this->period_end->format('M j, Y');
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function markChecker()
    {
        return $this->belongsTo(User::class, 'mark_checked_by');
    }

    public function hrApprover()
    {
        return $this->belongsTo(User::class, 'hr_approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function statusLabel(): array
    {
        return match ($this->status) {
            'draft' => ['label' => $this->rejected_at ? '❌ Rejected — needs correction' : '📝 Draft — not yet verified', 'class' => $this->rejected_at ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'],
            'employee_verified' => ['label' => '✅ Verified — awaiting GSO check', 'class' => 'bg-blue-100 text-blue-700'],
            'mark_checked' => ['label' => '🔎 Checked — awaiting HR approval', 'class' => 'bg-yellow-100 text-yellow-700'],
            'hr_approved' => ['label' => '🏁 Approved by HR', 'class' => 'bg-green-100 text-green-700'],
            default => ['label' => $this->status, 'class' => 'bg-gray-100 text-gray-600'],
        };
    }
}
