<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterTracking extends Model
{
    const STATUSES = [
        'logged' => '📥 Logged',
        'for_signature' => '✍️ For Signature (Campus Admin)',
        'signed' => '✅ Signed',
        'released' => '📤 Released',
    ];

    // The status pipeline, in order — advanceStatus() on the controller
    // moves a letter one step forward and stamps the matching date.
    const STATUS_ORDER = ['logged', 'for_signature', 'signed', 'released'];

    protected $fillable = [
        'control_no',
        'direction',
        'subject',
        'from_office',
        'to_office',
        'date_received',
        'forwarded_at',
        'signed_at',
        'released_at',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'date_received' => 'date',
        'forwarded_at' => 'date',
        'signed_at' => 'date',
        'released_at' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function directionLabel(): string
    {
        return $this->direction === 'incoming' ? '📩 Incoming' : '📤 Outgoing';
    }

    // The status this letter moves to next in the pipeline, or null if
    // it's already at the end (Released).
    public function nextStatus(): ?string
    {
        $position = array_search($this->status, self::STATUS_ORDER, true);

        if ($position === false || !isset(self::STATUS_ORDER[$position + 1])) {
            return null;
        }

        return self::STATUS_ORDER[$position + 1];
    }

    // The date matching wherever this letter currently sits in the
    // pipeline — shown next to its status badge.
    public function currentStatusDate(): ?\Illuminate\Support\Carbon
    {
        return match ($this->status) {
            'logged' => $this->created_at,
            'for_signature' => $this->forwarded_at,
            'signed' => $this->signed_at,
            'released' => $this->released_at,
            default => null,
        };
    }
}
