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
                        Material
                    </th>

                    <th class="p-4 text-left">
                        Current Balance
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($balances as $item)

                    <tr class="border-b">

                        <td class="p-4">
                            {{ $item->department->department_name }}
                        </td>

                        <td class="p-4">
                            {{ $item->material->name }}
                        </td>

                        <td class="p-4 font-bold text-green-600">
                            {{ $item->total_quantity }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="p-8 text-center text-gray-500">

                            No department inventory found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection