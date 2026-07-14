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

    public function contact()
    {
        return $this->hasOne(EmployeeContact::class);
    }

    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class);
    }

}