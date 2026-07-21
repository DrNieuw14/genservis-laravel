<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmploymentType;
use App\Models\Department;
use App\Models\Position;

class Personnel extends Model
{
     protected $table = 'personnel';

    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'employee_id',

        // New Employee Master fields
        'employment_type_id',
        'department_id',
        'position_id',

        // Existing fields
        'fullname',
        'position',
        'department',
        'assigned_area',
        'status',
        'user_id',
        'is_utility_staff',
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

    // ✅ Job Requests assigned to this person as work crew
    public function assignedJobRequests()
    {
        return $this->belongsToMany(JobRequest::class, 'job_request_personnel');
    }

    // ✅ Utility duty roster entries
    public function utilitySchedules()
    {
        return $this->hasMany(UtilitySchedule::class);
    }

    /**
     * Utility & Maintenance Staff pool — an explicit, HR-edit-proof flag
     * (`is_utility_staff`), toggled by whoever holds manage-utility-schedule
     * (i.e. Mark). Previously derived by matching position_name against
     * "Utility"/"Maintenance"/etc, but that silently broke the moment HR
     * relabeled someone's official designation (e.g. "Utility Worker" ->
     * "Administrative Aide I" during a system role/Employment Status
     * cleanup) even though they still work under Mark for attendance
     * purposes — position/employment type are HR's concern, this flag is
     * Mark's own call and stays put regardless of what HR does elsewhere.
     * Shared by EmployeeController::utilityStaff(),
     * JobRequestController::assignForm(), and UtilityScheduleController so
     * the pool's definition only lives in one place.
     */
    public function scopeUtilityStaff($query)
    {
        return $query->where('is_utility_staff', true);
    }

    /**
     * Employment Type
     */
    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class);
    }

    /**
     * Department (Master Table)
     */
    public function departmentRecord()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Position (Master Table)
     */
    public function positionRecord()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function profile()
    {
        return $this->hasOne(
            EmployeeProfile::class,
            'personnel_id'
        );
    }

    public function getPhotoUrlAttribute()
    {
        return $this->profile?->photo_url;
    }

    // "SURNAME, Given Names" for official documents (e.g. the DTR) that
    // expect that order — fullname is stored as one plain string (no
    // separate surname/given-name columns), so this assumes the last space-
    // separated word is the surname. Holds for every real name in this
    // system so far (e.g. "Jenny Beb F. Espineli" -> "Espineli, Jenny Beb F."),
    // but flag it if a compound surname ever needs splitting differently.
    public function surnameFirstName(): string
    {
        $parts = explode(' ', trim($this->fullname));

        if (count($parts) < 2) {
            return $this->fullname;
        }

        $surname = array_pop($parts);

        return $surname . ', ' . implode(' ', $parts);
    }

    public function contact()
    {
        return $this->hasOne(EmployeeContact::class);
    }

    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class);
    }

    /**
     * Next sequential employee ID for an employment type's prefix
     * (e.g. REG001, REG002, ...). Shared by employee onboarding and
     * any quick-add-employee flow so numbering never diverges.
     */
    public static function generateEmployeeId($employmentTypeId)
    {
        $employmentType = EmploymentType::findOrFail($employmentTypeId);

        $prefix = $employmentType->employee_prefix;

        $lastEmployee = self::where('employee_id', 'like', $prefix . '%')
            ->orderByDesc('employee_id')
            ->first();

        if (!$lastEmployee) {
            return $prefix . '001';
        }

        $lastNumber = (int) substr(
            $lastEmployee->employee_id,
            strlen($prefix)
        );

        return $prefix . str_pad(
            $lastNumber + 1,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

}