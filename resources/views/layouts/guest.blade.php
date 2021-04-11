@extends('layouts.main')

@section('app-content')
    <x-guest-navbar></x-guest-navbar>

    <div class="mt-4">
        @yield('content')
    </div>
@stop
