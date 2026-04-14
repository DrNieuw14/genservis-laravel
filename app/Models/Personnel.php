<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;


class Personnel extends Model
{
    protected $table = 'personnel';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'fullname',
        'position',
        'department',
        'user_id'
    ];

    public function user()
    {
    return $this->belongsTo(User::class);
    }
}

