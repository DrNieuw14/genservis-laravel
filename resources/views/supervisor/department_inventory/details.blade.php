@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">

        <h1 class="text-4xl font-bold text-white">
            📦 {{ $department->department_name }}
        </h1>

        <a href="{{ route('department.inventory.balance') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Back
        </a>

    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

        <table class="w-full">

            <thead>

                <tr class="bg-gradient-to-r from-blue-500 to-green-600 text-white">

                    <th class="p-4 text-left">
                        Material
                    </th>

                    <th class="p-4 text-left">
                        Quantity
                    </th>

                    <th class="p-4 text-left">
                        Released Date
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($materials as $item)

                    <tr class="border-b">

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