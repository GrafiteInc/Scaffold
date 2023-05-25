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

        <div class="container-fluid app-sidebar-content">
            <div class="row">
                <div class="col-lg-3">
                    <div class="ms-2">
                        @yield('panel')
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="me-2">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop
