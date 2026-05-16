@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto mt-10">

    <div class="bg-white shadow-xl rounded-xl p-6">

        <h2 class="text-2xl font-bold mb-6 text-gray-700">
            📦 Material Request
        </h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/material-request">
            @csrf

            <!-- MATERIAL -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Material</label>
                <select name="material_id" class="w-full border rounded-lg p-2" required>
                    <option value="">-- Select Material --</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}">
                            {{ $material->name }} (Stock: {{ $material->quantity }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- QUANTITY -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Quantity</label>
                <input type="number" name="quantity" min="1"
                    class="w-full border rounded-lg p-2" required>
            </div>

            <button class="w-full bg-green-600 text-white py-2 rounded-lg">
                Submit Request
            </button>

        </form>

    </div>
</div>

@endsection