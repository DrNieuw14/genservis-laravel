@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                {{ $report->reference_no }}

                <span class="text-sm font-semibold px-3 py-1 rounded-full
                    {{ $report->status === 'converted' ? 'bg-blue-100 text-blue-700' :
                       ($report->status === 'resolved' ? 'bg-green-100 text-green-700' :
                       ($report->status === 'dismissed' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-100 text-yellow-700')) }}">
                    {{ $report->statusLabel() }}
                </span>
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $report->location }}
            </p>
        </div>

        <div class="flex gap-2">
            @if($isOwner && $report->status === 'reported')
                <a href="{{ route('problem-reports.edit', $report->id) }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    ✏️ Edit
                </a>

                <form method="POST" action="{{ route('problem-reports.destroy', $report->id) }}"
                      onsubmit="return genservisConfirm(event, 'Delete this problem report? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                        🗑 Delete
                    </button>
                </form>
            @endif

            <x-back-button :href="$canReview ? route('problem-reports.index') : route('problem-reports.my')" />
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- DETAILS -->
    <div class="border rounded-lg p-5 bg-gray-50 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

            <div>
                <p class="text-gray-500">Reported By</p>
                <p class="font-semibold">{{ $report->reporter->fullname ?? $report->reporter->name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">Reported On</p>
                <p class="font-semibold">{{ $report->created_at->format('M d, Y h:i A') }}</p>
            </div>

        </div>

        <div class="mt-4">
            <p class="text-gray-500 text-sm">Problem Description</p>
            <p class="mt-1">{{ $report->problem_description }}</p>
        </div>

        @if($report->photo_path)
            <div class="mt-4">
                <p class="text-gray-500 text-sm mb-2">Photo</p>
                <img src="{{ $report->photo_url }}" class="w-48 h-48 object-cover rounded-lg border">
            </div>
        @endif

    </div>

    @if($report->admin_notes)
        <div class="border rounded-lg p-5 bg-blue-50 border-blue-200 mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">🔍 Physical Plant and Services' Assessment</h3>
            <p class="text-gray-700">{{ $report->admin_notes }}</p>
            @if($report->reviewer)
                <p class="text-xs text-gray-500 mt-2">
                    — {{ $report->reviewer->fullname ?? $report->reviewer->name }}, {{ $report->reviewed_at?->format('M d, Y h:i A') }}
                </p>
            @endif
        </div>
    @endif

    @if($report->jobRequest)
        <div class="border rounded-lg p-5 bg-green-50 border-green-200 mb-6 flex items-center justify-between">
            <p class="text-gray-700">
                🛠️ Converted to Job Request <strong>{{ $report->jobRequest->reference_no }}</strong>
            </p>
            <a href="{{ route('job-requests.show', $report->jobRequest->id) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">
                View Job Request →
            </a>
        </div>
    @endif

    <!-- REVIEW ACTIONS -->
    @if($canReview && !in_array($report->status, ['converted', 'resolved', 'dismissed']))

        <div class="border rounded-lg p-5 bg-yellow-50 border-yellow-200 mb-6">

            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                📝 Assessment
            </h3>

            <form method="POST" action="{{ route('problem-reports.review', $report->id) }}" class="mb-2">
                @csrf

                <label class="block mb-2 font-semibold text-sm">Notes — e.g. is there a budget or item/material need for fixing or replacement?</label>
                <textarea name="admin_notes" rows="3" class="w-full border rounded-lg p-3 mb-3"
                    placeholder="e.g. Needs a replacement bulb — check Materials Inventory before scheduling.">{{ old('admin_notes', $report->admin_notes) }}</textarea>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
                    💾 Save Assessment
                </button>
            </form>

        </div>

        <div class="border rounded-lg p-5 bg-green-50 border-green-200 mb-6">

            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                🛠️ Convert to Job Request
            </h3>

            <p class="text-gray-600 text-sm mb-4">
                If this needs personnel to fix it (with or without materials from inventory), convert it into a real Job Request so it can be assigned and tracked.
            </p>

            <form method="POST" action="{{ route('problem-reports.convert', $report->id) }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-sm">Route to</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="category" value="physical_plant" checked required>
                            🏗️ Physical Plant & Services
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="category" value="utility" required>
                            🧹 Utility Personnel
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-sm">Office / Unit / Project</label>
                    <input type="text" name="office_unit_project" value="{{ old('office_unit_project', $report->location) }}"
                        class="w-full border rounded-lg p-3" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-sm">Nature of Request</label>
                    <input type="text" name="nature_of_request"
                        value="{{ old('nature_of_request', \Illuminate\Support\Str::limit($report->problem_description, 100)) }}"
                        class="w-full border rounded-lg p-3" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-sm">Work Summary</label>
                    <textarea name="work_summary" rows="3" class="w-full border rounded-lg p-3" required>{{ old('work_summary', $report->problem_description) }}</textarea>
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow"
                    onclick="return genservisConfirm(event, 'Create a Job Request from this report?')">
                    🛠️ Convert to Job Request
                </button>
            </form>

        </div>

        <div class="flex gap-3">

            <form method="POST" action="{{ route('problem-reports.resolve', $report->id) }}"
                  onsubmit="return genservisConfirm(event, 'Mark this report resolved without a Job Request?')">
                @csrf
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-lg shadow">
                    ✅ Mark Resolved (no Job Request needed)
                </button>
            </form>

            <form method="POST" action="{{ route('problem-reports.dismiss', $report->id) }}"
                  onsubmit="return genservisConfirm(event, 'Dismiss this report?')">
                @csrf
                <input type="hidden" name="admin_notes" value="{{ $report->admin_notes }}">
                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-5 py-3 rounded-lg shadow">
                    🚫 Dismiss
                </button>
            </form>

        </div>

    @endif

</div>

@endsection
