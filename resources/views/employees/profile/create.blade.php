@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-8">

    <h1 class="text-3xl font-bold text-gray-800 mb-6">

        ➕ Add Personal Information

    </h1>

    <form
        action="{{ route('employees.profile.store', $employee) }}"
        method="POST">

        @include('employees.profile._form')

    </form>

</div>

@endsection