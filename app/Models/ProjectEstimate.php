<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEstimate extends Model
{
    const STATUSES = [
        'ongoing' => '🟡 Ongoing',
        'done' => '🟢 Done',
    ];

    protected $fillable = [
        'reference_no',
        'project_name',
        'location',
        'prepared_by',
        'scope_of_work',
        'duration',
        'assumptions',
        'exclusions',
        'status',
        'status_updated_at',
        'status_updated_by',
        'job_request_id',
        'created_by',
    ];

    protected $casts = [
        'status_updated_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(ProjectEstimateItem::class);
    }

    public function photos()
    {
        return $this->hasMany(ProjectEstimatePhoto::class);
    }

    public function preparer()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function statusUpdatedBy()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function materialsTotal(): float
    {
        return (float) $this->items->where('category', 'materials_equipment')->sum(fn ($item) => $item->totalCost());
    }

    public function laborTotal(): float
    {
        return (float) $this->items->where('category', 'labor')->sum(fn ($item) => $item->totalCost());
    }

    public function grandTotal(): float
    {
        return $this->materialsTotal() + $this->laborTotal();
    }
}
