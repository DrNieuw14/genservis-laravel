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

            <!-- MATERIAL -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Material</label>
                <select id="material-select"
                    name="material_id"
                    class="w-full border rounded-lg p-2"
                    required>
                    <option value="">-- Select Material --</option>
                    
                    @foreach($materials as $material)

                        <option
                            value="{{ $material->id }}"
                            data-stock="{{ $material->quantity }}"
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

            <div id="stock-preview"
                class="mt-2 text-sm font-semibold text-gray-600 hidden">
            </div>

            </div>

            <!-- QUANTITY -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Quantity</label>
                <input type="number" name="quantity" min="1"
                    class="w-full border rounded-lg p-2" required>
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

            <button class="w-full bg-green-600 text-white py-2 rounded-lg">
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

    const select = new TomSelect("#material-select",{
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },

        onChange: function(value) {

            const option = this.getOption(value);

            const originalOption =
                document.querySelector(`option[value="${value}"]`);

            const stock = originalOption.dataset.stock;
            const threshold = originalOption.dataset.threshold;

            const preview =
                document.getElementById('stock-preview');

            preview.classList.remove('hidden');

            // LOW STOCK
            if (parseInt(stock) <= parseInt(threshold)) {

                preview.innerHTML =
                    `⚠ Low Stock`;

                preview.className =
                    'mt-2 text-sm font-semibold text-yellow-600';

            }

            // OUT OF STOCK
            else if (parseInt(stock) <= 0) {

                preview.innerHTML =
                    `❌ Out of Stock`;

                preview.className =
                    'mt-2 text-sm font-semibold text-red-600';

            }

            // NORMAL STOCK
            else {

                preview.innerHTML =
                `✅ Available`;

                preview.className =
                    'mt-2 text-sm font-semibold text-green-600';
            }
        }
    });

</script>

@endsection