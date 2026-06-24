<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkinRequest extends Model
{
    protected $fillable = [
        'reference_no',
        'requestor_name',
        'personnel_id',
        'department_id',
        'room',
        'purpose',
        'priority',
        'remarks',
        'issued_by',
        'status',
        'transaction_type',
        'issued_at',
        'printed_at',
        'completed_at'
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function issuer()
    {
        return $this->belongsTo(
            User::class,
            'issued_by'
        );
    }

    public function items()
    {
        return $this->hasMany(
            WalkinRequestItem::class
        );
    }
}