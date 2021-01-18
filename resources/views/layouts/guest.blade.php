@extends('layouts.main')

@section('app-content')
    @include("layouts.guest.navbar")

    <div class="container mt-4">
        @yield('content')
    </div>
@stop
