@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <h1 class="text-4xl font-bold text-white mb-8">
        📦 Department Inventory Balance
    </h1>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

        <table class="w-full">

            <thead>

                <tr class="bg-gradient-to-r from-green-500 to-blue-600 text-white">

                    <th class="p-4 text-left">
                        Department
                    </th>

                    <th class="p-4 text-left">
                        Total Materials
                    </th>

                    <th class="p-4 text-left">
                        Total Quantity
                    </th>

                    <th class="p-4 text-center">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($balances as $item)

                    <tr class="border-b hover:bg-gray-50">

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