@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">

    <div class="bg-white rounded-xl shadow border border-gray-200">

        <div class="border-b px-6 py-5">

            <h1 class="text-2xl font-bold text-gray-800">
                ➕ Create Role
            </h1>

            <p class="text-gray-500 mt-1">
                Create a new system role for GenServis.
            </p>

        </div>

        <form action="{{ route('roles.store') }}" method="POST">

            @csrf

            @include('roles._form')

        </form>

    </div>

</div>

@endsection