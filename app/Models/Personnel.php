<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    protected $table = 'personnel';

    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'employee_id',
        'fullname',
        'position',
        'department',
        'assigned_area',
        'status',
        'user_id',
    ];

    protected $attributes = [
        'status' => 'Active',
    ];

    // ✅ User account linked to personnel
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ Walk-in material issuances
    public function walkinRequests()
    {
        return $this->hasMany(WalkinRequest::class);
    }

}