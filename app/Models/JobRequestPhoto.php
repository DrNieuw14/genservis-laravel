<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRequestPhoto extends Model
{
    protected $fillable = [
        'job_request_id',
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
}
