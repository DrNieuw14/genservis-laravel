@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3 mb-6">
        📦 Department Inventory Balance
    </h2>

    <div class="border rounded-lg overflow-hidden">

        <table class="w-full text-lg">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-4 text-left text-gray-800">
                        Department
                    </th>

                    <th class="p-4 text-left text-gray-800">
                        Total Materials
                    </th>

                    <th class="p-4 text-left text-gray-800">
                        Total Quantity
                    </th>

                    <th class="p-4 text-center text-gray-800">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($balances as $item)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4 font-semibold">
                            {{ $item->department->department_name }}
                        </td>

                        <td class="p-4">
                            {{ $item->total_materials }}
                        </td>

                        <td class="p-4 font-bold text-green-600">
                            {{ $item->total_quantity }}
                        </td>

                        <td class="p-4 text-center">

                            <a
                                href="{{ route(
                                    'department.inventory.details',
                                    $item->department_id
                                ) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                                View Details

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="p-8 text-center text-gray-500">

                            No department inventory found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection