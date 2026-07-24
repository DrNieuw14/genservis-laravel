@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE TITLE -->
    <div class="mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            ✏️ Edit Material
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Update inventory material details.
        </p>

    </div>

    <!-- ERRORS -->
    @if ($errors->any())

        <div class="bg-red-500 text-white p-4 rounded-xl mb-6">

            <ul class="list-disc ml-5">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <!-- FORM -->
    <form action="{{ route('materials.update', $material->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- NAME -->
            <div>

                <label class="block text-gray-700 font-semibold mb-2">
                    Material Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ $material->name }}"
                       class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

            </div>

            <!-- IMAGE -->
            <div class="md:col-span-2">

                <label class="block text-gray-700 font-semibold mb-2">
                    Item Photo
                </label>

                <p class="text-sm text-gray-500 mb-2">
                    Helps requesters visually confirm this is the item they need. Optional.
                </p>

                <div class="flex items-center gap-4">

                    <img
                        id="image-preview"
                        src="{{ $material->image_url ?? '' }}"
                        class="{{ $material->image_url ? '' : 'hidden' }} w-24 h-24 object-cover rounded-xl border"
                        alt="Preview">

                    <div class="flex-1">

                        <input type="file"
                               name="image"
                               accept="image/*"
                               onchange="previewMaterialImage(this)"
                               class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                        @if($material->image_url)

                            <label class="inline-flex items-center gap-2 mt-2 text-sm text-red-600">
                                <input type="checkbox" name="remove_image" value="1">
                                Remove current photo
                            </label>

                        @endif

                    </div>

                </div>

            </div>

            <!-- CATEGORY -->
            <div>

                <div class="flex items-center justify-between mb-2">

                    <label class="block text-gray-700 font-semibold">
                        Category
                    </label>

                    <button type="button"
                            onclick="openCategoryQuickAddModal()"
                            class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                        ➕ Add New Category
                    </button>

                </div>

                <select name="category_id"
                        id="categorySelect"
                        class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}"
                            {{ $material->category_id == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- UNIT -->
            <div>

                <div class="flex items-center justify-between mb-2">

                    <label class="block text-gray-700 font-semibold">
                        Unit
                    </label>

                    <button type="button"
                            onclick="openUnitQuickAddModal()"
                            class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                        ➕ Add New Unit
                    </button>

                </div>

                <select name="unit_id"
                        id="unitSelect"
                        class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                    @foreach($units as $unit)

                        <option value="{{ $unit->id }}"
                            {{ $material->unit_id == $unit->id ? 'selected' : '' }}>

                            {{ $unit->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- DEPARTMENT -->
            <div>

                <div class="flex items-center justify-between mb-2">

                    <label class="block text-gray-700 font-semibold">
                        Department
                    </label>

                    <button type="button"
                            onclick="openDepartmentQuickAddModal()"
                            class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                        ➕ Add New Department
                    </button>

                </div>

                <select name="department_id"
                        id="departmentSelect"
                        class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                    @foreach($departments as $department)

                        <option value="{{ $department->id }}"
                            {{ $material->department_id == $department->id ? 'selected' : '' }}>

                            {{ $department->department_name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- PROCUREMENT CLASSIFICATION -->
            <div class="md:col-span-2">

                <label class="block text-gray-700 font-semibold mb-2">
                    Procurement Classification
                </label>

                <select name="classification_id"
                        class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                    <option value="">-- Unclassified --</option>

                    @foreach($classifications as $classification)

                        <option value="{{ $classification->id }}"
                            {{ $material->classification_id == $classification->id ? 'selected' : '' }}>

                            {{ $classification->code }} — {{ $classification->sub_category_c }} ({{ $classification->uacs_code }})

                        </option>

                    @endforeach

                </select>

                <p class="text-sm text-gray-500 mt-2">
                    Required before this material can be added to a Procurement Plan (PPMP).
                </p>

            </div>

            <!-- CURRENT STOCK -->
            <div>

                <label class="block text-gray-700 font-semibold mb-2">
                    Current Stock
                </label>

                <input type="number"
                    value="{{ $material->quantity }}"
                    readonly
                    class="w-full bg-gray-100 border rounded-xl p-3 cursor-not-allowed">

                <p class="text-sm text-gray-500 mt-2">
                    Stock quantity can only be updated through restocking,
                    requests, transfers, or damage adjustments.
                </p>

            </div>

            <!-- THRESHOLD -->
            <div>

                <label class="block text-gray-700 font-semibold mb-2">
                    Low Stock Threshold
                </label>

                <input type="number"
                       name="threshold"
                       value="{{ $material->threshold }}"
                       class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-yellow-400">

            </div>

        </div>

        <!-- BUTTONS -->
        <div class="flex gap-4 mt-8">

            <button type="submit"
                    class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-6 py-3 rounded-xl shadow-lg hover:scale-105 transition">

                💾 Update Material

            </button>

            <a href="{{ route('materials.index') }}"
               class="bg-gray-300 text-gray-800 px-6 py-3 rounded-xl hover:bg-gray-400 transition">

                Cancel

            </a>

        </div>

    </form>

</div>

<script>

    function previewMaterialImage(input)
    {
        const preview = document.getElementById('image-preview');

        if (!input.files || !input.files[0]) {
            preview.classList.add('hidden');
            preview.src = '';
            return;
        }

        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('hidden');
    }

</script>

@include('supervisor.materials._quick_add_modals')

@endsection
