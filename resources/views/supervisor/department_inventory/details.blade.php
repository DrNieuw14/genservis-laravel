@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            📦 {{ $department->department_name }}
        </h2>

        <x-back-button :href="route('department.inventory.balance')" />

    </div>

    <div class="border rounded-lg overflow-hidden">

        <table class="w-full text-lg">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-4 text-left text-gray-800">
                        Material
                    </th>

                    <th class="p-4 text-left text-gray-800">
                        Quantity
                    </th>

                    <th class="p-4 text-left text-gray-800">
                        Released Date
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($materials as $item)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4">
                            {{ $item->material->name ?? 'Unknown Material' }}
                        </td>

                        <td class="p-4 font-semibold">
                            {{ $item->quantity }}
                        </td>

                        <td class="p-4">
                            {{ $item->released_at ? \Carbon\Carbon::parse($item->released_at)->format('M d, Y h:i A') : '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3"
                            class="p-6 text-center text-gray-500">

                            No materials found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection