@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                💊 Clinic Medicine Inventory
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Campus Health Services — medicine and medical supply stock.
            </p>
        </div>

        <a href="{{ route('clinic-medicines.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow whitespace-nowrap">
            ➕ Add Medicine
        </a>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-3">

        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or brand"
            class="w-full border rounded-lg p-3">

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow whitespace-nowrap">
            🔍 Search
        </button>

        @if($search)
            <a href="{{ route('clinic-medicines.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg shadow text-center">
                Clear
            </a>
        @endif

    </form>

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Brand</th>
                    <th class="p-3 text-left">Unit</th>
                    <th class="p-3 text-center">Current Stock</th>
                    <th class="p-3 text-center">Expiration</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($medicines as $medicine)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3 font-semibold">{{ $medicine->name }}</td>

                        <td class="p-3">{{ $medicine->brand ?: '-' }}</td>

                        <td class="p-3">{{ $medicine->unit ?: '-' }}</td>

                        <td class="p-3 text-center">{{ $medicine->current_stock }}</td>

                        <td class="p-3 text-center">{{ $medicine->expiration_date?->format('M Y') ?: '-' }}</td>

                        <td class="p-3 text-center">
                            @if($medicine->isOutOfStock())
                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-full">Out of Stock</span>
                            @elseif($medicine->isExpired())
                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-full">Expired</span>
                            @elseif($medicine->isExpiringSoon())
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">Expiring Soon</span>
                            @else
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">OK</span>
                            @endif
                        </td>

                        <td class="p-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('clinic-medicines.edit', $medicine->id) }}"
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('clinic-medicines.destroy', $medicine->id) }}"
                                      onsubmit="return confirm('Remove this medicine from inventory?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No medicines in inventory yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $medicines->links() }}
    </div>

</div>

@endsection
