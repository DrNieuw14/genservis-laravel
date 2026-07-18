<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;



class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'must_change_password',
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
        'must_change_password' => 'boolean',
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
     * RBAC Primary System Role — every account has exactly one of these,
     * set by whatever created the account (onboarding, Quick Add Employee,
     * Walk-In quick-add, User Approval). This stays the "main" role shown
     * as the account's badge everywhere in the UI.
     */
    public function systemRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Additional roles stacked on top of the primary one (e.g. an account
     * that's both General Services Officer and Property Custodian). An
     * account's real permission set is the union of its primary role plus
     * these — see hasPermission() / allRoles() below.
     */
    public function additionalRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Primary role + additional roles, deduplicated. The single source of
     * truth for "every role this account holds," used by hasPermission()
     * and anywhere the UI needs to list all of an account's roles rather
     * than just its primary one.
     */
    public function allRoles()
    {
        return collect([$this->systemRole])
            ->merge($this->additionalRoles)
            ->filter()
            ->unique('id')
            ->values();
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
        $roleIds = $this->allRoles()->pluck('id');

        if ($roleIds->isEmpty()) {
            return false;
        }

        return Permission::where('slug', $permission)
            ->where('status', true)
            ->whereHas('roles', fn ($q) => $q->whereIn('roles.id', $roleIds))
            ->exists();
    }

    /**
     * Every account — via primary role or any additional role — that holds
     * the given permission. Use this instead of whereHas('systemRole...')
     * whenever you need "who should be notified about X", so accounts that
     * only hold the permission through an additional role aren't missed.
     */
    public function scopeWithPermission($query, string $slug)
    {
        return $query->where(function ($q) use ($slug) {
            $q->whereHas('systemRole.permissions', fn ($p) => $p->where('slug', $slug)->where('status', true))
                ->orWhereHas('additionalRoles.permissions', fn ($p) => $p->where('slug', $slug)->where('status', true));
        });
    }
}