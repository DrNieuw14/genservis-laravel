<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyProfile extends Model
{
    protected $fillable = [
        'personnel_id',
        'highest_educational_attainment',
        'consultation_schedule',
        'designation',
        'research',
        'extension',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    /**
     * Number of distinct subjects taught, and total weekly contact hours —
     * always computed live from actual ClassSchedule entries, never stored,
     * matching this app's established "trust the computation" convention
     * (same as PDE totals, attendance overtime, etc.).
     */
    public function numberOfPreparations(): int
    {
        return ClassSchedule::where('personnel_id', $this->personnel_id)
            ->distinct('subject_id')
            ->count('subject_id');
    }

    public function totalContactHoursPerWeek(): float
    {
        $schedules = ClassSchedule::where('personnel_id', $this->personnel_id)->get();

        return round($schedules->sum(
            fn ($s) => (strtotime($s->end_time) - strtotime($s->start_time)) / 3600
        ), 2);
    }
}
