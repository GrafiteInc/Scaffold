@extends('layouts.main')

@section('app-content')
    <main class="w-100">
        <x-app-navbar-fluid></x-app-navbar-fluid>

        <div class="container app-content">
            <x-app-subnav></x-app-subnav>
            @yield('content')
        </div>
    </main>
@stop
