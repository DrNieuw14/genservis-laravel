@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <h2 class="text-3xl font-bold text-white flex items-center gap-2">
            📦 Materials Inventory
        </h2>

        <a href="{{ route('materials.create') }}"
           class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-5 py-3 rounded-xl shadow-lg hover:scale-105 transition">

            ➕ Add Material
        </a>

        <a href="{{ route('materials.import.form') }}"
            class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-xl shadow-lg">

            📥 Import Inventory

        </a>

        <a href="{{ route('supervisor.inventory.movements.index') }}"
            class="bg-white text-blue-700 px-5 py-3 rounded-xl shadow-lg hover:scale-105 transition font-semibold">

            📊 Inventory Logs
        </a>

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-6">

        <!-- TOTAL -->
        <div class="bg-white rounded-2xl shadow-xl p-6">

            <h3 class="text-gray-500 text-sm">
                Total Materials
            </h3>

            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $totalMaterials }}
            </p>

        </div>

        <!-- LOW STOCK -->
        <div class="bg-white rounded-2xl shadow-xl p-6">

            <h3 class="text-gray-500 text-sm">
                Low Stock
            </h3>

            <p class="text-3xl font-bold text-yellow-500 mt-2">
                {{ $lowStock }}
            </p>

        </div>

        <!-- CRITICAL STOCK -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-red-600">

            <h3 class="text-gray-500 text-sm">
                Critical Stock
            </h3>

            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $criticalStock }}
            </p>

        </div>

        <!-- OUT OF STOCK -->
        <div class="bg-white rounded-2xl shadow-xl p-6">

            <h3 class="text-gray-500 text-sm">
                Out of Stock
            </h3>

            <p class="text-3xl font-bold text-red-500 mt-2">
                {{ $outOfStock }}
            </p>

        </div>

        <!-- EXPIRING SOON -->
        <div class="bg-orange-500 text-white rounded-2xl shadow-xl p-6">

            <h3 class="text-sm">
                Expiring Soon
            </h3>

            <p class="text-3xl font-bold mt-2">
                {{ $expiringSoon }}
            </p>

            <p class="text-xs mt-2">
                Within 30 Days
            </p>

        </div>

        <!-- EXPIRED -->
        <div class="bg-red-700 text-white rounded-2xl shadow-xl p-6">

            <h3 class="text-sm">
                Expired Batches
            </h3>

            <p class="text-3xl font-bold mt-2">
                {{ $expiredItems }}
            </p>

            <p class="text-xs mt-2">
                Immediate Attention
            </p>

        </div>

    </div>

    <!-- SEARCH -->

    <!-- INVENTORY HEALTH MATRIX -->

    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">

        <div class="flex justify-between items-center mb-4">

            <h3 class="text-xl font-bold text-gray-700">
                📊 Inventory Health by Category
            </h3>

            <span class="text-sm text-gray-500">
                Healthy • Low • Critical • Out of Stock
            </span>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>

                    <tr class="border-b">

                        <th class="text-left py-3">
                            Category
                        </th>

                        <th class="text-center py-3 text-green-600">
                            Healthy
                        </th>

                        <th class="text-center py-3 text-yellow-600">
                            Low
                        </th>

                        <th class="text-center py-3 text-red-500">
                            Critical
                        </th>

                        <th class="text-center py-3 text-gray-700">
                            Out
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($inventoryHealth as $row)

                        <tr class="border-b hover:bg-gray-50">

                            <td class="py-3 font-medium">
                                {{ $row->name }}
                            </td>

                            <td class="text-center">

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">

                                    {{ $row->healthy }}

                                </span>

                            </td>

                            <td class="text-center">

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">

                                    {{ $row->low }}

                                </span>

                            </td>

                            <td class="text-center">

                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">

                                    {{ $row->critical }}

                                </span>

                            </td>

                            <td class="text-center">

                                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold">

                                    {{ $row->out }}

                                </span>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <!-- CATEGORY SUMMARY -->

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3 mb-6">

        @foreach($categorySummary as $category)

            <a href="{{ route('materials.index', ['category_id' => $category->id]) }}"
                class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-xl hover:scale-105 transition block
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
    <div class="bg-white rounded-2xl shadow-xl p-4 mb-6">
       
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

    <!-- TABLE -->
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

        
        <table class="min-w-full">


            <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">

                <tr>

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
                                    onclick="return confirm('Delete this material?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">

                                    🗑 Delete
                                </button>

                            </form>
                                </div>

                        </td>
                       
                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
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