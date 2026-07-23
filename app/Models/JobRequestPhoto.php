<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRequestPhoto extends Model
{
    const TYPES = [
        'request_evidence' => '📷 Evidence Submitted with Request',
        'work_done' => '🔧 Work Done Evidence',
        'official_receipt' => '🧾 Official Receipt',
    ];

    protected $fillable = [
        'job_request_id',
        'type',
        'path',
        'uploaded_by',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
