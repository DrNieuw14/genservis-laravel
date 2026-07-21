@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                ➕ Add Room
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Add a room to start tracking its property.
            </p>
        </div>

        <x-back-button :href="route('property-inventory.index')" />
    </div>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('property-inventory.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Room Name</label>
                <input type="text" name="room_name" value="{{ old('room_name') }}" placeholder="e.g. Room 101, Faculty Lounge"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                       required>
            </div>

            @php
                $oldRoomType = old('room_type');
                $isCustomRoomType = $oldRoomType && !in_array($oldRoomType, \App\Models\Room::ROOM_TYPES);
            @endphp

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                <select id="room_type_select" name="room_type" onchange="toggleRoomTypeOther(this)"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">— None —</option>
                    @foreach(\App\Models\Room::ROOM_TYPES as $type)
                        <option value="{{ $type }}"
                            @selected(($isCustomRoomType && $type === 'Other') || (!$isCustomRoomType && $oldRoomType === $type))>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>

                <input type="text" id="room_type_other_input" name="room_type_other"
                       value="{{ $isCustomRoomType ? $oldRoomType : '' }}"
                       placeholder="Enter custom room type (e.g. Networking Laboratory)"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-green-500 {{ $isCustomRoomType ? '' : 'hidden' }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Building</label>
                <input type="text" name="building" value="{{ old('building') }}" placeholder="e.g. Main Building"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="department_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">— None —</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                            {{ $department->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                          class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description') }}</textarea>
            </div>

        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow">
                ✔ Save Room
            </button>

            <a href="{{ route('property-inventory.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-xl">
                Cancel
            </a>
        </div>

    </form>

</div>

<script>

    function toggleRoomTypeOther(select) {
        const otherInput = document.getElementById('room_type_other_input');
        otherInput.classList.toggle('hidden', select.value !== 'Other');
    }

</script>

@endsection
