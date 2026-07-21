<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyConservationAttachment extends Model
{
    protected $fillable = [
        'energy_conservation_report_id',
        'type',
        'path',
        'uploaded_by',
    ];

    public function report()
    {
        return $this->belongsTo(EnergyConservationReport::class, 'energy_conservation_report_id');
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
            'electric_bill' => '🧾 Copy of Monthly Electric Bill',
            'photo' => '📷 Photo Documentation',
            default => '📎 Other Supporting Document',
        };
    }

    public function isImage(): bool
    {
        return in_array(strtolower(pathinfo($this->path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }
}
