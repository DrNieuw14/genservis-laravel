@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📦 Inventory Movement Logs
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Inventory Audit Center
        </p>

    </div>

    <!-- SEARCH -->
    <form method="GET" class="mb-6">

        <div class="flex flex-col md:flex-row gap-4">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search material or movement type..."
                   class="w-full border border-gray-300
                          rounded-lg p-3 text-lg
                          focus:ring-2 focus:ring-blue-400
                          outline-none">

            <button type="submit"
                    class="bg-gradient-to-r from-green-500 to-blue-500
                           hover:scale-105 transition
                           text-white px-6 py-3
                           rounded-xl shadow-lg font-semibold">

                🔍 Search

            </button>

        </div>

    </form>

    <!-- TABLE -->
    <div class="border rounded-lg overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-lg">

                <!-- HEADER -->
                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-4 text-left text-gray-800 whitespace-nowrap">
                            Date
                        </th>

                        <th class="p-4 text-left text-gray-800 whitespace-nowrap">
                            Material
                        </th>

                        <th class="p-4 text-left text-gray-800 whitespace-nowrap">
                            Department
                        </th>

                        <th class="p-4 text-left text-gray-800 whitespace-nowrap">
                            Movement Type
                        </th>

                        <th class="p-4 text-left text-gray-800 whitespace-nowrap">
                            Quantity
                        </th>

                        <th class="p-4 text-left text-gray-800 whitespace-nowrap">
                            Previous
                        </th>

                        <th class="p-4 text-left text-gray-800 whitespace-nowrap">
                            New
                        </th>

                        <th class="p-4 text-left text-gray-800 whitespace-nowrap">
                            User
                        </th>

                    </tr>

                </thead>

                <!-- BODY -->
                <tbody class="divide-y divide-gray-200">

                    @forelse($movements as $movement)

                    <tr class="hover:bg-gray-50 transition">

                        <!-- DATE -->
                        <td class="p-4 text-sm text-gray-600 whitespace-nowrap">

                            {{ $movement->created_at->format('M d, Y h:i A') }}

                        </td>

                        <!-- MATERIAL -->
                        <td class="p-4 font-semibold text-gray-800 whitespace-nowrap">

                            {{ $movement->material->name ?? 'N/A' }}

                        </td>

                        <!-- DEPARTMENT -->
                        <td class="p-4 text-gray-700">

                            {{ $movement->material->department->department_name ?? 'N/A' }}

                        </td>

                        <!-- TYPE -->
                        <td class="p-4">

                            @if($movement->movement_type == 'restock')

                                <span class="bg-green-100 text-green-700
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold">

                                    📥 Restock

                                </span>

                            @elseif($movement->movement_type == 'initial_stock')

                                <span class="bg-blue-100 text-blue-700
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold">

                                    📦 Initial Stock

                                </span>

                            @elseif($movement->movement_type == 'deducted')

                                <span class="bg-red-100 text-red-700
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold">

                                    ➖ Deducted

                                </span>

                            @elseif($movement->movement_type == 'request')

                                <span class="bg-yellow-100 text-yellow-700
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold">

                                    📝 Request

                                </span>

                            @elseif($movement->movement_type == 'damage')

                                <span class="bg-orange-100 text-orange-700
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold">

                                    ⚠ Damage

                                </span>

                            @elseif($movement->movement_type == 'transfer')

                                <span class="bg-indigo-100 text-indigo-700
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold">

                                    🔄 Transfer

                                </span>

                            @else

                                <span class="bg-gray-100 text-gray-700
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold">

                                    Adjustment

                                </span>

                            @endif

                        </td>

                        <!-- QUANTITY -->
                        <td class="p-4">

                            <span class="bg-gray-100 text-gray-800
                                         px-3 py-1 rounded-full
                                         text-sm font-bold">

                                {{ $movement->quantity }}

                            </span>

                        </td>

                        <!-- PREVIOUS -->
                        <td class="p-4 text-gray-700 font-medium">

                            {{ $movement->previous_stock }}

                        </td>

                        <!-- NEW -->
                        <td class="p-4">

                            <span class="bg-green-100 text-green-700
                                         px-3 py-1 rounded-full
                                         text-sm font-bold">

                                {{ $movement->new_stock }}

                            </span>

                        </td>

                        <!-- USER -->
                        <td class="p-4 text-gray-700 whitespace-nowrap">

                            {{ $movement->user->username ?? 'N/A' }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8"
                            class="text-center p-10 text-gray-500">

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