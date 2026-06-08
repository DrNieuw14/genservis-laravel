@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto mt-10">

    <div class="bg-white shadow-2xl rounded-2xl p-8">

        <h2 class="text-3xl font-bold mb-6 text-gray-800 flex items-center gap-2">
            📦 Material Request
        </h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-3 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/material-request">
            @csrf

            <!-- DEPARTMENT -->

            <div class="mb-6">

                <label class="block text-sm font-semibold mb-1">

                    Destination Department / Office

                </label>

                <p class="text-xs text-gray-500 mt-1">

                    Materials will be requested from the Centralized Stockroom and assigned to the selected department.

                </p>

                <select
                    name="department_id"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>

                    <option value="">
                        -- Select Department --
                    </option>

                    @foreach($departments as $department)

                        <option value="{{ $department->id }}">

                            {{ $department->department_name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- STOCKROOM INFO -->

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">

                <h3 class="font-bold text-blue-800 mb-2">
                    🏢 Source & Destination
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <p class="text-xs text-gray-500">
                            Source Location
                        </p>

                        <p class="font-semibold text-gray-800">
                            Centralized Stockroom
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Requested For
                        </p>

                        <p class="font-semibold text-gray-800">
                            Selected Department / Office
                        </p>
                    </div>

                </div>

            </div>

            <!-- CATEGORY FILTER -->

            <div class="mb-6">

                <label class="block text-sm font-semibold mb-1">
                    Material Category
                </label>

                <select id="category-filter"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none">

                    <option value="">
                        All Categories
                    </option>

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <!-- ITEMS CONTAINER -->
            <div id="items-container">

                <!-- ITEM ROW -->
                <div class="item-row bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">

                    <!-- MATERIAL -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1">
                            Material
                        </label>

                        <select name="material_id[]"
                            class="material-select w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                            required>

                            <option value="">-- Select Material --</option>

                            @foreach($materials as $material)

                                <option
                                    value="{{ $material->id }}"
                                    data-stock="{{ $material->quantity }}"
                                    data-category="{{ $material->category->name ?? 'N/A' }}"
                                    data-unit="{{ $material->unit->name ?? 'pcs' }}"
                                    data-threshold="{{ $material->threshold }}"
                                    {{ $material->quantity <= 0 ? 'disabled' : '' }}
                                >
                                    {{ $material->name }}
                                    — Stock: {{ $material->quantity }}

                                    @if($material->quantity <= 0)
                                        (Out of Stock)
                                    @endif

                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="material-info hidden bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4">

                        <h4 class="font-semibold text-blue-800 mb-2">
                            📦 Material Information
                        </h4>

                        <div class="grid grid-cols-2 gap-2 text-sm">

                            <div>
                                <span class="text-gray-500">Category:</span>
                                <span class="info-category font-medium">-</span>
                            </div>

                            <div>
                                <span class="text-gray-500">Unit:</span>
                                <span class="info-unit font-medium">-</span>
                            </div>

                            <div>
                                <span class="text-gray-500">Available:</span>
                                <span class="info-stock font-medium text-green-600">-</span>
                            </div>

                            <div>
                                <span class="text-gray-500">Status:</span>
                                <span class="info-status font-medium">-</span>
                            </div>

                        </div>

                    </div>

                    <!-- QUANTITY -->
                    <div class="mb-2">
                        <label class="block text-sm font-semibold mb-1">
                            Quantity
                        </label>

                        <input type="number"
                            name="quantity[]"
                            min="1"
                            class="quantity-input w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                            required>

                        <p class="stock-warning text-red-500 text-sm mt-1 hidden">
                            Quantity exceeds available stock.
                        </p>
                    </div>

                    <!-- REMOVE BUTTON -->
                    <button type="button"
                        class="remove-item bg-red-500 text-white px-3 py-1 rounded-lg text-sm mt-2 hidden">
                        Remove
                    </button>

                </div>

            </div>

           

            <!-- ADD ITEM -->
            <button type="button"
                id="add-item"
                class="mb-6 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">

                ➕ Add Another Material

            </button>

            <!-- REQUEST SUMMARY -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">

                <h3 class="font-bold text-blue-800 mb-3">
                    📋 Material Requisition Summary
                </h3>

                <p class="text-xs text-gray-500 mb-3">
                    Review all requested materials before submission.
                </p>

                <!-- ITEM LIST -->
                <div id="summary-items"
                    class="text-sm text-gray-700 space-y-1 mb-3">

                    <p class="text-gray-400">
                        No materials selected yet.
                    </p>

                </div>

                <!-- TOTALS -->
                <div class="border-t pt-3 text-sm">

                    <div class="flex justify-between">
                        <span>Total Items:</span>
                        <span id="summary-total-items">0</span>
                    </div>

                    <div class="flex justify-between font-semibold">
                        <span>Total Quantity:</span>
                        <span id="summary-total-qty">0</span>
                    </div>

                </div>

            </div>

            <!-- PURPOSE -->
            <div class="mb-4">

                <label class="block text-sm font-semibold mb-1">
                    Purpose of Request
                </label>

                <textarea
                    name="purpose"
                    rows="4"
                    placeholder="Example: Cleaning of ICT Laboratory"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                    required></textarea>

            </div>

            <!-- SUBMIT -->
            <button class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition">

                📨 Submit Material Requisition

            </button>

        </form>

    </div>
</div>

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

<style>

    .ts-control {
        border-radius: 0.75rem !important;
        border: 1px solid #d1d5db !important;
        padding: 0.75rem 1rem !important;
        min-height: 52px !important;
        box-shadow: none !important;
    }

    .ts-control input {
        font-size: 14px !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .ts-wrapper.single .ts-control {
        background: white !important;
    }

    .ts-control:focus-within {
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 2px rgba(96,165,250,0.3) !important;
    }

    .ts-dropdown {
        border-radius: 0.75rem !important;
        border: 1px solid #d1d5db !important;
        overflow: hidden;
    }

</style>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>

    // ✅ INIT TOMSELECT
        function initTomSelect(element) {

        const tom = new TomSelect(element, {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // ✅ STOCK CHECK
        element.addEventListener('change', function () {

            const selectedOption =
                element.options[element.selectedIndex];

            console.log(selectedOption);
            console.log(selectedOption.dataset);

            const stock =
                selectedOption.dataset.stock || 0;

            const row =
                element.closest('.item-row');

            const quantityInput =
                row.querySelector('.quantity-input');

            const infoCard = row.querySelector('.material-info');

            console.log('Info Card:', infoCard);

            if (!infoCard) {
                console.error('Material info card not found');
                return;
            }

            const category =
                selectedOption.dataset.category;

            const unit =
                selectedOption.dataset.unit;

            const threshold =
                parseInt(selectedOption.dataset.threshold || 0);

            const stockNumber =
                parseInt(stock);

            let status = 'Available';
            let statusColor = 'text-green-600';

            if(stockNumber <= 0){
                status = 'Out of Stock';
                statusColor = 'text-red-600';
            }
            else if(stockNumber <= threshold){
                status = 'Low Stock';
                statusColor = 'text-yellow-600';
            }

            if (infoCard) {
                infoCard.classList.remove('hidden');
            }

            if (infoCard) {

                infoCard.querySelector('.info-category').innerText = category;
                infoCard.querySelector('.info-unit').innerText = unit;
                infoCard.querySelector('.info-stock').innerText = stock + ' ' + unit;
                infoCard.querySelector('.info-status').innerText = status;

                infoCard.querySelector('.info-status').className =
                    'info-status font-medium ' + statusColor;
            }

            quantityInput.max = stock;

        });

    }

    // ✅ INITIALIZE FIRST SELECT
    document.querySelectorAll('.material-select').forEach(select => {
        initTomSelect(select);
    });

    // ✅ MATERIAL OPTIONS TEMPLATE
    const materialOptions = `
        <option value="">-- Select Material --</option>

        @foreach($materials as $material)

            <option
                value="{{ $material->id }}"
                data-stock="{{ $material->quantity }}"
                data-category="{{ $material->category->name ?? 'N/A' }}"
                data-unit="{{ $material->unit->name ?? 'pcs' }}"
                data-threshold="{{ $material->threshold }}"
                {{ $material->quantity <= 0 ? 'disabled' : '' }}
            >
                {{ $material->name }}
                — Stock: {{ $material->quantity }}

                @if($material->quantity <= 0)
                    (Out of Stock)
                @endif

            </option>

        @endforeach
    `;

    // ✅ ADD ITEM
    document.getElementById('add-item').addEventListener('click', function () {

        const container = document.getElementById('items-container');

        // CREATE CLEAN ROW
        const newRow = document.createElement('div');

        newRow.className =
        'item-row bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4';

        newRow.innerHTML = `

            <div class="mb-4">

                <label class="block text-sm font-semibold mb-1">
                    Material
                </label>

                <select
                    name="material_id[]"
                    class="material-select w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>

                    ${materialOptions}

                </select>

                    <div class="material-info hidden bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4">

                        <h4 class="font-semibold text-blue-800 mb-2">
                            📦 Material Information
                        </h4>

                            <div class="grid grid-cols-2 gap-2 text-sm">

                                <div>
                                    <span class="text-gray-500">Category:</span>
                                    <span class="info-category font-medium">-</span>
                                </div>

                                <div>
                                    <span class="text-gray-500">Unit:</span>
                                    <span class="info-unit font-medium">-</span>
                                </div>

                                <div>
                                    <span class="text-gray-500">Available:</span>
                                    <span class="info-stock font-medium text-green-600">-</span>
                                </div>

                                <div>
                                    <span class="text-gray-500">Status:</span>
                                    <span class="info-status font-medium">-</span>
                                </div>

                            </div>

                    </div>

            </div>

            <div class="mb-2">

                <label class="block text-sm font-semibold mb-1">
                    Quantity
                </label>

                <input type="number"
                    name="quantity[]"
                    min="1"
                    class="quantity-input w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>

                <p class="stock-warning text-red-500 text-sm mt-1 hidden">
                    Quantity exceeds available stock.
                </p>

            </div>

            <button type="button"
                class="remove-item bg-red-500 text-white px-3 py-1 rounded-lg text-sm mt-2">

                Remove

            </button>
        `;

        container.appendChild(newRow);

        // INIT TOMSELECT
        initTomSelect(
            newRow.querySelector('.material-select')
        );

    });

    // ✅ REMOVE ITEM
    document.addEventListener('click', function(e){

        if(e.target.classList.contains('remove-item')){

            e.target.closest('.item-row').remove();

        }

    });

</script>

<script>

    // ✅ LIVE QUANTITY VALIDATION
    document.addEventListener('input', function(e){

        if(e.target.classList.contains('quantity-input')){

            const input = e.target;

            const row = input.closest('.item-row');

            const warning =
                row.querySelector('.stock-warning');

            const max =
                parseInt(input.max || 0);

            const value =
                parseInt(input.value || 0);

            console.log(
                'Value:',
                value,
                'Max:',
                max
            );

            // ❌ EXCEEDS STOCK
            if(value > max){

                warning.classList.remove('hidden');

                input.classList.add(
                    'border-red-500',
                    'ring-2',
                    'ring-red-300'
                );

            }

            // ✅ VALID
            else {

                warning.classList.add('hidden');

                input.classList.remove(
                    'border-red-500',
                    'ring-2',
                    'ring-red-300'
                );

            }

        }


    });

</script>

<script>

document.addEventListener('change', function(e){

    if(!e.target.classList.contains('material-select')) {
        return;
    }

    const currentSelect = e.target;

    const currentValue = currentSelect.value;

    if(currentValue === '') {
        return;
    }

    let duplicateFound = false;

    document.querySelectorAll('.material-select').forEach(select => {

        if(
            select !== currentSelect &&
            select.value === currentValue
        ){
            duplicateFound = true;
        }

    });

    if(duplicateFound){

        alert('This material is already selected.');

        if(currentSelect.tomselect){
            currentSelect.tomselect.clear();
        }else{
            currentSelect.value = '';
        }

    }

});

</script>

<script>

    // ✅ UPDATE SUMMARY PANEL
    function updateSummary() {

        const summaryItems =
            document.getElementById('summary-items');

        const totalItems =
            document.getElementById('summary-total-items');

        const totalQty =
            document.getElementById('summary-total-qty');

        let html = '';

        let itemCount = 0;

        let qtyCount = 0;

        document.querySelectorAll('.item-row').forEach(row => {

            const select =
                row.querySelector('.material-select');

            const quantity =
                row.querySelector('.quantity-input');

            const materialName =
                select.options[select.selectedIndex]?.text || '';

            const qty =
                parseInt(quantity.value || 0);

            // SKIP EMPTY
            if(select.value && qty > 0){

                itemCount++;

                qtyCount += qty;

                html += `
                    <div class="flex justify-between">
                        <span>${materialName}</span>
                        <span>x${qty}</span>
                    </div>
                `;
            }

        });

        // EMPTY
        if(itemCount === 0){

            html = `
                <p class="text-gray-400">
                    No materials selected yet.
                </p>
            `;
        }

        summaryItems.innerHTML = html;

        totalItems.innerText = itemCount;

        totalQty.innerText = qtyCount;
    }

    // ✅ AUTO UPDATE
    document.addEventListener('change', updateSummary);

    document.addEventListener('input', updateSummary);

</script>

<script>

document.getElementById('category-filter')
.addEventListener('change', function () {

    const categoryId = this.value;

    document.querySelectorAll('.material-select').forEach(select => {

        const tom = select.tomselect;

        if (!tom) return;

        tom.clear();

        tom.clearOptions();

        tom.addOption({
            value: '',
            text: '-- Select Material --'
        });

        @foreach($materials as $material)

            if (
                categoryId === '' ||
                categoryId == '{{ $material->category_id }}'
            ) {

                tom.addOption({
                    value: '{{ $material->id }}',
                    text: '{{ $material->name }} — Stock: {{ $material->quantity }}'
                });

            }

        @endforeach

        tom.refreshOptions(false);

    });

});

</script>

@endsection