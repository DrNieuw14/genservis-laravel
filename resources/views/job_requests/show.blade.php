@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex justify-between items-start mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🛠️ Job Request {{ $jobRequest->reference_no }}
            </h2>

            <div class="mt-2">
                @include('job_requests.partials.status-badge', ['status' => $jobRequest->status])
            </div>
        </div>

        <div class="flex gap-2">

            @php
                $backHref = match(true) {
                    $canApprove || $canAssign => route('job-requests.index'),
                    $isAssignedWorker => route('job-requests.my-assigned'),
                    default => route('job-requests.history'),
                };
            @endphp

            <x-back-button :href="$backHref" />

            <a href="{{ route('job-requests.print', $jobRequest->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- REQUEST DETAILS -->
    <div class="border rounded-lg p-5 bg-gray-50 mb-6">

        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            🧾 Request Details
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">Requesting Party</p>
                <p class="font-semibold mt-1">{{ $jobRequest->requesting_party }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Office / Unit / Project</p>
                <p class="font-semibold mt-1">{{ $jobRequest->office_unit_project }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Department</p>
                <p class="font-semibold mt-1">{{ $jobRequest->department->department_name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Category</p>
                <p class="font-semibold mt-1">{{ $jobRequest->categoryLabel() }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Nature of Request</p>
                <p class="font-semibold mt-1">{{ $jobRequest->nature_of_request }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Work Category</p>
                <p class="font-semibold mt-1">{{ $jobRequest->work_category ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Target Date</p>
                <p class="font-semibold mt-1">{{ $jobRequest->target_date?->format('M d, Y') ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Date Submitted</p>
                <p class="font-semibold mt-1">{{ $jobRequest->created_at->format('M d, Y h:i A') }}</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Work Summary</p>
                <p class="font-semibold mt-1">{{ $jobRequest->work_summary }}</p>
            </div>

        </div>

    </div>

    <!-- EVIDENCE SUBMITTED WITH REQUEST -->
    @php
        $requestEvidencePhotos = $jobRequest->photos->where('type', 'request_evidence');
        $canManageEvidence = $isOwner || $canApprove || $canAssign;
    @endphp

    @if($requestEvidencePhotos->isNotEmpty() || $canManageEvidence)

        <div class="border rounded-lg p-5 bg-gray-50 mb-6">

            <p class="text-sm text-gray-500 mb-2">
                📷 Evidence Submitted with Request ({{ $requestEvidencePhotos->count() }})
            </p>

            @if($requestEvidencePhotos->isNotEmpty())

                <div class="flex flex-wrap gap-4 mb-4">

                    @foreach($requestEvidencePhotos as $photo)

                        <div class="text-center">

                            <a href="{{ $photo->url }}" target="_blank">
                                <img
                                    src="{{ $photo->url }}"
                                    alt="Evidence photo"
                                    class="w-28 h-28 object-cover rounded-lg border hover:opacity-80 transition">
                            </a>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}
                            </p>

                            @if($canManageEvidence || $photo->uploaded_by === auth()->id())
                                <form method="POST" action="{{ route('job-requests.photos.destroy', [$jobRequest->id, $photo->id]) }}"
                                      onsubmit="return confirm('Remove this photo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-xs">🗑 Remove</button>
                                </form>
                            @endif

                        </div>

                    @endforeach

                </div>

            @endif

            @if($canManageEvidence)

                <form method="POST" action="{{ route('job-requests.evidence-photos.store', $jobRequest->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[200px]">
                            <input type="file" name="photos[]" accept="image/*" multiple class="w-full border rounded-lg p-2 bg-white">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                            ⬆️ Add Photo
                        </button>
                    </div>
                    @error('photos.*')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </form>

            @endif

        </div>

    @endif

    <!-- APPROVAL -->
    @if(in_array($jobRequest->status, ['approved', 'assigned', 'work_done', 'completed', 'rejected']))

        <div class="border rounded-lg p-5 bg-gray-50 mb-6">

            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                ✅ Approval
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p class="text-sm text-gray-500">{{ $jobRequest->status === 'rejected' ? 'Rejected By' : 'Approved By' }}</p>
                    <p class="font-semibold mt-1">{{ $jobRequest->approver->fullname ?? $jobRequest->approver->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Date</p>
                    <p class="font-semibold mt-1">{{ $jobRequest->approved_at?->format('M d, Y h:i A') ?? '-' }}</p>
                </div>

                @if($jobRequest->status === 'rejected')
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Reason</p>
                        <p class="font-semibold mt-1">{{ $jobRequest->rejection_reason }}</p>
                    </div>
                @elseif($jobRequest->remarks)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Remarks</p>
                        <p class="font-semibold mt-1">{{ $jobRequest->remarks }}</p>
                    </div>
                @endif

            </div>

        </div>

    @endif

    <!-- ASSIGNED PERSONNEL -->
    @if(in_array($jobRequest->status, ['assigned', 'work_done', 'completed']))

        <div class="border rounded-lg p-5 bg-gray-50 mb-6">

            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                👷 Assigned Personnel
            </h3>

            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($jobRequest->assignedPersonnel as $person)
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $person->fullname }}
                    </span>
                @endforeach
            </div>

            <p class="text-sm text-gray-500">
                Assigned by {{ $jobRequest->assigner->fullname ?? $jobRequest->assigner->name ?? '-' }}
                on {{ $jobRequest->assigned_at?->format('M d, Y h:i A') ?? '-' }}
            </p>

            @if($jobRequest->work_done_at)
                <p class="text-sm text-teal-700 font-semibold mt-2">
                    🔧 Work marked done by {{ $jobRequest->workDoneBy->fullname ?? $jobRequest->workDoneBy->name ?? '-' }}
                    on {{ $jobRequest->work_done_at->format('M d, Y h:i A') }} — awaiting sign-off.
                </p>
            @endif

            @php $workDonePhotos = $jobRequest->photos->where('type', 'work_done'); @endphp

            @if($workDonePhotos->isNotEmpty())

                <div class="mt-4">

                    <p class="text-sm text-gray-500 mb-2">
                        🔧 Work Done Evidence ({{ $workDonePhotos->count() }})
                    </p>

                    <div class="flex flex-wrap gap-4">

                        @foreach($workDonePhotos as $photo)

                            <div class="text-center">

                                <a href="{{ $photo->url }}" target="_blank">
                                    <img
                                        src="{{ $photo->url }}"
                                        alt="Work evidence"
                                        class="w-28 h-28 object-cover rounded-lg border hover:opacity-80 transition">
                                </a>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}
                                </p>

                                @if($canApprove || $canAssign || $photo->uploaded_by === auth()->id())
                                    <form method="POST" action="{{ route('job-requests.photos.destroy', [$jobRequest->id, $photo->id]) }}"
                                          onsubmit="return confirm('Remove this photo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs">🗑 Remove</button>
                                    </form>
                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>

    @endif

    <!-- ACTIONS -->
    @if($canApprove && $jobRequest->status === 'pending')

        <div class="border rounded-lg p-5 bg-yellow-50 border-yellow-200 mb-6">

            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                ⚖️ Approve or Reject
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <form method="POST" action="{{ route('job-requests.approve', $jobRequest->id) }}">
                    @csrf

                    <label class="block mb-2 font-semibold text-sm">Remarks (optional)</label>
                    <textarea name="remarks" rows="3" class="w-full border rounded-lg p-3 mb-3" placeholder="Instructions for the assigned crew..."></textarea>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
                        ✅ Approve
                    </button>
                </form>

                <form method="POST" action="{{ route('job-requests.reject', $jobRequest->id) }}">
                    @csrf

                    <label class="block mb-2 font-semibold text-sm">Reason for Rejection</label>
                    <textarea name="rejection_reason" rows="3" class="w-full border rounded-lg p-3 mb-3" placeholder="Explain why this request is being rejected..." required></textarea>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
                        ❌ Reject
                    </button>
                </form>

            </div>

        </div>

    @endif

    @if($canAssign && $jobRequest->status === 'approved')

        <div class="border rounded-lg p-5 bg-blue-50 border-blue-200 mb-6 flex items-center justify-between">

            <p class="text-gray-700">
                This request is approved and ready for personnel assignment.
            </p>

            <a href="{{ route('job-requests.assign', $jobRequest->id) }}"
               class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-3 rounded-lg shadow whitespace-nowrap">
                👷 Assign Personnel
            </a>

        </div>

    @endif

    @if($canAssign && $jobRequest->status === 'assigned')

        <div class="border rounded-lg p-5 bg-purple-50 border-purple-200 mb-6 flex items-center justify-between flex-wrap gap-3">

            <p class="text-gray-700">
                Personnel are assigned and the job is in progress. Waiting for them
                to mark their work done before this can be closed out.
            </p>

            <a href="{{ route('job-requests.assign', $jobRequest->id) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-5 py-3 rounded-lg shadow whitespace-nowrap">
                ✏️ Change Assignment
            </a>

        </div>

    @endif

    @if($isAssignedWorker && in_array($jobRequest->status, ['assigned', 'work_done']))

        <div class="border rounded-lg p-5 bg-teal-50 border-teal-200 mb-6">

            @if($jobRequest->status === 'assigned')

                <p class="text-gray-700 mb-4">
                    You're assigned to this job. Once the work is finished, mark it done
                    so {{ $jobRequest->assigner->fullname ?? $jobRequest->assigner->name ?? 'the approver' }}
                    can review and close it out.
                </p>

            @else

                <p class="text-gray-700 mb-4">
                    This job has already been marked done
                    @if($jobRequest->workDoneBy && $jobRequest->workDoneBy->id !== auth()->id())
                        by {{ $jobRequest->workDoneBy->fullname ?? $jobRequest->workDoneBy->name }}
                    @endif
                    and is awaiting sign-off. You can still add your own evidence photos below
                    until it's closed out.
                </p>

            @endif

            <form method="POST" action="{{ route('job-requests.mark-work-done', $jobRequest->id) }}" enctype="multipart/form-data">
                @csrf

                <label class="block mb-2 font-semibold text-sm">
                    Photo Evidence {{ $jobRequest->status === 'assigned' ? '(optional)' : '' }}
                </label>

                <p class="text-sm text-gray-500 mb-3">
                    Add photos of the completed work for Mark's reporting records. You can add more than one.
                </p>

                <input
                    type="file"
                    name="photos[]"
                    accept="image/*"
                    multiple
                    onchange="previewWorkDonePhotos(this)"
                    class="w-full border rounded-lg p-3 bg-white mb-3">

                @error('photos.*')
                    <p class="text-sm text-red-600 mb-3">{{ $message }}</p>
                @enderror

                <div id="work-done-preview" class="flex flex-wrap gap-3 mb-4"></div>

                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
                    {{ $jobRequest->status === 'assigned' ? '🔧 Mark Work Done' : '📸 Add More Photos' }}
                </button>
            </form>

        </div>

        <script>

            function previewWorkDonePhotos(input)
            {
                const container = document.getElementById('work-done-preview');

                container.innerHTML = '';

                if (!input.files) {
                    return;
                }

                Array.from(input.files).forEach(function (file) {

                    const img = document.createElement('img');

                    img.src = URL.createObjectURL(file);
                    img.className = 'w-24 h-24 object-cover rounded-lg border';

                    container.appendChild(img);

                });
            }

        </script>

    @endif

    @if($canAssign && $jobRequest->status === 'work_done')

        <div class="border rounded-lg p-5 bg-green-50 border-green-200 mb-6 flex items-center justify-between flex-wrap gap-3">

            <p class="text-gray-700">
                The assigned personnel marked this job done. Review and close it out.
            </p>

            <div class="flex gap-2">

                <a href="{{ route('job-requests.assign', $jobRequest->id) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-5 py-3 rounded-lg shadow whitespace-nowrap">
                    ✏️ Change Assignment
                </a>

                <form method="POST" action="{{ route('job-requests.complete', $jobRequest->id) }}">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow whitespace-nowrap">
                        🏁 Mark Completed
                    </button>
                </form>

            </div>

        </div>

    @endif

</div>

@endsection
