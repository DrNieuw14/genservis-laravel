<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisAdvisee extends Model
{
    protected $fillable = [
        'created_by',
        'program',
        'year_level',
        'thesis_title',
    ];

    public function movements()
    {
        return $this->hasMany(ThesisMovement::class)->orderBy('moved_at')->orderBy('id');
    }

    public function members()
    {
        return $this->hasMany(ThesisAdviseeMember::class)->orderBy('id');
    }

    public function adviser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // A group thesis has 2+ members, a solo thesis has 1 — same entity
    // either way, this just joins whoever's listed for display.
    public function getStudentNamesLabelAttribute(): string
    {
        return $this->members->pluck('student_name')->implode(', ');
    }

    public function latestMovement(): ?ThesisMovement
    {
        return $this->movements->last();
    }

    // "With Adviser" when the last movement was IN (student handed it over),
    // "With Student" when OUT (returned for revision), null before any
    // movement has been logged at all.
    public function currentHolder(): ?string
    {
        $last = $this->latestMovement();

        if (! $last) {
            return null;
        }

        return $last->direction === 'in' ? 'With Adviser' : 'With Student';
    }

    public function daysSinceLastMovement(): ?int
    {
        $last = $this->latestMovement();

        return $last ? (int) $last->moved_at->diffInDays(now()) : null;
    }
}
