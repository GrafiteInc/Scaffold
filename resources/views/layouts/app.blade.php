@extends('layouts.main')

@section('app-content')

    <nav id="sidebar" class="sidebar">
        <div class="sidebar-sticky mt-4">
            @include('layouts.app.sidebar')
        </div>
    </nav>

    <main class="ml-sm-auto main">
        @include("layouts.app.navbar")

        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

@stop
