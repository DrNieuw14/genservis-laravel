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

            <!-- ITEMS CONTAINER -->
            <div id="items-container">

                <!-- ITEM ROW -->
                <div class="item-row border rounded-xl p-4 mb-4 bg-gray-50">

                    <!-- MATERIAL -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1">
                            Material
                        </label>

                        <select name="material_id[]"
                            class="material-select w-full border rounded-lg p-2"
                            required>

                            <option value="">-- Select Material --</option>

                            @foreach($materials as $material)

                                <option
                                    value="{{ $material->id }}"
                                    data-stock="{{ $material->quantity }}"
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

                    <!-- QUANTITY -->
                    <div class="mb-2">
                        <label class="block text-sm font-semibold mb-1">
                            Quantity
                        </label>

                        <input type="number"
                            name="quantity[]"
                            min="1"
                            class="quantity-input w-full border rounded-lg p-2"
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
                    📋 Request Summary
                </h3>

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
                    class="w-full border rounded-lg p-2"
                    required></textarea>

            </div>

            <!-- SUBMIT -->
            <button class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition">

                Submit Request

            </button>

        </form>

    </div>
</div>

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

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

            const stock =
                selectedOption.dataset.stock || 0;

            const row =
                element.closest('.item-row');

            const quantityInput =
                row.querySelector('.quantity-input');

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
            'item-row border rounded-xl p-4 mb-4 bg-gray-50';

        newRow.innerHTML = `

            <div class="mb-4">

                <label class="block text-sm font-semibold mb-1">
                    Material
                </label>

                <select
                    name="material_id[]"
                    class="material-select w-full border rounded-lg p-2"
                    required>

                    ${materialOptions}

                </select>

            </div>

            <div class="mb-2">

                <label class="block text-sm font-semibold mb-1">
                    Quantity
                </label>

                <input type="number"
                    name="quantity[]"
                    min="1"
                    class="quantity-input w-full border rounded-lg p-2"
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

    // ✅ PREVENT DUPLICATE MATERIALS
    document.addEventListener('change', function(e){

        if(e.target.classList.contains('material-select')){

            const selectedValues = [];

            document.querySelectorAll('.material-select').forEach(select => {

                const value = select.value;

                // SKIP EMPTY
                if(value === '') return;

                // DUPLICATE DETECTED
                if(selectedValues.includes(value)){

                    alert('This material is already selected.');

                    select.tomselect.clear();

                } else {

                    selectedValues.push(value);

                }

            });

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

@endsection