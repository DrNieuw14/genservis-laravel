@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-8 px-4">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                📦 Material Details
            </h1>

            <p class="text-gray-500 mt-1">
                Complete inventory profile and audit history
            </p>

        </div>

        <a href="{{ route('materials.index') }}"
           class="bg-gray-700 hover:bg-gray-800 text-white px-5 py-3 rounded-xl shadow">

            ← Back to Inventory

        </a>

    </div>

    <!-- MATERIAL PROFILE -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- LEFT -->
            <div>

                <h2 class="text-2xl font-bold text-blue-700 mb-4">
                    {{ $material->name }}
                </h2>

                <div class="space-y-3 text-gray-700">

                    <p>
                        <strong>Department:</strong>
                        {{ $material->department->department_name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Category:</strong>
                        {{ $material->category->name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Unit:</strong>
                        {{ $material->unit->name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Threshold:</strong>
                        {{ $material->threshold }}
                    </p>

                    <p>
                        <strong>Created By:</strong>
                        {{ $material->creator->username ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Date Added:</strong>
                        {{ $material->created_at->format('M d, Y h:i A') }}
                    </p>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="flex flex-col justify-center items-center">

                <div class="text-6xl font-bold mb-4">

                    {{ $material->quantity }}

                </div>

                @if($material->quantity <= 0)

                    <span class="bg-red-600 text-white px-5 py-2 rounded-full text-lg">
                        ❌ Out of Stock
                    </span>

                @elseif($material->quantity <= 5)

                    <span class="bg-red-500 text-white px-5 py-2 rounded-full text-lg">
                        🔥 Critical Stock
                    </span>

                @elseif($material->quantity <= $material->threshold)

                    <span class="bg-yellow-400 text-black px-5 py-2 rounded-full text-lg">
                        ⚠️ Low Stock
                    </span>

                @else

                    <span class="bg-green-500 text-white px-5 py-2 rounded-full text-lg">
                        ✅ Healthy Stock
                    </span>

                @endif

            </div>

        </div>

    </div>

    <!-- RECENT INVENTORY MOVEMENTS -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">

        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            📊 Recent Inventory Movements
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Quantity</th>
                        <th class="p-3 text-left">Previous</th>
                        <th class="p-3 text-left">New</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($movements as $movement)

                        <tr class="border-t">

                            <td class="p-3">
                                {{ $movement->created_at->format('M d, Y h:i A') }}
                            </td>

                            <td class="p-3">
                                {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                            </td>

                            <td class="p-3 font-bold">
                                {{ $movement->quantity }}
                            </td>

                            <td class="p-3">
                                {{ $movement->previous_stock }}
                            </td>

                            <td class="p-3">
                                {{ $movement->new_stock }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="p-4 text-center text-gray-500">

                                No movement records found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- RESTOCK HISTORY -->
    <div class="bg-white rounded-2xl shadow-xl p-6">

        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            📦 Restock History
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-left">Added Stock</th>
                        <th class="p-3 text-left">Previous</th>
                        <th class="p-3 text-left">New</th>
                        <th class="p-3 text-left">Supplier</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($restocks as $restock)

                        <tr class="border-t">

                            <td class="p-3">
                                {{ $restock->created_at->format('M d, Y h:i A') }}
                            </td>

                            <td class="p-3 font-bold text-green-600">
                                +{{ $restock->added_stock }}
                            </td>

                            <td class="p-3">
                                {{ $restock->previous_stock }}
                            </td>

                            <td class="p-3">
                                {{ $restock->new_stock }}
                            </td>

                            <td class="p-3">
                                {{ $restock->supplier ?? 'N/A' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="p-4 text-center text-gray-500">

                                No restock history found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
