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
    ];

    protected $attributes = [
        'status' => 'Active',
    ];

    // ✅ Correct relationship
      public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


}