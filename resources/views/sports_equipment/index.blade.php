@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">

        <div>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🏀 Sports Equipment Inventory
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Equipment catalog available for users to borrow via Material Request.
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('sports-equipment.borrows.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                🔄 Borrow Requests
            </a>

            <a href="{{ route('sports-equipment.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">
                ➕ Add Equipment
            </a>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto border rounded-lg">

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Equipment</th>
                    <th class="p-3 text-center">Total</th>
                    <th class="p-3 text-center">Borrowed</th>
                    <th class="p-3 text-center">Available</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($equipments as $equipment)

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold flex items-center gap-3">
                            @if($equipment->image_url)
                                <img src="{{ $equipment->image_url }}" class="w-10 h-10 object-cover rounded-lg border" alt="">
                            @endif
                            {{ $equipment->name }}
                        </td>
                        <td class="p-3 text-center">{{ $equipment->total_quantity }} {{ $equipment->unit }}</td>
                        <td class="p-3 text-center">{{ $equipment->borrowedQuantity() }}</td>
                        <td class="p-3 text-center">
                            <span class="font-semibold {{ $equipment->availableQuantity() > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $equipment->availableQuantity() }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <a href="{{ route('sports-equipment.edit', $equipment->id) }}" class="text-blue-600 hover:underline text-sm mr-3">
                                ✏ Edit
                            </a>

                            <button type="button"
                                onclick="confirmDelete({{ $equipment->id }}, '{{ addslashes($equipment->name) }}')"
                                class="text-red-600 hover:underline text-sm">
                                🗑 Delete
                            </button>

                            <form id="delete-form-{{ $equipment->id }}" action="{{ route('sports-equipment.destroy', $equipment->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            No sports equipment yet. Click "Add Equipment" to start the catalog.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

function confirmDelete(id, name)
{
    Swal.fire({
        icon: 'warning',
        title: 'Delete ' + name + '?',
        text: 'This cannot be undone.',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#dc2626',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

</script>

@endsection
