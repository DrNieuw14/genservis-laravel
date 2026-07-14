@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    @include('employees.partials.personal-header')

    @include('employees.partials.personal-information')

    @include('employees.partials.contact-information')

     @include('employees.partials.educational-background')

</div>

@endsection