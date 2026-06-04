@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

    <h2 class="text-3xl font-bold text-white mb-6">
        📊 Department Inventory Summary
    </h2>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-gradient-to-r from-green-500 to-blue-600 text-white">

                <tr>

                    <th class="p-4 text-left">
                        Department
                    </th>

                    <th class="p-4 text-left">
                        Material
                    </th>

                    <th class="p-4 text-left">
                        Total Quantity
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($summary as $row)

                <tr class="border-b hover:bg-gray-50">

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