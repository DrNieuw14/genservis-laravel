@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            🏠 Procurement Dashboard
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Overview of your institution's procurement plans and budgets.
        </p>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
            <h3 class="text-gray-500 text-base">Total Plans</h3>
            <p class="text-4xl font-bold text-gray-800 mt-1">{{ $totalPlans }}</p>
        </div>

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
            <h3 class="text-gray-500 text-base">Draft Plans</h3>
            <p class="text-4xl font-bold text-gray-800 mt-1">{{ $draftPlans }}</p>
        </div>

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
            <h3 class="text-gray-500 text-base">Submitted</h3>
            <p class="text-4xl font-bold text-gray-800 mt-1">{{ $submittedPlans }}</p>
        </div>

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
            <h3 class="text-gray-500 text-base">Approved Plans</h3>
            <p class="text-4xl font-bold text-gray-800 mt-1">{{ $approvedPlans }}</p>
        </div>

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
            <h3 class="text-gray-500 text-base">Allocated Budget</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">
                ₱ {{ number_format($allocatedBudget,2) }}
            </p>
        </div>

        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
            <h3 class="text-gray-500 text-base">Approved Budget</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">
                ₱ {{ number_format($approvedBudget,2) }}
            </p>
        </div>

    </div>

</div>

@endsection
