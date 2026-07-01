<div class="bg-white rounded-xl shadow p-6 mt-6">

        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-800">
                📊 Budget Utilization
            </h3>

            <span class="text-sm font-semibold text-gray-600">
                {{ $plan->budget_utilization_percentage }}%
            </span>
        </div>

        @php
            $percentage = min($plan->budget_utilization_percentage, 100);

            $barColor = 'bg-green-500';

            if ($plan->budget_utilization_percentage >= 90) {
                $barColor = 'bg-red-600';
            } elseif ($plan->budget_utilization_percentage >= 60) {
                $barColor = 'bg-yellow-500';
            }
        @endphp

        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">

            <div
                class="{{ $barColor }} h-4 transition-all duration-500"
                style="width: {{ $percentage }}%;">
            </div>

        </div>

        <div class="flex justify-between mt-3 text-sm text-gray-600">

            <span>
                ₱{{ number_format($plan->total_planned_cost,2) }}
                Used
            </span>

            <span>
                ₱{{ number_format($plan->allocated_budget,2) }}
                Budget
            </span>

        </div>

    </div>