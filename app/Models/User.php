<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;



class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'birthdate',
        'birth_month',
        'age',
        'role',
        'role_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'age' => 'integer',
    ];

    /**
     * Legacy role check
     */
    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * RBAC System Role
     */
    public function systemRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Employee Record
     */
    public function personnel(): HasOne
    {
        return $this->hasOne(Personnel::class, 'user_id');
    }

    /**
     * Walk-in Issuance
     */
    public function issuedWalkinRequests()
    {
        return $this->hasMany(
            WalkinRequest::class,
            'issued_by'
        );
    }

    public function hasPermission(string $permission): bool
    {
        if (!$this->systemRole) {
            return false;
        }

        return $this->systemRole
            ->permissions()
            ->where('slug', $permission)
            ->where('status', true)
            ->exists();
    }
}