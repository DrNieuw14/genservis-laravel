<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Procurement Planning
    </h2>
</x-slot>

<div class="py-6">

<div class="max-w-7xl mx-auto px-6">

@if(session('success'))

<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">

{{ session('success') }}

</div>

@endif

<!-- Header -->

<div class="bg-white rounded-xl shadow">

<div class="border-b px-6 py-4 flex justify-between items-center">

<div>

<h2 class="text-2xl font-bold">

📄 Procurement Plan

</h2>

<p class="text-gray-500">

Working Document

</p>

</div>

<span class="px-4 py-2 rounded-full
@if($plan->status=='Draft')
bg-yellow-100 text-yellow-700
@elseif($plan->status=='Approved')
bg-green-100 text-green-700
@else
bg-blue-100 text-blue-700
@endif">

{{ $plan->status }}

</span>

</div>

<div class="p-6">

<div class="grid md:grid-cols-2 gap-6">

<div>

<label class="text-gray-500">

PPMP Number

</label>

<div class="font-bold text-xl">

{{ $plan->plan_number }}

</div>

</div>

<div>

<label class="text-gray-500">

Planning Year

</label>

<div class="font-bold">

{{ $plan->year }}

</div>

</div>

<div>

<label class="text-gray-500">

Department

</label>

<div class="font-bold">

{{ $plan->department->department_name }}

</div>

</div>

<div>

<label class="text-gray-500">

Allocated Budget

</label>

<div class="font-bold text-green-700">

₱ {{ number_format($plan->allocated_budget,2) }}

</div>

</div>

</div>

</div>

</div>

<!-- Header Card -->

</div>

    <!-- Budget Summary -->

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

        <!-- Allocated Budget -->

        <div class="bg-white rounded-xl shadow p-6">

            <div class="text-gray-500">
                Allocated Budget
            </div>

            <div class="text-3xl font-bold text-green-700 mt-2">

                ₱ {{ number_format($plan->allocated_budget,2) }}

            </div>

        </div>

        <!-- Planned Procurement -->

        <div class="bg-white rounded-xl shadow p-6">

            <div class="text-gray-500">
                Planned Procurement
            </div>

            <div class="text-3xl font-bold text-blue-700 mt-2">

                ₱0.00

            </div>

        </div>

        <!-- Remaining Budget -->

        <div class="bg-white rounded-xl shadow p-6">

            <div class="text-gray-500">
                Remaining Budget
            </div>

            <div class="text-3xl font-bold text-orange-600 mt-2">

                ₱ {{ number_format($plan->allocated_budget,2) }}

            </div>

        </div>

    </div>

    <!-- Procurement Toolbar -->

    <div class="bg-white rounded-xl shadow mt-6">

        <div class="p-5 flex flex-wrap gap-3">

            <button
                type="button"
                onclick="document.getElementById('addMaterialModal').classList.remove('hidden')"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">

                ➕ Add Material

            </button>

            <button
                disabled
                class="bg-gray-200 text-gray-500 px-5 py-2 rounded cursor-not-allowed">

                📥 Import Excel

            </button>

            <button
                disabled
                class="bg-gray-200 text-gray-500 px-5 py-2 rounded cursor-not-allowed">

                📤 Export Excel

            </button>

            <button
                disabled
                class="bg-gray-200 text-gray-500 px-5 py-2 rounded cursor-not-allowed">

                🖨 Print PPMP

            </button>

        </div>

    </div>

    <!-- Procurement Items -->

    <div class="bg-white rounded-xl shadow mt-6">

        <div class="p-6 border-b">

            <h2 class="text-xl font-bold">

                Procurement Items

            </h2>

            <p class="text-gray-500">

                Add materials that will be included in this Annual Procurement Plan.

            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Material</th>
                        <th class="px-4 py-3 text-left">Unit</th>
                        <th class="px-4 py-3 text-right">Unit Cost</th>
                        <th class="px-4 py-3 text-center">Q1</th>
                        <th class="px-4 py-3 text-center">Q2</th>
                        <th class="px-4 py-3 text-center">Q3</th>
                        <th class="px-4 py-3 text-center">Q4</th>
                        <th class="px-4 py-3 text-center">Annual Qty</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
            
            <tbody>

                 @forelse($plan->items as $item)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $item->material_name }}
                    </td>

                    <td class="px-4 py-3">
                        {{ optional($item->unit)->name }}
                    </td>

                    <td class="px-4 py-3 text-right">
                        ₱ {{ number_format($item->estimated_unit_cost,2) }}
                    </td>

                    <td class="px-4 py-3 text-center">{{ $item->q1 }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->q2 }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->q3 }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->q4 }}</td>

                    <td class="px-4 py-3 text-center">
                        {{ $item->annual_quantity }}
                    </td>

                    <td class="px-4 py-3 text-right">
                        ₱ {{ number_format($item->annual_cost,2) }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <button class="text-blue-600 hover:underline">Edit</button>
                        |
                        <button class="text-red-600 hover:underline">Delete</button>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="10" class="text-center text-gray-500 py-10">
                        No Procurement Items Yet
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

            
        </div>

    </div>

    

</div>

    <!-- Add Material Modal -->

    <div
        id="addMaterialModal"
        class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl">

            <div class="flex justify-between items-center border-b px-6 py-4">

                <h2 class="text-xl font-bold">

                    Add Procurement Item

                </h2>

                <button
                    type="button"
                    onclick="document.getElementById('addMaterialModal').classList.add('hidden')">

                    ✕

                </button>

            </div>

            <div class="p-6">

                <form
                    action="{{ route('procurement.plans.items.store', $plan->id) }}"
                    method="POST">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Material -->

                        <div class="md:col-span-2">

                            <label class="font-semibold">

                                Material

                            </label>

                            <select
                                name="material_id"
                                class="w-full border rounded mt-2">

                                <option value="">

                                    -- Select Material --

                                </option>

                                @foreach($materials as $material)

                                    <option value="{{ $material->id }}">

                                        {{ $material->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- Estimated Cost -->

                        <div>

                            <label class="font-semibold">

                                Estimated Unit Cost

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="estimated_unit_cost"
                                class="w-full border rounded mt-2">

                        </div>

                        <!-- Q1 -->

                        <div>

                            <label class="font-semibold">

                                Q1

                            </label>

                            <input
                                type="number"
                                name="q1"
                                value="0"
                                class="w-full border rounded mt-2">

                        </div>

                        <!-- Q2 -->

                        <div>

                            <label class="font-semibold">

                                Q2

                            </label>

                            <input
                                type="number"
                                name="q2"
                                value="0"
                                class="w-full border rounded mt-2">

                        </div>

                        <!-- Q3 -->

                        <div>

                            <label class="font-semibold">

                                Q3

                            </label>

                            <input
                                type="number"
                                name="q3"
                                value="0"
                                class="w-full border rounded mt-2">

                        </div>

                        <!-- Q4 -->

                        <div>

                            <label class="font-semibold">

                                Q4

                            </label>

                            <input
                                type="number"
                                name="q4"
                                value="0"
                                class="w-full border rounded mt-2">

                        </div>

                    </div>

                    <div class="border-t mt-6 pt-4 flex justify-end gap-3">

                        <button
                            type="button"
                            onclick="document.getElementById('addMaterialModal').classList.add('hidden')"
                            class="px-5 py-2 border rounded">

                            Cancel

                        </button>

                        <button
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">

                            Save Procurement Item

                        </button>

                    </div>

                </form>

            </div>

            <div class="border-t px-6 py-4 flex justify-end">

                <button
                    type="button"
                    onclick="document.getElementById('addMaterialModal').classList.add('hidden')"
                    class="px-5 py-2 border rounded">

                    Close

                </button>

            </div>

        </div>

    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const materialSelect = document.getElementById('materialSelect');

            if (!materialSelect) return;

            materialSelect.addEventListener('change', function () {

                const option = this.options[this.selectedIndex];

                document.getElementById('categoryDisplay').value =
                    option.dataset.category || '';

                document.getElementById('unitDisplay').value =
                    option.dataset.unit || '';

                document.getElementById('stockDisplay').value =
                    option.dataset.stock || '';

            });

        });

    </script>

</x-app-layout>