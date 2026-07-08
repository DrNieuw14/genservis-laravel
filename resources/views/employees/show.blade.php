@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    @include('employees.partials.profile-header')

    @include('employees.partials.personal-information')

    @include('employees.partials.contact-information')

</div>

@endsection