@extends('layouts.main')

@section('app-content')
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-sticky mt-4">
            <x-app-sidebar></x-app-sidebar>
        </div>
    </nav>

    <main class="ms-sm-auto main">
        <div class="sidebar-overlay"></div>
        <x-app-navbar-static></x-app-navbar-static>

        <div class="container app-sidebar-content">
            @yield('content')
        </div>
    </main>
@stop
