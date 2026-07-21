<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingInspectionItem extends Model
{
    protected $fillable = [
        'building_inspection_id',
        'category',
        'flagged_observations',
        'other_observations',
        'remarks',
    ];

    protected $casts = [
        'flagged_observations' => 'array',
    ];

    public function buildingInspection()
    {
        return $this->belongsTo(BuildingInspection::class);
    }

    public function photos()
    {
        return $this->hasMany(BuildingInspectionPhoto::class);
    }

    public function categoryLabel(): string
    {
        return BuildingInspection::CATEGORIES[$this->category]['label'] ?? $this->category;
    }

    public function categoryItems(): array
    {
        return BuildingInspection::CATEGORIES[$this->category]['items'] ?? [];
    }

    public function isFlagged(string $observationText): bool
    {
        return in_array($observationText, $this->flagged_observations ?? [], true);
    }

    public function flaggedIssueCount(): int
    {
        $flagged = collect($this->flagged_observations ?? []);

        return collect($this->categoryItems())
            ->where('polarity', 'issue')
            ->pluck('text')
            ->filter(fn ($text) => $flagged->contains($text))
            ->count();
    }

    public function totalIssueItemCount(): int
    {
        return collect($this->categoryItems())->where('polarity', 'issue')->count();
    }
}
