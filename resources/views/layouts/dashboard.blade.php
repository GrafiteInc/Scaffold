@extends('layouts.master')

@section('app-content')

    <nav id="sidebar" class="sidebar">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                @include('dashboard.panel')
            </ul>
        </div>
        <footer class="footer d-none d-md-block">
            <div class="container-fluid d-flex justify-content-between">
                <span>&copy; {!! date('Y'); !!} <a href="">You</a></span>
                <div>
                    <a class="mr-2" href="{{ route('terms-of-service') }}">Terms</a>
                    <a class="mr-2" href="{{ route('privacy-policy') }}">Privacy</a>

                    @if (session('original_user'))
                        {!! form()->action('post', 'users.return-switch', 'Switch Back', [ 'class' => 'btn btn-secondary' ]) !!}
                    @endif
                </div>
            </div>
        </footer>
    </nav>

    <main class="ml-sm-auto pt-3 main">
        <div class="container">
            @yield('content')
        </div>
    </main>

@stop
