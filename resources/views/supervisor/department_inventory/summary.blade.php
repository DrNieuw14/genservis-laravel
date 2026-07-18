@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3 mb-6">
        📊 Department Inventory Summary
    </h2>

    <div class="border rounded-lg overflow-hidden">

        <table class="w-full text-lg">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-4 text-left text-gray-800">
                        Department
                    </th>

                    <th class="p-4 text-left text-gray-800">
                        Material
                    </th>

                    <th class="p-4 text-left text-gray-800">
                        Total Quantity
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($summary as $row)

                <tr class="hover:bg-gray-50 transition">

                    <td class="p-4">
                        {{ $row->department->department_name ?? 'N/A' }}
                    </td>

                    <td class="p-4">
                        {{ $row->material->name ?? 'N/A' }}
                    </td>

                    <td class="p-4 font-bold text-green-600">
                        {{ $row->total_quantity }}
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="3"
                        class="text-center p-8 text-gray-500">

                        No department inventory found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection