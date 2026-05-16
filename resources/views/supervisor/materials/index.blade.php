@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-8">

    <div class="bg-white shadow-2xl rounded-2xl p-6">

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            📦 Materials Inventory
        </h2>

        <!-- BUTTON -->
        <a href="{{ route('materials.create') }}" 
           class="inline-block mb-4 bg-gradient-to-r from-green-500 to-blue-600 text-white px-4 py-2 rounded-lg shadow hover:scale-105 transition">
            ➕ Add Material
        </a>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- TABLE -->
        <table class="w-full border-collapse rounded-lg overflow-hidden">

            <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Category</th>
                    <th class="p-3 text-left">Unit</th>
                    <th class="p-3 text-left">Stock</th>
                </tr>
            </thead>

            <tbody>
                @forelse($materials as $material)
                    <tr class="border-b hover:bg-gray-100 transition">

                        <td class="p-3">{{ $material->name }}</td>

                        <td class="p-3">
                            {{ $material->category->name ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $material->unit->name ?? '-' }}
                        </td>

                        <td class="p-3">
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

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-4">
                            No materials found
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>
@endsection