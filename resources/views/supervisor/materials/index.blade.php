@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- HEADER -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📦 Materials Inventory
        </h2>

        <div class="flex flex-wrap gap-3">

            <a href="{{ route('materials.create') }}"
               class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-5 py-3 rounded-xl shadow-lg hover:scale-105 transition">

                ➕ Add Material
            </a>

            <a href="{{ route('materials.import.form') }}"
                class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-xl shadow-lg">

                📥 Import Inventory

            </a>

            <a href="{{ route('supervisor.inventory.movements.index') }}"
                class="bg-gray-100 text-blue-700 px-5 py-3 rounded-xl hover:bg-gray-200 transition font-semibold">

                📊 Inventory Logs
            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl mb-6 text-lg">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-6 text-lg">
            {{ session('error') }}
        </div>

    @endif

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">

    <!-- TOTAL -->
    <a href="{{ route('materials.index') }}"
    class="
    rounded-2xl p-5 block hover:scale-105 transition
    {{ !request('status')
        ? 'bg-blue-600 text-white shadow-xl ring-4 ring-blue-200'
        : 'bg-gray-50 border border-gray-200 text-gray-800'
    }}">
        <h3 class="text-sm">Total Materials</h3>
        <p class="text-3xl font-bold mt-2">
            {{ $totalMaterials }}
        </p>
    </a>

    <!-- LOW -->
    <a href="{{ route('materials.index',['status'=>'low']) }}"
    class="
    rounded-2xl shadow-xl p-5 hover:scale-105 transition block
    {{ request('status') == 'low'
        ? 'bg-yellow-600 text-white ring-4 ring-gray-300'
        : 'bg-yellow-500 text-white'
    }}">
        <h3 class="text-sm">Low Stock</h3>
        <p class="text-3xl font-bold mt-2">{{ $lowStock }}</p>
    </a>

    <!-- CRITICAL -->
    <a href="{{ route('materials.index',['status'=>'critical']) }}"
    class="
    rounded-2xl shadow-xl p-5 hover:scale-105 transition block
    {{ request('status') == 'critical'
        ? 'bg-red-700 text-white ring-4 ring-gray-300 animate-pulse'
        : 'bg-red-600 text-white'
    }}">
        <h3 class="text-sm">Critical Stock</h3>
        <p class="text-3xl font-bold mt-2">{{ $criticalStock }}</p>
    </a>

    <!-- OUT -->
    <a href="{{ route('materials.index',['status'=>'out']) }}"
    class="
    rounded-2xl shadow-xl p-5 hover:scale-105 transition block
    {{ request('status') == 'out'
        ? 'bg-gray-900 text-white ring-4 ring-gray-300'
        : 'bg-slate-700 text-white'
    }}">
        <h3 class="text-sm">Out of Stock</h3>
        <p class="text-3xl font-bold mt-2">{{ $outOfStock }}</p>
    </a>

    <!-- EXPIRING -->
    <a href="{{ route('materials.index',['status'=>'expiring']) }}"
    class="
    rounded-2xl shadow-xl p-5 hover:scale-105 transition block
    {{ request('status') == 'expiring'
        ? 'bg-orange-700 text-white ring-4 ring-gray-300'
        : 'bg-orange-500 text-white'
    }}">
        <h3 class="text-sm">Expiring Soon</h3>
        <p class="text-3xl font-bold mt-2">{{ $expiringSoon }}</p>
    </a>

    <!-- EXPIRED -->
    <a href="{{ route('materials.index',['status'=>'expired']) }}"
    class="
    rounded-2xl shadow-xl p-5 hover:scale-105 transition block
    {{ request('status') == 'expired'
        ? 'bg-red-900 text-white ring-4 ring-gray-300'
        : 'bg-red-800 text-white'
    }}">
        <h3 class="text-sm">Expired Batches</h3>
        <p class="text-3xl font-bold mt-2">{{ $expiredItems }}</p>
    </a>

</div>

    <!-- CATEGORY SUMMARY -->

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3 mb-6">

        @foreach($categorySummary as $category)

            <a href="{{ route('materials.index', ['category_id' => $category->id]) }}"
                class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center hover:shadow-md hover:scale-105 transition block
                {{ request('category_id') == $category->id ? 'ring-4 ring-blue-500' : '' }}">

                <div class="text-sm font-semibold text-gray-600">
                    {{ $category->name }}
                </div>

                <div class="text-2xl font-bold text-blue-600 mt-2">
                    {{ $category->materials_count }}
                </div>

                <div class="text-xs text-gray-400">
                    Items
                </div>

            </a>

        @endforeach

    </div>
    <div class="border rounded-lg p-4 bg-gray-50 mb-6">

        <form method="GET" action="{{ route('materials.index') }}">

            <div class="flex flex-col md:flex-row gap-3">

                <!-- SEARCH -->
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search materials..."
                    class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                <!-- DEPARTMENT FILTER -->
                <select name="department_id"
                        class="border rounded-xl p-3 focus:ring-2 focus:ring-green-400">

                    <option value="">
                        All Departments
                    </option>

                    @foreach($departments as $department)

                        <option value="{{ $department->id }}"
                            {{ request('department_id') == $department->id ? 'selected' : '' }}>

                            {{ $department->department_name }}

                        </option>

                    @endforeach

                </select>

                <!-- CATEGORY FILTER -->
                <select name="category_id"
                        class="border rounded-xl p-3 focus:ring-2 focus:ring-purple-400">

                    <option value="">
                        All Categories
                    </option>

                    @foreach(($categories ?? []) as $category)

                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

                <!-- BUTTON -->
                <button
                    class="bg-blue-600 text-white px-6 rounded-xl hover:bg-blue-700 transition">

                    Filter

                </button>

            </div>

        </form>

    </div>

    <!-- BULK DEPARTMENT REASSIGNMENT -->
    <form id="bulk-assign-form" method="POST" action="{{ route('materials.bulk-assign-department') }}">

        @csrf

        <div id="bulk-action-bar"
             class="hidden bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex flex-wrap items-center gap-4">

            <span class="font-semibold text-blue-800">
                <span id="selected-count">0</span> material(s) selected
            </span>

            <select name="department_id" required
                    class="border rounded-xl p-2 flex-1 min-w-[200px] max-w-xs">

                <option value="">Assign to department...</option>

                @foreach($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->department_name }}
                    </option>
                @endforeach

            </select>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-semibold shadow transition">

                🏢 Assign Department

            </button>

            <button type="button" id="clear-selection-btn"
                    class="text-gray-500 hover:text-gray-700 text-sm underline">

                Clear selection

            </button>

        </div>

    </form>

    <!-- BULK CLASSIFICATION ASSIGNMENT -->
    <form id="bulk-assign-classification-form" method="POST" action="{{ route('materials.bulk-assign-classification') }}">

        @csrf

        <div id="bulk-classification-bar"
             class="hidden bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex flex-wrap items-center gap-4">

            <span class="font-semibold text-amber-800">
                <span id="selected-count-classification">0</span> material(s) selected
            </span>

            <select name="classification_id" required
                    class="border rounded-xl p-2 flex-1 min-w-[200px] max-w-xs">

                <option value="">Assign classification...</option>

                @foreach($classifications as $classification)
                    <option value="{{ $classification->id }}">
                        {{ $classification->code }} — {{ $classification->sub_category_c }} ({{ $classification->uacs_code }})
                    </option>
                @endforeach

            </select>

            <button type="submit"
                    class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-xl font-semibold shadow transition">

                🏷 Assign Classification

            </button>

            <button type="button" id="clear-selection-btn-classification"
                    class="text-gray-500 hover:text-gray-700 text-sm underline">

                Clear selection

            </button>

        </div>

    </form>

    <!-- TABLE -->
    <div class="border rounded-lg overflow-hidden">


        <table class="min-w-full">


            <thead class="bg-gray-100">

                <tr>

                    <th class="p-4">
                        <input type="checkbox" id="select-all-checkbox" class="w-4 h-4">
                    </th>

                    <th class="p-4 text-left">Name</th>

                    <th class="p-4 text-left">Department</th>

                    <th class="p-4 text-left">Category</th>

                    <th class="p-4 text-left">Unit</th>

                    <th class="p-4 text-left">Stock</th>

                    <th class="p-4 text-left">Created By</th>

                    <th class="p-4 text-left">Actions</th>

                </tr>

            </thead>


            <tbody>

                @forelse($materials as $material)

                    <tr class="border-b hover:bg-gray-50 transition">

                        <td class="p-4">
                            <input type="checkbox" class="material-checkbox w-4 h-4" value="{{ $material->id }}">
                        </td>

                        <td class="p-4 font-medium">
                            {{ $material->name }}
                        </td>

                        
                        <td class="p-4">
                            {{ $material->department->department_name ?? '-' }}
                        </td>
                        

                        <td class="p-4">
                            {{ $material->category->name ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ $material->unit->name ?? '-' }}
                        </td>

                        <td class="p-4">

                            @if($material->quantity <= 0)

                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm">
                                    ❌ Out of Stock
                                </span>

                            @elseif($material->quantity <= 5)

                                                       
                            <span class="inline-flex items-center gap-1 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap">

                                🔥 Critical

                                <span class="bg-white text-red-600 px-2 py-0.5 rounded-full text-xs font-bold">
                                    {{ $material->quantity }}
                                </span>

                            </span>

                            @elseif($material->quantity <= $material->threshold)

                                <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-sm">
                                    ⚠️ Low ({{ $material->quantity }})
                                </span>

                            @else

                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                                    {{ $material->quantity }}
                                </span>

                            @endif

                        </td>

                        <td class="p-4">
                            {{ $material->creator->username ?? 'Unknown' }}
                        </td>

                    <td class="p-4 whitespace-nowrap">

                        <div class="flex flex-wrap gap-2">


                            <!-- VIEW -->
                            <a href="{{ route('materials.show', $material->id) }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-lg text-sm shadow-md transition flex items-center gap-1">

                                🔍 Details

                            </a>

                            <!-- EDIT -->
                            <a href="{{ route('materials.edit', $material->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">

                                ✏️ Edit
                            </a>

                            <!-- RESTOCK -->
                            <a href="{{ route('materials.restock.form', $material->id) }}"
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">

                                📦 Restock
                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('materials.destroy', $material->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="button"
                                    class="delete-material-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">

                                    🗑 Delete

                                </button>

                            </form>
                                </div>

                        </td>
                       
                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="text-center text-gray-500 py-8">

                            No materials found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-material-btn').forEach(button => {

        button.addEventListener('click', function () {

            const form = this.closest('form');

            console.log('Delete button clicked');

            Swal.fire({
                title: 'Delete Material?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                console.log(result);

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        });

    });

    // ── Bulk Department Reassignment ──

    const bulkForm = document.getElementById('bulk-assign-form');
    const bulkBar = document.getElementById('bulk-action-bar');
    const selectedCountEl = document.getElementById('selected-count');
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const clearSelectionBtn = document.getElementById('clear-selection-btn');

    const classificationForm = document.getElementById('bulk-assign-classification-form');
    const classificationBar = document.getElementById('bulk-classification-bar');
    const selectedCountClassificationEl = document.getElementById('selected-count-classification');
    const clearSelectionClassificationBtn = document.getElementById('clear-selection-btn-classification');

    function materialCheckboxes() {
        return Array.from(document.querySelectorAll('.material-checkbox'));
    }

    function updateBulkBar() {
        const checked = materialCheckboxes().filter(cb => cb.checked);
        selectedCountEl.textContent = checked.length;
        bulkBar.classList.toggle('hidden', checked.length === 0);
        selectedCountClassificationEl.textContent = checked.length;
        classificationBar.classList.toggle('hidden', checked.length === 0);
    }

    materialCheckboxes().forEach(cb => cb.addEventListener('change', updateBulkBar));

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            materialCheckboxes().forEach(cb => cb.checked = selectAllCheckbox.checked);
            updateBulkBar();
        });
    }

    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', function () {
            materialCheckboxes().forEach(cb => cb.checked = false);
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkBar();
        });
    }

    if (clearSelectionClassificationBtn) {
        clearSelectionClassificationBtn.addEventListener('click', function () {
            materialCheckboxes().forEach(cb => cb.checked = false);
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkBar();
        });
    }

    if (bulkForm) {
        bulkForm.addEventListener('submit', function (e) {

            e.preventDefault();

            const checked = materialCheckboxes().filter(cb => cb.checked);

            if (checked.length === 0) {
                return;
            }

            const departmentSelect = bulkForm.querySelector('select[name="department_id"]');
            const departmentName = departmentSelect.options[departmentSelect.selectedIndex]?.text || 'this department';

            Swal.fire({
                title: 'Reassign Department?',
                text: `Move ${checked.length} material(s) to "${departmentName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Reassign',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {

                    bulkForm.querySelectorAll('input[name="material_ids[]"]').forEach(el => el.remove());

                    checked.forEach(cb => {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'material_ids[]';
                        hidden.value = cb.value;
                        bulkForm.appendChild(hidden);
                    });

                    bulkForm.submit();

                }

            });

        });
    }

    if (classificationForm) {
        classificationForm.addEventListener('submit', function (e) {

            e.preventDefault();

            const checked = materialCheckboxes().filter(cb => cb.checked);

            if (checked.length === 0) {
                return;
            }

            const classificationSelect = classificationForm.querySelector('select[name="classification_id"]');
            const classificationLabel = classificationSelect.options[classificationSelect.selectedIndex]?.text || 'this classification';

            Swal.fire({
                title: 'Assign Classification?',
                text: `Classify ${checked.length} material(s) as "${classificationLabel}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Assign',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {

                    classificationForm.querySelectorAll('input[name="material_ids[]"]').forEach(el => el.remove());

                    checked.forEach(cb => {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'material_ids[]';
                        hidden.value = cb.value;
                        classificationForm.appendChild(hidden);
                    });

                    classificationForm.submit();

                }

            });

        });
    }

});
</script>
@endpush