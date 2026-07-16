@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->
    <div class="flex justify-between items-start mb-8">

        <div>

            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                🔐 Create Role
            </h1>

            <p class="text-gray-200 mt-2">
                Add a new system role.
            </p>

        </div>

        <a
            href="{{ route('roles.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2
                bg-indigo-600 hover:bg-indigo-700
                text-white text-sm font-medium
                rounded-lg shadow transition">

            ⬅️ Back

        </a>

    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">

        <form method="POST" action="{{ route('roles.store') }}">

            @csrf

            @include('admin.roles._form')

            <div class="flex justify-end mt-8">

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow">

                    💾 Create Role

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
