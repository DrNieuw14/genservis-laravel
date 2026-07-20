<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEstimatePhoto extends Model
{
    protected $fillable = [
        'project_estimate_id',
        'path',
        'type',
        'uploaded_by',
    ];

    public function projectEstimate()
    {
        return $this->belongsTo(ProjectEstimate::class);
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
        return match ($this->type) {
            'receipt' => '🧾 Receipt',
            'work_done' => '🛠️ Work Done',
            default => '📎 Other',
        };
    }
}
