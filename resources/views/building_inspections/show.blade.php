@extends('layouts.app')

@section('content')

@php $score = $inspection->conditionScore(); @endphp

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏢 {{ $inspection->building_name }}
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $inspection->reference_no }} — Inspected {{ $inspection->inspection_date->format('M d, Y') }}
            </p>
        </div>

        <div class="flex gap-2">

            <x-back-button :href="route('building-inspections.index')" />

            <a href="{{ route('building-inspections.edit', $inspection->id) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                ✏️ Edit
            </a>

            <a href="{{ route('building-inspections.print', $inspection->id) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🖨 Print
            </a>

            <form method="POST" action="{{ route('building-inspections.destroy', $inspection->id) }}"
                  onsubmit="return confirm('Delete this inspection and all its data? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    🗑 Delete
                </button>
            </form>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
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

    <!-- CONDITION SCORE + INFO -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="border rounded-lg p-5 bg-gray-50">
            <p class="text-sm text-gray-500">Building In-Charge</p>
            <p class="font-semibold text-lg mt-1">{{ $inspection->building_in_charge ?? '-' }}</p>
        </div>

        <div class="border rounded-lg p-5 bg-gray-50">
            <p class="text-sm text-gray-500">Inspected By</p>
            <p class="font-semibold text-lg mt-1">{{ $inspection->inspector->fullname ?? $inspection->inspector->name ?? '-' }}</p>
        </div>

        <div class="border rounded-lg p-5 bg-gray-50">
            <p class="text-sm text-gray-500">Noted By</p>
            <p class="font-semibold text-lg mt-1">{{ $inspection->noted_by ?? '-' }}</p>
        </div>

        <div class="rounded-lg p-5 text-white shadow-lg
            {{ $score['rating'] === 'good' ? 'bg-gradient-to-r from-green-600 to-green-700' : ($score['rating'] === 'needs_attention' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-red-600 to-red-700') }}">
            <p class="text-sm opacity-90">Overall Condition</p>
            <p class="font-bold text-xl mt-1">{{ $score['label'] }}</p>
            <p class="text-sm opacity-90 mt-1">{{ $score['flagged'] }} of {{ $score['total'] }} issues flagged</p>
        </div>

    </div>

    <!-- CATEGORY CARDS -->
    <div class="space-y-6">

        @foreach($inspection->items as $item)

            <div class="border rounded-lg p-6">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ $item->categoryLabel() }}</h3>
                    <span class="text-sm text-gray-500">{{ $item->flaggedIssueCount() }} of {{ $item->totalIssueItemCount() }} flagged</span>
                </div>

                <form method="POST" action="{{ route('building-inspections.items.update', [$inspection->id, $item->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-4">

                        @foreach($item->categoryItems() as $observation)

                            <label class="flex items-start gap-2 text-sm">
                                <input type="checkbox" name="flagged_observations[]" value="{{ $observation['text'] }}"
                                    {{ $item->isFlagged($observation['text']) ? 'checked' : '' }}
                                    class="mt-1 rounded border-gray-300 {{ $observation['polarity'] === 'issue' ? 'text-red-600 focus:ring-red-500' : 'text-green-600 focus:ring-green-500' }}">
                                <span>{{ $observation['text'] }}</span>
                            </label>

                        @endforeach

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block mb-1 font-semibold text-sm">Others (optional)</label>
                            <textarea name="other_observations" rows="2"
                                class="w-full border rounded-lg p-3 text-sm">{{ $item->other_observations }}</textarea>
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold text-sm">Remarks</label>
                            <textarea name="remarks" rows="2"
                                class="w-full border rounded-lg p-3 text-sm">{{ $item->remarks }}</textarea>
                        </div>

                    </div>

                    <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow text-sm">
                        💾 Save {{ $item->categoryLabel() }}
                    </button>

                </form>

                <!-- PHOTOS -->
                <div class="mt-5 pt-5 border-t">

                    <form method="POST" action="{{ route('building-inspections.items.photos.store', [$inspection->id, $item->id]) }}"
                          enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                        @csrf

                        <div class="flex-1 min-w-[200px]">
                            <label class="block mb-1 font-semibold text-sm">Photo Evidence (optional)</label>
                            <input type="file" name="photos[]" multiple accept="image/*" class="w-full border rounded-lg p-2 bg-white text-sm">
                        </div>

                        <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow text-sm">
                            ⬆️ Upload
                        </button>

                    </form>

                    @if($item->photos->isNotEmpty())

                        <div class="mt-4 flex flex-wrap gap-4">

                            @foreach($item->photos as $photo)

                                <div class="text-center">

                                    <a href="{{ $photo->url }}" target="_blank">
                                        <img src="{{ $photo->url }}" alt="Inspection photo"
                                            class="w-24 h-24 object-cover rounded-lg border hover:opacity-80 transition">
                                    </a>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}
                                    </p>

                                    <form method="POST" action="{{ route('building-inspections.items.photos.destroy', [$inspection->id, $item->id, $photo->id]) }}"
                                          onsubmit="return confirm('Remove this photo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs">
                                            🗑 Remove
                                        </button>
                                    </form>

                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection
