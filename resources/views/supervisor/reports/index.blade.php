@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <h1 class="text-3xl font-bold mb-2">
        📊 Reports Dashboard
    </h1>

    <p class="text-gray-600 mb-8">
        Select a report category to generate or print reports.
    </p>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="border rounded-lg p-6 shadow hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-2">
                📦 Inventory Reports
            </h2>

            <p class="text-gray-500 text-sm">
                Inventory status, stock levels, expiration, and department reports.
            </p>

            <a href="{{ route('reports.inventory') }}"
            class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Open
            </a>
        </div>

        <div class="border rounded-lg p-6 shadow opacity-60">

            <h2 class="text-xl font-bold mb-2">
                👥 Personnel Reports
            </h2>

            <p class="text-sm text-gray-500">
                Coming Soon
            </p>

        </div>

        <div class="border rounded-lg p-6 shadow opacity-60">

            <h2 class="text-xl font-bold mb-2">
                📅 Attendance Reports
            </h2>

            <p class="text-sm text-gray-500">
                Coming Soon
            </p>

        </div>

        <div class="border rounded-lg p-6 shadow opacity-60">

            <h2 class="text-xl font-bold mb-2">
                📝 Leave Reports
            </h2>

            <p class="text-sm text-gray-500">
                Coming Soon
            </p>

        </div>

    </div>

</div>

@endsection