<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyIssuance extends Model
{
    // Real COA value brackets — which printed form applies is decided once
    // at creation from the selected items' unit cost, then fixed for good.
    const FORM_TYPES = [
        'ics_5k_below' => 'Inventory Custodian Slip (₱5,000 and Below)',
        'ics_mid' => 'Inventory Custodian Slip (₱5,001 - ₱49,999.99)',
        'par' => 'Property Acknowledgement Receipt (₱50,000 and above)',
    ];

    protected $fillable = [
        'room_id',
        'form_type',
        'slip_no',
        'fund_cluster',
        'po_number',
        'recipient_personnel_id',
        'issued_by',
        'issued_by_name',
        'issued_by_position',
        'issued_at',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'issued_at' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function items()
    {
        return $this->hasMany(PropertyIssuanceItem::class);
    }

    public function photos()
    {
        return $this->hasMany(PropertyIssuancePhoto::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Personnel::class, 'recipient_personnel_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // Prefer the linked system-user issuer; fall back to the freeform
    // name/position captured for historical or external officers who have
    // no User account here (e.g. a past Supply Officer on a backfilled slip).
    public function issuerDisplayName(): string
    {
        return $this->issuer->name ?? $this->issued_by_name ?? '';
    }

    public function issuerDisplayPosition(): string
    {
        return $this->issuer?->personnel?->positionRecord?->position_name
            ?? $this->issued_by_position
            ?? 'Property Custodian';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function totalValue(): float
    {
        return $this->items->sum(fn ($item) => $item->quantity * (float) $item->unit_cost);
    }

    public function formTypeLabel(): string
    {
        return self::FORM_TYPES[$this->form_type] ?? $this->form_type;
    }

    public function isIcs(): bool
    {
        return in_array($this->form_type, ['ics_5k_below', 'ics_mid']);
    }

    public function icsBracketLabel(): string
    {
        return $this->form_type === 'ics_5k_below' ? '5,000 and Below' : '5,001 - 49,999.99';
    }

    // Real COA thresholds: ≤5,000 and 5,001-49,999.99 are both semi-
    // expendable (ICS, split into two brackets), ≥50,000 is capitalized
    // property (PAR).
    public static function determineFormType(float $unitCost): string
    {
        if ($unitCost >= 50000) {
            return 'par';
        }

        if ($unitCost > 5000) {
            return 'ics_mid';
        }

        return 'ics_5k_below';
    }

    // ICS shares one numbering series across both value brackets (matches
    // how the real ICS No. field works on the paper form); PAR has its own
    // separate series.
    public static function generateSlipNo(string $formType): string
    {
        $prefix = $formType === 'par' ? 'PAR' : 'ICS';

        $matchTypes = $prefix === 'PAR' ? ['par'] : ['ics_5k_below', 'ics_mid'];

        $count = static::whereIn('form_type', $matchTypes)
            ->whereYear('created_at', now()->year)
            ->count();

        return $prefix . '-' . now()->year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}
