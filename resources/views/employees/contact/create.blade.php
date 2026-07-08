@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-8">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            📞 Add Contact Information
        </h1>

        <p class="text-gray-500 mt-2">
            Enter the employee's contact details and emergency contact information.
        </p>

    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <form method="POST"
              action="{{ route('employees.contact.store', $employee) }}">

            @csrf

            @php
                $contact = null;
            @endphp

            @include('employees.contact._form')

        </form>

    </div>

</div>

@endsection