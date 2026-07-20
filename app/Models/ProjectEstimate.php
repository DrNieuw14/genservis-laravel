<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEstimate extends Model
{
    protected $fillable = [
        'reference_no',
        'project_name',
        'location',
        'prepared_by',
        'scope_of_work',
        'duration',
        'assumptions',
        'exclusions',
        'job_request_id',
        'created_by',
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
