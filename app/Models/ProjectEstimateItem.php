<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEstimateItem extends Model
{
    protected $fillable = [
        'project_estimate_id',
        'description',
        'unit',
        'quantity',
        'unit_cost',
        'category',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
    ];

    public function projectEstimate()
    {
        return $this->belongsTo(ProjectEstimate::class);
    }

    public function totalCost(): float
    {
        return (float) $this->quantity * (float) $this->unit_cost;
    }

    public function categoryLabel(): string
    {
        return $this->category === 'labor' ? 'Labor' : 'Materials / Equipment';
    }
}
