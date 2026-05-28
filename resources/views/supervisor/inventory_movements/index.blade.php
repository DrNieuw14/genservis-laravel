@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-8 px-4">

    <!-- TITLE -->
    <div class="mb-6">

        <h1 class="text-3xl font-bold text-gray-800">
            📦 Inventory Movement Logs
        </h1>

        <p class="text-gray-500 mt-2">
            Inventory Audit Center
        </p>

    </div>

    <!-- SEARCH -->
    <form method="GET"
          class="mb-6">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search material or movement type..."
               class="w-full border rounded-xl p-3">

    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr class="text-left text-gray-700">

                        <th class="p-4">Date</th>

                        <th class="p-4">Material</th>

                        <th class="p-4">Department</th>

                        <th class="p-4">Type</th>

                        <th class="p-4">Quantity</th>

                        <th class="p-4">Previous</th>

                        <th class="p-4">New</th>

                        <th class="p-4">User</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($movements as $movement)

                        <tr class="border-t hover:bg-gray-50">

                            <!-- DATE -->
                            <td class="p-4 text-sm text-gray-600">
                                {{ $movement->created_at->format('M d, Y h:i A') }}
                            </td>

                            <!-- MATERIAL -->
                            <td class="p-4 font-semibold text-gray-800">
                                {{ $movement->material->name ?? 'N/A' }}
                            </td>

                            <!-- DEPARTMENT -->
                            <td class="p-4 text-gray-700">

                                {{ $movement->material->department->department_name ?? 'N/A' }}

                            </td>

                            <!-- TYPE -->
                            <td class="p-4">

                                @if($movement->movement_type == 'restock')

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Restock
                                    </span>

                                @elseif($movement->movement_type == 'initial_stock')

                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Initial Stock
                                    </span>

                                @elseif($movement->movement_type == 'deducted')

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Deducted
                                    </span>

                                @elseif($movement->movement_type == 'request')

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Request
                                    </span>

                                @elseif($movement->movement_type == 'damage')

                                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Damage
                                    </span>

                                @elseif($movement->movement_type == 'transfer')

                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Transfer
                                    </span>

                                @else

                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Adjustment
                                    </span>

                                @endif

                            </td>

                            <!-- QUANTITY -->
                            <td class="p-4 font-bold">
                                {{ $movement->quantity }}
                            </td>

                            <!-- PREVIOUS -->
                            <td class="p-4">
                                {{ $movement->previous_stock }}
                            </td>

                            <!-- NEW -->
                            <td class="p-4">
                                {{ $movement->new_stock }}
                            </td>

                            <!-- USER -->
                            <td class="p-4 text-gray-700">
                                {{ $movement->user->username ?? 'N/A' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center p-6 text-gray-500">

                                No inventory movements found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection