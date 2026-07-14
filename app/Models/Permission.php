<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'module',
        'name',
        'slug',
        'description',
        'status',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}