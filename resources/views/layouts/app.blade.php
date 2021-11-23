@extends('layouts.main')

@section('app-content')

    <nav id="sidebar" class="sidebar">
        <div class="sidebar-sticky mt-4">
            <x-app-sidebar></x-app-sidebar>
        </div>
    </nav>

    <main class="ms-sm-auto main">
        <x-app-navbar></x-app-navbar>

        <div class="container app-content">
            @yield('content')
        </div>
    </main>

@stop
