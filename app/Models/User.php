<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }
}