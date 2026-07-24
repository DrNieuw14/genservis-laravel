<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsEquipmentBorrow extends Model
{
    const STATUSES = ['pending', 'approved', 'rejected', 'returned'];

    // Same vocabulary as PropertyItem::CONDITIONS, captured when the
    // custodian logs the physical return.
    const CONDITIONS = ['Good', 'Damaged', 'For Repair', 'Unserviceable'];

    protected $fillable = [
        'borrow_number',
        'sports_equipment_id',
        'user_id',
        'department_id',
        'quantity',
        'room',
        'purpose',
        'expected_return_date',
        'actual_return_date',
        'status',
        'condition_on_return',
        'approved_by',
        'approved_at',
        'returned_confirmed_by',
        'rejection_reason',
        'remarks',
    ];

    protected $casts = [
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(SportsEquipment::class, 'sports_equipment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function returnedConfirmedBy()
    {
        return $this->belongsTo(User::class, 'returned_confirmed_by');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'approved'
            && $this->expected_return_date->isPast();
    }

    public function displayStatus(): string
    {
        if ($this->isOverdue()) {
            return 'overdue';
        }

        return $this->status;
    }

    public function statusBadgeClass(): string
    {
        return match ($this->displayStatus()) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-blue-100 text-blue-700',
            'overdue' => 'bg-red-100 text-red-700',
            'returned' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-gray-200 text-gray-600',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public static function generateBorrowNumber(): string
    {
        $latestId = static::max('id') + 1;

        return 'SEB-' . date('Y') . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);
    }
}
