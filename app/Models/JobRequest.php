<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    protected $fillable = [
        'reference_no',
        'user_id',
        'personnel_id',
        'requesting_party',
        'department_id',
        'office_unit_project',
        'category',
        'nature_of_request',
        'work_summary',
        'work_category',
        'target_date',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'remarks',
        'assigned_by',
        'assigned_at',
        'work_done_by',
        'work_done_at',
        'completed_at',
    ];

    protected $casts = [
        'target_date' => 'date',
        'approved_at' => 'datetime',
        'assigned_at' => 'datetime',
        'work_done_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function workDoneBy()
    {
        return $this->belongsTo(User::class, 'work_done_by');
    }

    public function assignedPersonnel()
    {
        return $this->belongsToMany(Personnel::class, 'job_request_personnel');
    }

    // 📸 Evidence photos uploaded when the assigned worker marks it done
    public function photos()
    {
        return $this->hasMany(JobRequestPhoto::class);
    }

    /**
     * Is this account one of the workers assigned to this job? Drives both
     * the "Mark Work Done" button and the My Assigned Jobs listing.
     */
    public function isAssignedTo(User $user): bool
    {
        if (!$user->personnel) {
            return false;
        }

        return $this->assignedPersonnel->contains('id', $user->personnel->id);
    }

    /**
     * The permission slug an approver must hold to act on this request,
     * based on its category — physical_plant jobs go to Physical Plant and
     * Services, utility jobs (moving, cleaning) go to the General Services
     * Officer.
     */
    public function approvalPermission(): string
    {
        return $this->category === 'physical_plant'
            ? 'approve-job-requests-physical-plant'
            : 'approve-job-requests-utility';
    }

    public function categoryLabel(): string
    {
        return $this->category === 'physical_plant'
            ? 'Physical Plant & Services'
            : 'Utility Personnel';
    }

    /**
     * The approving role's actual title, for the signature line — distinct
     * from categoryLabel(), which names the category of work rather than
     * who signs off on it.
     */
    public function approverRoleLabel(): string
    {
        return $this->category === 'physical_plant'
            ? 'Physical Plant and Services'
            : 'General Services Officer';
    }
}
