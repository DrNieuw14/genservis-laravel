<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Procurement Planning Dashboard
    </h2>
</x-slot>

<div class="py-6">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-500">Total Plans</h3>
                <p class="text-3xl font-bold">{{ $totalPlans }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-500">Draft Plans</h3>
                <p class="text-3xl font-bold">{{ $draftPlans }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-500">Submitted</h3>
                <p class="text-3xl font-bold">{{ $submittedPlans }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-500">Approved Plans</h3>
                <p class="text-3xl font-bold">{{ $approvedPlans }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-500">Allocated Budget</h3>
                <p class="text-2xl font-bold">
                    ₱ {{ number_format($allocatedBudget,2) }}
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-gray-500">Approved Budget</h3>
                <p class="text-2xl font-bold">
                    ₱ {{ number_format($approvedBudget,2) }}
                </p>
            </div>

        </div>

    </div>

</div>

</x-app-layout>