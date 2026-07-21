@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏠 Room Inventory of Property
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Fixed/durable property (furniture, ICT equipment, appliances) tracked per room.
            </p>
        </div>

        <a href="{{ route('property-inventory.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">
            ➕ Add Room
        </a>

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

    <form method="GET" action="{{ route('property-inventory.index') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search room name or building..."
                   class="w-full max-w-md border rounded-lg p-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                🔍 Search
            </button>
            @if($search)
                <a href="{{ route('property-inventory.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg shadow">
                    Clear
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Room</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Building</th>
                    <th class="p-3 text-left">Department</th>
                    <th class="p-3 text-center">Items</th>
                    <th class="p-3 text-center">Total Value (₱)</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($rooms as $room)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $room->room_name }}</td>
                        <td class="p-3">{{ $room->room_type ?? '-' }}</td>
                        <td class="p-3">{{ $room->building ?? '-' }}</td>
                        <td class="p-3">{{ $room->department->department_name ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $room->propertyItems->count() }}</td>
                        <td class="p-3 text-center">{{ number_format($room->totalValue(), 2) }}</td>
                        <td class="p-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $room->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                {{ $room->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <a href="{{ route('property-inventory.show', $room->id) }}" class="text-blue-600 hover:underline text-sm">
                                📋 View
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No rooms yet. Click "Add Room" to start the inventory.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $rooms->links() }}
    </div>

</div>

@endsection
