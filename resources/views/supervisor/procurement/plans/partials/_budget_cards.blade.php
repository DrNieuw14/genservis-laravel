<!-- Budget Utilization -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

        <!-- Allocated Budget -->

        <div class="bg-white rounded-xl shadow p-6">

            <div class="text-gray-500">
                Allocated Budget
            </div>

            <div class="text-3xl font-bold text-green-700 mt-2">

                ₱ {{ number_format($plan->allocated_budget, 2) }}

            </div>

        </div>

        <!-- Planned Procurement -->

        <div class="bg-white rounded-xl shadow p-6">

            <div class="text-gray-500">
                Planned Procurement
            </div>

            <div class="text-3xl font-bold text-blue-700 mt-2">

                ₱ {{ number_format($plan->total_planned_cost, 2) }}

            </div>

        </div>

        <!-- Remaining Budget -->

        <div class="bg-white rounded-xl shadow p-6">

            <div class="text-gray-500">
                Remaining Budget
            </div>

            <div class="text-3xl font-bold text-orange-600 mt-2">

                ₱ {{ number_format($plan->remaining_budget_computed, 2) }}

            </div>

        </div>

    </div>