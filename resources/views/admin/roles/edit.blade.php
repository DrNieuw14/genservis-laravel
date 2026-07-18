@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="flex justify-between items-start mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🔐 Edit Role
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                {{ $role->name }}
            </p>

        </div>

        <x-back-button :href="route('roles.index')" />

    </div>

    <form method="POST" action="{{ route('roles.update', $role) }}">

        @csrf
        @method('PUT')

        @include('admin.roles._form')

        <div class="flex justify-end mt-8">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow font-semibold text-lg">

                💾 Save Changes

            </button>

        </div>

    </form>

</div>

@endsection
