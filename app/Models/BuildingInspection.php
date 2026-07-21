<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingInspection extends Model
{
    /**
     * The 6 fixed categories from PPLS-QF-03, each with its exact standard
     * observation questions. 'polarity' matters for scoring: for every
     * category except Fire Safety, checking a box means a problem was
     * observed ('issue'). Fire Safety is inverted for most of its items —
     * "Functional fire alarm?" being checked is a GOOD sign ('good'), not
     * an issue — only "Presence of fire hazards?" follows the usual
     * issue polarity. Get this wrong and the condition score actively
     * misleads Mark about which buildings need attention.
     */
    const CATEGORIES = [
        'site_surroundings' => [
            'label' => 'Site / Immediate Surrounding',
            'items' => [
                ['text' => 'Grass needs trimming/cutting?', 'polarity' => 'issue'],
                ['text' => 'Plants needs watering?', 'polarity' => 'issue'],
                ['text' => 'Landscaping needs attention/improvement?', 'polarity' => 'issue'],
                ['text' => 'Are there tree branches that need trimming?', 'polarity' => 'issue'],
                ['text' => 'Presence of trash or uncollected garbage?', 'polarity' => 'issue'],
            ],
        ],
        'building_exterior' => [
            'label' => 'Building Exterior',
            'items' => [
                ['text' => 'Needs repainting?', 'polarity' => 'issue'],
                ['text' => 'Presence of cracks or chips in walls?', 'polarity' => 'issue'],
                ['text' => 'Broken windows/glass panes?', 'polarity' => 'issue'],
                ['text' => 'Broken doors, doorknobs or jambs?', 'polarity' => 'issue'],
                ['text' => 'Dilapidated ceiling/eaves?', 'polarity' => 'issue'],
                ['text' => 'Leaking roof?', 'polarity' => 'issue'],
                ['text' => 'Presence of debris in roof gutters?', 'polarity' => 'issue'],
                ['text' => 'Broken roof gutters?', 'polarity' => 'issue'],
                ['text' => 'Broken fascia boards?', 'polarity' => 'issue'],
                ['text' => 'Broken downspout?', 'polarity' => 'issue'],
            ],
        ],
        'building_interior' => [
            'label' => 'Building Interior',
            'items' => [
                ['text' => 'Needs repainting?', 'polarity' => 'issue'],
                ['text' => 'Presence of cracks or chips in walls?', 'polarity' => 'issue'],
                ['text' => 'Broken/chipped/missing floor tiles?', 'polarity' => 'issue'],
                ['text' => 'Dilapidated/sagging ceiling?', 'polarity' => 'issue'],
                ['text' => 'Insect infestation?', 'polarity' => 'issue'],
                ['text' => 'Broken doors, doorknobs or jambs?', 'polarity' => 'issue'],
                ['text' => 'Broken tables, chairs or other fixtures?', 'polarity' => 'issue'],
                ['text' => 'Presence of trash/uncollected garbage?', 'polarity' => 'issue'],
            ],
        ],
        'electrical' => [
            'label' => 'Electrical Equipment',
            'items' => [
                ['text' => 'Presence of broken/non-functional electrical equipment?', 'polarity' => 'issue'],
                ['text' => 'Open electrical switches, convenience outlets, wall sockets?', 'polarity' => 'issue'],
                ['text' => 'Exposed telephone/internet cable?', 'polarity' => 'issue'],
                ['text' => 'Busted lamps/bulbs?', 'polarity' => 'issue'],
                ['text' => 'Octopus connection?', 'polarity' => 'issue'],
                ['text' => 'Dangling electrical wires?', 'polarity' => 'issue'],
            ],
        ],
        'plumbing' => [
            'label' => 'Plumbing',
            'items' => [
                ['text' => 'Leaking faucets, water lines and pipe connections?', 'polarity' => 'issue'],
                ['text' => 'Leaking bathroom/comfort room fixtures?', 'polarity' => 'issue'],
                ['text' => 'Clogged plumbing fixtures/sewer lines?', 'polarity' => 'issue'],
            ],
        ],
        'fire_safety' => [
            'label' => 'Fire Safety',
            'items' => [
                ['text' => 'Presence of fire hazards?', 'polarity' => 'issue'],
                ['text' => 'Accessible/clear-way fire exits?', 'polarity' => 'good'],
                ['text' => 'Functional fire alarm?', 'polarity' => 'good'],
                ['text' => 'Functional/accessible fire extinguisher?', 'polarity' => 'good'],
                ['text' => 'Visible fire safety signage or evacuation plan?', 'polarity' => 'good'],
            ],
        ],
    ];

    protected $fillable = [
        'reference_no',
        'building_name',
        'building_in_charge',
        'inspection_date',
        'inspected_by',
        'noted_by',
        'created_by',
    ];

    protected $casts = [
        'inspection_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(BuildingInspectionItem::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Total issue-polarity observations flagged across every category, out
     * of every issue-polarity observation that exists — 'good'-polarity
     * items (e.g. Fire Safety's "Functional fire alarm?") never count
     * toward this, checked or not.
     */
    public function conditionScore(): array
    {
        $flaggedCount = 0;
        $totalIssueItems = 0;

        foreach ($this->items as $item) {
            $flaggedCount += $item->flaggedIssueCount();
            $totalIssueItems += $item->totalIssueItemCount();
        }

        $rating = match (true) {
            $flaggedCount === 0 => 'good',
            $flaggedCount <= 3 => 'needs_attention',
            default => 'critical',
        };

        return [
            'flagged' => $flaggedCount,
            'total' => $totalIssueItems,
            'rating' => $rating,
            'label' => match ($rating) {
                'good' => '🟢 Good',
                'needs_attention' => '🟡 Needs Attention',
                'critical' => '🔴 Critical',
            },
        ];
    }
}
