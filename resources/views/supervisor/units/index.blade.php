@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-4xl font-bold text-white flex items-center gap-3">
                📏 Units
            </h2>

            <p class="text-white/80 mt-2">
                Manage inventory units for GenServis.
            </p>
        </div>

        <a href="{{ route('units.create') }}"
           class="bg-gradient-to-r from-green-500 to-blue-500
                  hover:scale-105 transition
                  text-white px-5 py-3 rounded-xl shadow-lg font-semibold">

            ➕ Add Unit

        </a>

    </div>

    <!-- ALERTS -->
    @if(session('success'))

        <div class="bg-green-500 text-white p-4 rounded-xl mb-4 shadow">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-500 text-white p-4 rounded-xl mb-4 shadow">
            {{ session('error') }}
        </div>

    @endif

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        <table class="w-full">

            <!-- HEADER -->
            <thead class="bg-gradient-to-r from-green-500 to-blue-500 text-white">

                <tr>
                    <th class="p-4 text-left">#</th>
                    <th class="p-4 text-left">Unit Name</th>
                    <th class="p-4 text-left">Materials</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>

            </thead>

            <!-- BODY -->
            <tbody>

                @forelse($units as $unit)

                <tr class="border-b hover:bg-gray-50 transition">

                    <!-- NUMBER -->
                    <td class="p-4 font-semibold text-gray-700">
                        {{ $loop->iteration }}
                    </td>

                    <!-- NAME -->
                    <td class="p-4 font-semibold text-gray-800">
                        {{ $unit->name }}
                    </td>

                    <!-- MATERIAL COUNT -->
                    <td class="p-4">

                        <span class="bg-blue-100 text-blue-700
                                     px-3 py-1 rounded-full text-sm font-semibold">

                            {{ $unit->materials_count }} Materials

                        </span>

                    </td>

                    <!-- ACTIONS -->
                    <td class="p-4 text-center">

                        <div class="flex justify-center gap-2">

                            <!-- EDIT -->
                            <a href="{{ route('units.edit', $unit->id) }}"
                               class="bg-blue-500 hover:bg-blue-600
                                      text-white px-4 py-2 rounded-lg shadow">

                                ✏️ Edit
                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('units.destroy', $unit->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this unit?')">

                                @csrf
                                @method('DELETE')

                                <button class="bg-red-500 hover:bg-red-600
                                               text-white px-4 py-2 rounded-lg shadow">

                                    🗑 Delete

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4"
                        class="text-center p-10 text-gray-500">

                        No units found.

                    </td>
                    
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection