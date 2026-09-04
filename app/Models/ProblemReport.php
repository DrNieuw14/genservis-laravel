<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemReport extends Model
{
    const STATUSES = [
        'reported' => 'Reported',
        'reviewed' => '🔍 Reviewed',
        'converted' => '🛠️ Converted to Job Request',
        'resolved' => '✅ Resolved',
        'dismissed' => '🚫 Dismissed',
    ];

    protected $fillable = [
        'reference_no',
        'reported_by',
        'location',
        'problem_description',
        'photo_path',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'job_request_id',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    // A report is only ever converted once — after that, Physical Plant
    // and Services should track progress on the linked Job Request itself
    // rather than acting on the report again.
    public function isConverted(): bool
    {
        return $this->status === 'converted';
    }
}
