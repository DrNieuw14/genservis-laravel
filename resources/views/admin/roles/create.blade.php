@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="flex justify-between items-start mb-6">

        <div>

            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
                🔐 Create Role
            </h2>

            <p class="text-gray-500 mt-1 text-lg">
                Add a new system role.
            </p>

        </div>

        <x-back-button :href="route('roles.index')" />

    </div>

    <form method="POST" action="{{ route('roles.store') }}">

        @csrf

        @include('admin.roles._form')

        <div class="flex justify-end mt-8">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow font-semibold text-lg">

                💾 Create Role

            </button>

        </div>

    </form>

</div>

@endsection
