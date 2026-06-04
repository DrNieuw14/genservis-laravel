@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto mt-8">

    <h2 class="text-3xl font-bold text-white mb-6">
        🏢 Department Inventory
    </h2>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-blue-600 text-white">

                <tr>

                    <th class="p-4 text-left">Department</th>

                    <th class="p-4 text-left">Material</th>

                    <th class="p-4 text-left">Quantity</th>

                    <th class="p-4 text-left">Released By</th>

                    <th class="p-4 text-left">Released At</th>

                </tr>

            </thead>

            <tbody>

                @foreach($inventories as $item)

                <tr class="border-b">

                    <td class="p-4">
                        {{ $item->department->department_name }}
                    </td>

                    <td class="p-4">
                        {{ $item->material->name }}
                    </td>

                    <td class="p-4">
                        {{ $item->quantity }}
                    </td>

                    <td class="p-4">
                        {{ $item->releaser->fullname ?? $item->releaser->username }}
                    </td>

                    <td class="p-4">
                        {{ $item->released_at }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection