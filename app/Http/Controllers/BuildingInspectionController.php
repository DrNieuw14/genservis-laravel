<?php

namespace App\Http\Controllers;

use App\Models\BuildingInspection;
use App\Models\BuildingInspectionItem;
use App\Models\BuildingInspectionPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BuildingInspectionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $inspections = BuildingInspection::with('items')
            ->when($search, fn ($q) => $q->where('building_name', 'like', "%{$search}%")
                ->orWhere('reference_no', 'like', "%{$search}%"))
            ->latest('inspection_date')
            ->paginate(15)
            ->withQueryString();

        return view('building_inspections.index', compact('inspections', 'search'));
    }

    public function create()
    {
        return view('building_inspections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_name' => 'required|string|max:200',
            'building_in_charge' => 'nullable|string|max:150',
            'inspection_date' => 'required|date',
            'noted_by' => 'nullable|string|max:150',
        ], [
            'building_name.required' => 'Please enter the building name.',
            'inspection_date.required' => 'Please select the inspection date.',
        ]);

        $latestId = BuildingInspection::max('id') + 1;

        $referenceNo = 'BIC-' . date('Y') . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $inspection = BuildingInspection::create($validated + [
            'reference_no' => $referenceNo,
            'inspected_by' => Auth::id(),
            'created_by' => Auth::id(),
        ]);

        // The 6 categories are fixed by the form itself, not user-added —
        // create all of them up front so the show page always has a full
        // set of category cards to fill in.
        foreach (array_keys(BuildingInspection::CATEGORIES) as $category) {
            $inspection->items()->create(['category' => $category]);
        }

        return redirect()
            ->route('building-inspections.show', $inspection->id)
            ->with('success', 'Inspection created. Fill in each category below.');
    }

    public function show($id)
    {
        $inspection = BuildingInspection::with(['items.photos.uploader', 'inspector'])->findOrFail($id);

        return view('building_inspections.show', compact('inspection'));
    }

    public function edit($id)
    {
        $inspection = BuildingInspection::findOrFail($id);

        return view('building_inspections.edit', compact('inspection'));
    }

    public function update(Request $request, $id)
    {
        $inspection = BuildingInspection::findOrFail($id);

        $validated = $request->validate([
            'building_name' => 'required|string|max:200',
            'building_in_charge' => 'nullable|string|max:150',
            'inspection_date' => 'required|date',
            'noted_by' => 'nullable|string|max:150',
        ], [
            'building_name.required' => 'Please enter the building name.',
            'inspection_date.required' => 'Please select the inspection date.',
        ]);

        $inspection->update($validated);

        return redirect()
            ->route('building-inspections.show', $inspection->id)
            ->with('success', 'Inspection updated.');
    }

    public function destroy($id)
    {
        $inspection = BuildingInspection::findOrFail($id);
        $inspection->delete();

        return redirect()
            ->route('building-inspections.index')
            ->with('success', 'Inspection deleted.');
    }

    // 💾 Saves one category's checklist — flagged observations, the free
    // "Others" text, and remarks — one form per category card.
    public function updateItem(Request $request, $id, $itemId)
    {
        $item = BuildingInspectionItem::where('building_inspection_id', $id)->findOrFail($itemId);

        // Rule::in() (array-based), not a comma-joined "in:" string — several
        // of the real observation texts contain commas of their own (e.g.
        // "Leaking faucets, water lines and pipe connections?"), which the
        // comma-separated rule syntax would otherwise split apart.
        $validCategoryTexts = collect($item->categoryItems())->pluck('text')->all();

        $validated = $request->validate([
            'flagged_observations' => 'nullable|array',
            'flagged_observations.*' => Rule::in($validCategoryTexts),
            'other_observations' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $item->update([
            'flagged_observations' => $validated['flagged_observations'] ?? [],
            'other_observations' => $validated['other_observations'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return back()->with('success', $item->categoryLabel() . ' saved.');
    }

    public function uploadPhoto(Request $request, $id, $itemId)
    {
        $item = BuildingInspectionItem::where('building_inspection_id', $id)->findOrFail($itemId);

        $validated = $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|max:5120',
        ], [
            'photos.required' => 'Please choose at least one photo to upload.',
            'photos.*.image' => 'Each file must be a photo (JPG, PNG, etc.).',
            'photos.*.max' => 'Each photo must be 5MB or smaller.',
        ]);

        foreach ($request->file('photos', []) as $photo) {

            $path = $photo->store('building_inspections', 'public');

            BuildingInspectionPhoto::create([
                'building_inspection_item_id' => $item->id,
                'path' => $path,
                'uploaded_by' => Auth::id(),
            ]);
        }

        return back()->with('success', 'Photo(s) uploaded.');
    }

    public function destroyPhoto($id, $itemId, $photoId)
    {
        $photo = BuildingInspectionPhoto::where('building_inspection_item_id', $itemId)->findOrFail($photoId);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Photo removed.');
    }

    public function print($id)
    {
        $inspection = BuildingInspection::with(['items.photos.uploader', 'inspector'])->findOrFail($id);

        return view('building_inspections.print', compact('inspection'));
    }

    // 📊 Report — for Mark's own reporting/records, same pattern as every
    // other report in this module.
    public function report(Request $request)
    {
        return view('building_inspections.report', $this->reportData($request));
    }

    public function reportPrint(Request $request)
    {
        return view('building_inspections.report_print', $this->reportData($request));
    }

    private function reportData(Request $request): array
    {
        $dateFrom = $request->date('date_from');
        $dateTo = $request->date('date_to');

        $inspections = BuildingInspection::with('items')
            ->when($dateFrom, fn ($q) => $q->whereDate('inspection_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('inspection_date', '<=', $dateTo))
            ->orderByDesc('inspection_date')
            ->get();

        $totalInspections = $inspections->count();
        $ratingCounts = $inspections->countBy(fn ($i) => $i->conditionScore()['rating']);

        return [
            'inspections' => $inspections,
            'dateFrom' => $dateFrom?->format('Y-m-d'),
            'dateTo' => $dateTo?->format('Y-m-d'),
            'totalInspections' => $totalInspections,
            'goodCount' => $ratingCounts->get('good', 0),
            'needsAttentionCount' => $ratingCounts->get('needs_attention', 0),
            'criticalCount' => $ratingCounts->get('critical', 0),
        ];
    }
}
