@extends('layouts.master')

@section('app-content')

    <nav id="sidebar" class="sidebar">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                @include('dashboard.panel')
            </ul>
        </div>
    </nav>

    <main class="ml-sm-auto pt-3 main">
        @yield('content')
    </main>

@stop