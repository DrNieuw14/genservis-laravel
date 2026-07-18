<div
    id="addMaterialModal"
    class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">

        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">

            <h2
                id="procurementModalTitle"
                class="text-xl font-bold">

                Add Procurement Item

            </h2>

            <button
                type="button"
                onclick="closeProcurementModal()"
                class="text-gray-500 hover:text-red-600 text-xl">

                ✕

            </button>

        </div>

        <!-- Modal Body -->
        <div class="p-6">

            <form
                id="procurementItemForm"
                action="{{ route('procurement.plans.items.store', $plan->id) }}"
                method="POST">

                @csrf

                <input
                    type="hidden"
                    id="formMethod"
                    name="_method"
                    value="POST">

                <input
                    type="hidden"
                    id="procurementItemId"
                    name="item_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Material -->
                    <div class="md:col-span-2">

                        <label class="font-semibold">
                            Material
                        </label>

                        <select
                            id="material_id"
                            name="material_id"
                            onchange="loadMaterialDetails(this.value)"
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

                        <button
                            type="button"
                            onclick="toggleNewMaterialForm()"
                            class="text-sm text-blue-600 hover:underline mt-2">

                            + Can't find it? Add New Material

                        </button>

                    </div>

                    <!-- New Material Inline Form -->
                    <div class="md:col-span-2">

                        <div
                            id="newMaterialForm"
                            class="hidden rounded-lg border border-green-200 bg-green-50 p-4">

                            <h4 class="font-semibold text-green-800 mb-3">
                                ➕ Add New Material
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                <div class="md:col-span-2">

                                    <label class="text-sm font-semibold">
                                        Material Name
                                    </label>

                                    <input
                                        id="newMaterialName"
                                        type="text"
                                        class="w-full border rounded mt-1">

                                </div>

                                <div>

                                    <label class="text-sm font-semibold">
                                        Category
                                    </label>

                                    <select id="newMaterialCategory" class="w-full border rounded mt-1">

                                        <option value="">-- Select Category --</option>

                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach

                                    </select>

                                </div>

                                <div>

                                    <label class="text-sm font-semibold">
                                        Unit
                                    </label>

                                    <select id="newMaterialUnit" class="w-full border rounded mt-1">

                                        <option value="">-- Select Unit --</option>

                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach

                                    </select>

                                </div>

                                <div class="md:col-span-2">

                                    <label class="text-sm font-semibold">
                                        Procurement Classification
                                    </label>

                                    <select id="newMaterialClassification" class="w-full border rounded mt-1">

                                        <option value="">-- Select Classification --</option>

                                        @foreach($classifications as $classification)
                                            <option value="{{ $classification->id }}">
                                                {{ $classification->code }} — {{ $classification->sub_category_c }} ({{ $classification->uacs_code }})
                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>

                            <div id="newMaterialError" class="hidden text-sm text-red-600 mt-3"></div>

                            <div class="mt-4 flex justify-end gap-2">

                                <button
                                    type="button"
                                    onclick="toggleNewMaterialForm()"
                                    class="px-4 py-2 border rounded text-sm">

                                    Cancel

                                </button>

                                <button
                                    type="button"
                                    onclick="createNewMaterial()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">

                                    Create &amp; Select

                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- Material Information -->
                    <div class="md:col-span-2">

                        <div
                            id="materialInfoCard"
                            class="hidden rounded-lg border border-blue-200 bg-blue-50 p-4">

                            <h4 class="font-semibold text-blue-800 mb-3">
                                📦 Material Information
                            </h4>

                            <div class="grid grid-cols-2 gap-3 text-sm">

                                <div>

                                    <p class="text-gray-500">
                                        Category
                                    </p>

                                    <p
                                        id="materialCategory"
                                        class="font-medium">

                                        —

                                    </p>

                                </div>

                                <div>

                                    <p class="text-gray-500">
                                        Unit
                                    </p>

                                    <p
                                        id="materialUnit"
                                        class="font-medium">

                                        —

                                    </p>

                                </div>

                                <div>

                                    <p class="text-gray-500">
                                        Current Stock
                                    </p>

                                    <p
                                        id="materialStock"
                                        class="font-medium">

                                        —

                                    </p>

                                </div>

                                <div>

                                    <p class="text-gray-500">
                                        Reorder Level
                                    </p>

                                    <p
                                        id="materialThreshold"
                                        class="font-medium">

                                        —

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Assign Classification (shown only when the selected material has none yet) -->
                    <div class="md:col-span-2">

                        <div
                            id="assignClassificationCard"
                            class="hidden rounded-lg border border-amber-200 bg-amber-50 p-4">

                            <h4 class="font-semibold text-amber-800 mb-2">
                                🏷 This material has no classification yet
                            </h4>

                            <p class="text-sm text-amber-700 mb-3">
                                Pick one below - it will be saved to the material and this item will be added together.
                            </p>

                            <select
                                id="assignClassificationSelect"
                                name="assign_classification_id"
                                class="w-full border rounded">

                                <option value="">-- Select Classification --</option>

                                @foreach($classifications as $classification)
                                    <option value="{{ $classification->id }}">
                                        {{ $classification->code }} — {{ $classification->sub_category_c }} ({{ $classification->uacs_code }})
                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <!-- Estimated Cost -->
                    <div>

                        <label class="font-semibold">
                            Estimated Unit Cost
                        </label>

                        <input
                            id="estimated_unit_cost"
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
                            id="q1"
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
                            id="q2"
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
                            id="q3"
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
                            id="q4"
                            type="number"
                            name="q4"
                            value="0"
                            class="w-full border rounded mt-2">

                    </div>

                    <!-- Mode of Procurement -->
                    <div class="md:col-span-2">

                        <label class="font-semibold">
                            Mode of Procurement
                        </label>

                        <select
                            id="procurement_method"
                            name="procurement_method"
                            class="w-full border rounded mt-2"
                            required>

                            <option value="">-- Select Mode of Procurement --</option>
                            <option value="COMPETITIVE BIDDING">Competitive Bidding</option>
                            <option value="SHOPPING">Shopping</option>
                            <option value="DIRECT CONTRACTING">Direct Contracting</option>
                            <option value="NP- SMALL VALUE PROCUREMENT">NP - Small Value Procurement</option>
                            <option value="NP- AGENCY TO AGENCY">NP - Agency to Agency</option>

                        </select>

                    </div>

                    <!-- Reason for Change (edit mode only) -->
                    <div id="editReasonField" class="md:col-span-2 hidden">

                        <label class="font-semibold">
                            Reason for Change (optional)
                        </label>

                        <textarea
                            id="edit_reason"
                            name="edit_reason"
                            rows="2"
                            placeholder="Let the person who added this item know why it changed..."
                            class="w-full border rounded mt-2"></textarea>

                    </div>

                </div>

                <!-- Procurement Summary -->
                <div class="mt-6">

                    <div class="rounded-lg border border-green-200 bg-green-50 p-4">

                        <h4 class="font-semibold text-green-800 mb-3">
                            💰 Procurement Summary
                        </h4>

                        <div class="grid grid-cols-2 gap-4">

                            <div>

                                <p class="text-sm text-gray-600">
                                    Total Quantity
                                </p>

                                <p
                                    id="totalQuantityDisplay"
                                    class="text-2xl font-bold text-gray-800">

                                    0

                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-gray-600">
                                    Estimated Total Cost
                                </p>

                                <hr class="my-4">

<div class="grid grid-cols-2 gap-4">

    <div>

        <p class="text-sm text-gray-600">
            Remaining Budget
        </p>

        <p
            id="remainingBudgetDisplay"
            class="text-xl font-bold text-blue-700">

            ₱{{ number_format($plan->remaining_budget_computed, 2) }}

        </p>

    </div>

    <div>

        <p class="text-sm text-gray-600">
            Budget Status
        </p>

        <p
            id="budgetStatusDisplay"
            class="text-xl font-bold text-green-700">

            🟢 Within Budget

        </p>

    </div>

</div>

                                <p
                                    id="estimatedTotalDisplay"
                                    class="text-2xl font-bold text-green-700">

                                    ₱0.00

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <div class="border-t mt-6 pt-4 flex justify-end gap-3">

                    <button
                        type="button"
                        onclick="closeProcurementModal()"
                        class="px-5 py-2 border rounded">

                        Cancel

                    </button>

                    <button
                        id="procurementSubmitButton"
                        type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">

                        Save Procurement Item

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>