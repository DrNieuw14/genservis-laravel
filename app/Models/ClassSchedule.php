<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    protected $fillable = [
        'section_id',
        'subject_id',
        'personnel_id',
        'faculty_name',
        'room',
        'day_of_week',
        'start_time',
        'end_time',
        'created_by',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Prefer the linked Personnel record; fall back to the plain-text
    // transcribed label (bulk-imported entries with no confident match);
    // "TBA" if genuinely neither.
    public function getFacultyLabelAttribute(): string
    {
        return $this->faculty->fullname ?? $this->faculty_name ?? 'TBA';
    }

    public function getTimeRangeLabelAttribute(): string
    {
        return \Illuminate\Support\Carbon::parse($this->start_time)->format('g:iA')
            . ' - '
            . \Illuminate\Support\Carbon::parse($this->end_time)->format('g:iA');
    }

    /**
     * Any existing entry on the same day whose time range overlaps this one,
     * scoped to whichever of section/faculty/room is passed in — a section
     * can't attend two classes at once, a teacher can't teach two rooms at
     * once, a room can't host two classes at once. Excludes $ignoreId so an
     * update doesn't flag itself as its own conflict.
     */
    public static function findConflicts(
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        ?int $sectionId,
        ?int $personnelId,
        ?string $room,
        ?int $ignoreId = null
    ) {
        $overlap = fn ($q) => $q->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        return static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where($overlap)
            ->where(function ($q) use ($sectionId, $personnelId, $room) {
                $q->where('section_id', $sectionId);

                if ($personnelId) {
                    $q->orWhere('personnel_id', $personnelId);
                }

                if ($room) {
                    $q->orWhere('room', $room);
                }
            })
            ->with(['section.program', 'subject', 'faculty'])
            ->get();
    }
}
