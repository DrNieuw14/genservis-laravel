<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionYear extends Model
{
    protected $fillable = [
        'label',
        'description',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function applicants()
    {
        return $this->hasMany(AdmissionApplicant::class);
    }

    public function examSessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function programRankings()
    {
        return $this->hasMany(ProgramRanking::class);
    }

    public function reapplications()
    {
        return $this->hasMany(Reapplication::class);
    }

    public function finalAdmissions()
    {
        return $this->hasMany(FinalAdmission::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
