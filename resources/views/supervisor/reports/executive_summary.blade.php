@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-4xl font-bold text-white mt-4">
                Executive Inventory Summary
            </h1>

             <p class="text-blue-100 text-lg mt-3 max-w-4xl leading-relaxed">
                General Services Office
            </p>

             <p class="text-blue-100 text-lg mt-3 max-w-4xl leading-relaxed">
                Generated {{ now()->format('F d, Y h:i A') }}
            </p>

        </div>

        <button
            onclick="window.print()"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">

            🖨 Print Report

        </button>

    </div>


    <!-- KPI Cards -->

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="bg-white rounded-lg shadow p-5">

            <h4 class="text-gray-500 text-sm">
                Inventory Health
            </h4>

            <p class="text-4xl font-bold text-green-700">
                {{ $inventoryHealth }}%
            </p>

            <p class="text-gray-600">
                {{ $healthStatus }}
            </p>

        </div>

        <div class="bg-white rounded-lg shadow p-5">

            <h4 class="text-gray-500 text-sm">
                Available Materials
            </h4>

            <p class="text-4xl font-bold text-blue-700">
                {{ $availableMaterials }}
            </p>

        </div>

        <div class="bg-white rounded-lg shadow p-5">

            <h4 class="text-gray-500 text-sm">
                Needs Attention
            </h4>

            <p class="text-4xl font-bold text-red-700">
                {{ $criticalStock + $lowStock + $outOfStock }}
            </p>

        </div>

        <div class="bg-white rounded-lg shadow p-5">

            <h4 class="text-gray-500 text-sm">
                Expiring Soon
            </h4>

            <p class="text-4xl font-bold text-purple-700">
                {{ $expiringSoon }}
            </p>

        </div>

    </div>


    <!-- Executive Assessment -->

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">

        <h2 class="text-2xl font-bold text-blue-800 mb-4">
            Executive Assessment
        </h2>

        @if($inventoryHealth >= 90)

            <p class="text-lg">

                Inventory operations are currently
                <strong>Excellent</strong>.
                Most inventory items are sufficiently stocked,
                and only a small number require monitoring.

            </p>

        @elseif($inventoryHealth >= 75)

            <p class="text-lg">

                Inventory condition is
                <strong>Good</strong>.
                Continue monitoring low-stock materials
                to avoid shortages.

            </p>

        @elseif($inventoryHealth >= 50)

            <p class="text-lg">

                Inventory condition is
                <strong>Fair</strong>.
                Restocking should be prioritized
                for several materials.

            </p>

        @else

            <p class="text-lg text-red-700">

                Inventory condition is
                <strong>Poor</strong>.
                Immediate procurement is recommended
                to prevent operational disruption.

            </p>

        @endif

    </div>


    <!-- Top Critical Materials -->

    <div class="bg-white rounded-lg shadow">

        <div class="border-b p-5">

            <h2 class="text-2xl font-bold">

                Top 10 Critical Materials

            </h2>

        </div>

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3 text-left">
                        Material
                    </th>

                    <th class="p-3">
                        Department
                    </th>

                    <th class="p-3">
                        Qty
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($topCritical as $material)

                <tr class="border-t">

                    <td class="p-3">

                        {{ $material->name }}

                    </td>

                    <td class="text-center">

                        {{ $material->department->department_name ?? '-' }}

                    </td>

                    <td class="text-center text-red-600 font-bold">

                        {{ $material->quantity }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="3" class="text-center p-6">

                        No critical materials.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection