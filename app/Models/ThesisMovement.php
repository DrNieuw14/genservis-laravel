<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisMovement extends Model
{
    protected $fillable = [
        'thesis_advisee_id',
        'direction',
        'chapter_stage',
        'moved_at',
        'remarks',
        'logged_by',
    ];

    protected $casts = [
        'moved_at' => 'date',
    ];

    public function advisee()
    {
        return $this->belongsTo(ThesisAdvisee::class, 'thesis_advisee_id');
    }

    public function loggedBy()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
