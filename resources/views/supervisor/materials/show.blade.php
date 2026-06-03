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

    <!-- INVENTORY BATCHES -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mt-6">

        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            📦 Inventory Batches
        </h2>

        <p class="text-gray-500 mb-4">
            Batch tracking, supplier source, and remaining stock per inventory batch.
        </p>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-left">Batch No</th>
                        <th class="p-3 text-left">Received</th>
                        <th class="p-3 text-left">Remaining</th>
                        <th class="p-3 text-left">Supplier</th>
                        <th class="p-3 text-left">Invoice</th>
                        <th class="p-3 text-left">Expiration</th>
                        <th class="p-3 text-left">Days Left</th>
                        <th class="p-3 text-left">Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($restocks as $batch)

                        <tr class="border-t">

                            <td class="p-3 font-semibold text-blue-700">
                                {{ $batch->batch_no ?? 'N/A' }}
                            </td>

                            <td class="p-3">
                                {{ $batch->added_stock }}
                            </td>

                            <td class="p-3 font-bold text-blue-700">

                                {{ $batch->quantity_remaining ?? 0 }}

                            </td>

                            <td class="p-3">
                                {{ $batch->supplier ?? 'N/A' }}
                            </td>

                            <td class="p-3">
                                {{ $batch->invoice_no ?? 'N/A' }}
                            </td>

                            <!-- EXPIRATION -->
                            <td class="p-3">

                                @if($batch->has_expiration)

                                    {{ \Carbon\Carbon::parse(
                                        $batch->expiration_date
                                    )->format('M d, Y') }}

                                @else

                                    N/A

                                @endif

                            </td>

                            <!-- DAYS LEFT -->
                            <td class="p-3">

                                @if($batch->has_expiration)

                                    @php

                                        $days = (int) now()->diffInDays(
                                            $batch->expiration_date,
                                            false
                                        );

                                    @endphp

                                    @if($days < 0)

                                        <span class="text-red-600 font-bold">
                                            Expired
                                        </span>

                                    @elseif($days <= 30)

                                        <span class="text-orange-600 font-bold">
                                            {{ $days }} Days
                                        </span>

                                    @else

                                        <span class="text-green-600 font-semibold">
                                            {{ $days }} Days
                                        </span>

                                    @endif

                                @else

                                    N/A

                                @endif

                            </td>

                            <!-- STATUS -->
                            <td class="p-3">

                                @if($batch->quantity_remaining > 0)

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                                        🟢 Available

                                    </span>

                                @else

                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm">

                                        ⚫ Consumed

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="p-4 text-center text-gray-500">

                                No batch records found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- RECENT MATERIAL DISTRIBUTION -->
<div class="bg-white rounded-2xl shadow-xl p-6 mt-6">

    <h2 class="text-2xl font-bold text-gray-800 mb-2">
        👥 Recent Material Distribution
    </h2>

    <p class="text-gray-500 mb-4">
        Shows personnel who received this material through approved requests.
    </p>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3 text-left">Request No</th>
                    <th class="p-3 text-left">Personnel</th>
                    <th class="p-3 text-left">Department</th>
                    <th class="p-3 text-left">Quantity</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Date</th>

                </tr>

            </thead>

            <tbody>

                @forelse($distributions as $distribution)

                    <tr class="border-t">

                        <td class="p-3 font-semibold text-blue-700">
                            {{ $distribution->request->request_number ?? 'N/A' }}
                        </td>

                        <td class="p-3">
                            {{ $distribution->request->user->fullname
                                ?? $distribution->request->user->username
                                ?? 'N/A' }}
                        </td>

                        <td class="p-3">
                            {{ $distribution->request->department->department_name ?? 'N/A' }}
                        </td>

                        <td class="p-3 font-bold">
                            {{ $distribution->quantity }}
                        </td>

                        <td class="p-3">

                            @if($distribution->request->status == 'approved')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                    ✅ Approved
                                </span>

                            @elseif($distribution->request->status == 'pending')

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                    ⏳ Pending
                                </span>

                            @else

                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                    ❌ Rejected
                                </span>

                            @endif

                        </td>

                        <td class="p-3">
                            {{ $distribution->created_at->format('M d, Y') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="p-4 text-center text-gray-500">

                            No distribution records found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
