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

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

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

        <!-- OUT OF STOCK -->
        <div class="bg-white rounded-2xl shadow-xl p-6">

            <h3 class="text-gray-500 text-sm">
                Out of Stock
            </h3>

            <p class="text-3xl font-bold text-red-500 mt-2">
                {{ $outOfStock }}
            </p>

        </div>

    </div>

    <!-- SEARCH -->
    <div class="bg-white rounded-2xl shadow-xl p-4 mb-6">

        <form method="GET" action="{{ route('materials.index') }}">

            <div class="flex gap-3">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search materials..."
                       class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                <button
                    class="bg-blue-600 text-white px-6 rounded-xl hover:bg-blue-700 transition">

                    Search
                </button>

            </div>

        </form>

    </div>

    <!-- TABLE -->
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">

                <tr>
                    <th class="p-4 text-left">Name</th>
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

                        <td class="p-4 flex gap-2">

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

                        </td>
                       
                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
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