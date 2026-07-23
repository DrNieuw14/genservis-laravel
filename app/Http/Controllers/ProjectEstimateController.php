<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\JobRequest;
use App\Models\Notification;
use App\Models\ProjectEstimate;
use App\Models\ProjectEstimateItem;
use App\Models\ProjectEstimatePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectEstimateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $estimates = ProjectEstimate::with('items')
            ->when($search, fn ($q) => $q->where('project_name', 'like', "%{$search}%")
                ->orWhere('reference_no', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('project_estimates.index', compact('estimates', 'search'));
    }

    public function create()
    {
        $jobRequests = JobRequest::whereIn('status', ['approved', 'assigned', 'work_done', 'completed'])
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'reference_no', 'nature_of_request']);

        return view('project_estimates.create', compact('jobRequests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:200',
            'location' => 'nullable|string|max:200',
            'scope_of_work' => 'nullable|string|max:2000',
            'duration' => 'nullable|string|max:100',
            'assumptions' => 'nullable|string|max:2000',
            'exclusions' => 'nullable|string|max:2000',
            'job_request_id' => 'nullable|exists:job_requests,id',
        ], [
            'project_name.required' => 'Please enter the project name.',
        ]);

        $latestId = ProjectEstimate::max('id') + 1;

        $referenceNo = 'PDE-' . date('Y') . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $estimate = ProjectEstimate::create($validated + [
            'reference_no' => $referenceNo,
            'prepared_by' => Auth::id(),
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('project-estimates.show', $estimate->id)
            ->with('success', 'Project estimate created. Add line items below.');
    }

    public function show($id)
    {
        $estimate = ProjectEstimate::with(['items', 'preparer', 'jobRequest', 'photos.uploader'])->findOrFail($id);

        return view('project_estimates.show', compact('estimate'));
    }

    public function edit($id)
    {
        $estimate = ProjectEstimate::findOrFail($id);

        $jobRequests = JobRequest::whereIn('status', ['approved', 'assigned', 'work_done', 'completed'])
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'reference_no', 'nature_of_request']);

        return view('project_estimates.edit', compact('estimate', 'jobRequests'));
    }

    public function update(Request $request, $id)
    {
        $estimate = ProjectEstimate::findOrFail($id);

        $validated = $request->validate([
            'project_name' => 'required|string|max:200',
            'location' => 'nullable|string|max:200',
            'scope_of_work' => 'nullable|string|max:2000',
            'duration' => 'nullable|string|max:100',
            'assumptions' => 'nullable|string|max:2000',
            'exclusions' => 'nullable|string|max:2000',
            'job_request_id' => 'nullable|exists:job_requests,id',
        ], [
            'project_name.required' => 'Please enter the project name.',
        ]);

        $estimate->update($validated);

        return redirect()
            ->route('project-estimates.show', $estimate->id)
            ->with('success', 'Project estimate updated.');
    }

    public function destroy($id)
    {
        $estimate = ProjectEstimate::findOrFail($id);
        $estimate->delete();

        return redirect()
            ->route('project-estimates.index')
            ->with('success', 'Project estimate deleted.');
    }

    // 🔔 Toggle Ongoing/Done — notifies the linked Job Request's requester
    // (if any) and the estimate's own preparer (if not the one making this
    // change), same "who'd actually want to know" reasoning as every other
    // status-change notification in this app.
    public function updateStatus(Request $request, $id)
    {
        $estimate = ProjectEstimate::with('jobRequest')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:ongoing,done',
        ]);

        $estimate->update([
            'status' => $validated['status'],
            'status_updated_at' => now(),
            'status_updated_by' => Auth::id(),
        ]);

        $user = Auth::user();
        $statusLabel = $estimate->statusLabel();

        $notifyUserIds = collect([$estimate->jobRequest?->user_id, $estimate->prepared_by])
            ->filter()
            ->unique()
            ->reject(fn ($userId) => $userId === Auth::id());

        foreach ($notifyUserIds as $notifyUserId) {

            $notif = Notification::create([
                'user_id' => $notifyUserId,
                'type' => 'project_estimate',
                'title' => 'Project Status Updated',
                'url' => route('project-estimates.show', $estimate->id, false),
                'message' =>
                    ($user->fullname ?? $user->username)
                    . ' marked "' . $estimate->project_name . '" as ' . $statusLabel . '.',
                'is_read' => 0,
            ]);

            event(new NewNotificationEvent($notif));
        }

        return back()->with('success', "Project marked as {$statusLabel}.");
    }

    public function storeItem(Request $request, $id)
    {
        $estimate = ProjectEstimate::findOrFail($id);

        $validated = $this->validateItem($request);

        $estimate->items()->create($validated);

        $response = back()->with('success', 'Item added.');

        // "Save & Add Another" — flash a flag the show page reads to
        // reopen the Add Item modal automatically, instead of making the
        // user click "+ Add Item" again for every line.
        if ($request->input('action') === 'add_another') {
            $response->with('reopen_add_item', true);
        }

        return $response;
    }

    public function updateItem(Request $request, $id, $itemId)
    {
        $item = ProjectEstimateItem::where('project_estimate_id', $id)->findOrFail($itemId);

        $validated = $this->validateItem($request);

        $item->update($validated);

        return back()->with('success', 'Item updated.');
    }

    public function destroyItem($id, $itemId)
    {
        $item = ProjectEstimateItem::where('project_estimate_id', $id)->findOrFail($itemId);
        $item->delete();

        return back()->with('success', 'Item removed.');
    }

    // 📸 Attachments — before (current status prior to work), receipts,
    // work-done evidence, or anything else worth keeping with the estimate.
    // Optional, multiple files, each individually attributed to its
    // uploader (mirrors Job Request's photo evidence).
    public function uploadPhotos(Request $request, $id)
    {
        $estimate = ProjectEstimate::with('photos')->findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:before,receipt,work_done,other',
            'photos' => 'required|array',
            'photos.*' => 'image|max:5120',
        ], [
            'photos.required' => 'Please choose at least one photo to upload.',
            'photos.*.image' => 'Each file must be a photo (JPG, PNG, etc.).',
            'photos.*.max' => 'Each photo must be 5MB or smaller.',
        ]);

        // A "Work Done" photo only makes sense once there's a "Before" photo
        // to compare it against — enforced here, not just hidden in the UI,
        // since a direct POST could otherwise skip the client-side check.
        if ($validated['type'] === 'work_done' && $estimate->photos->where('type', 'before')->isEmpty()) {
            return back()->with('error', 'Upload a "Before" photo of the current project status first, before adding Work Done photos.');
        }

        foreach ($request->file('photos', []) as $photo) {

            $path = $photo->store('project_estimates', 'public');

            ProjectEstimatePhoto::create([
                'project_estimate_id' => $estimate->id,
                'path' => $path,
                'type' => $validated['type'],
                'uploaded_by' => Auth::id(),
            ]);
        }

        return back()->with('success', 'Photo(s) uploaded.');
    }

    public function destroyPhoto($id, $photoId)
    {
        $photo = ProjectEstimatePhoto::where('project_estimate_id', $id)->findOrFail($photoId);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Photo removed.');
    }

    public function print($id)
    {
        $estimate = ProjectEstimate::with(['items', 'preparer', 'photos.uploader'])->findOrFail($id);

        return view('project_estimates.print', compact('estimate'));
    }

    // 📊 Report — for Mark's own reporting/records, same pattern as Job
    // Request Report and the Attendance Report.
    public function report(Request $request)
    {
        return view('project_estimates.report', $this->reportData($request));
    }

    public function reportPrint(Request $request)
    {
        return view('project_estimates.report_print', $this->reportData($request));
    }

    private function reportData(Request $request): array
    {
        $dateFrom = $request->date('date_from');
        $dateTo = $request->date('date_to');

        $estimates = ProjectEstimate::with('items')
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->orderByDesc('created_at')
            ->get();

        $totalEstimates = $estimates->count();
        $totalValue = $estimates->sum(fn ($e) => $e->grandTotal());
        $totalMaterials = $estimates->sum(fn ($e) => $e->materialsTotal());
        $totalLabor = $estimates->sum(fn ($e) => $e->laborTotal());

        return [
            'estimates' => $estimates,
            'dateFrom' => $dateFrom?->format('Y-m-d'),
            'dateTo' => $dateTo?->format('Y-m-d'),
            'totalEstimates' => $totalEstimates,
            'totalValue' => $totalValue,
            'totalMaterials' => $totalMaterials,
            'totalLabor' => $totalLabor,
        ];
    }

    private function validateItem(Request $request): array
    {
        return $request->validate([
            'description' => 'required|string|max:200',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0',
            'category' => 'required|in:materials_equipment,labor',
        ], [
            'description.required' => 'Please describe this item.',
            'quantity.required' => 'Please enter a quantity.',
            'unit_cost.required' => 'Please enter a unit cost.',
        ]);
    }
}
