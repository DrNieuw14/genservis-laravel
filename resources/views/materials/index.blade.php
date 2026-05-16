@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold mb-6 text-white">
        📦 Materials Inventory
    </h2>

    <div class="bg-white rounded-xl shadow p-6">

        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Name</th>
                    <th class="p-3 border">Category</th>
                    <th class="p-3 border">Unit</th>
                    <th class="p-3 border">Stock</th>
                </tr>
            </thead>

            <tbody>
                @foreach($materials as $material)
                <tr class="hover:bg-gray-50">

                    <td class="p-3 border">{{ $material->name }}</td>

                    <td class="p-3 border">
                        {{ $material->category->name ?? '-' }}
                    </td>

                    <td class="p-3 border">
                        {{ $material->unit->name ?? '-' }}
                    </td>

                    <td class="p-3 border">

                        @if($material->quantity <= 0)
                            <span class="bg-red-500 text-white px-2 py-1 rounded">
                                Out of Stock
                            </span>

                        @elseif($material->quantity <= $material->threshold)
                            <span class="bg-yellow-400 text-black px-2 py-1 rounded">
                                Low Stock ({{ $material->quantity }})
                            </span>

                        @else
                            <span class="text-green-600 font-semibold">
                                {{ $material->quantity }}
                            </span>
                        @endif

                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</div>

@endsection