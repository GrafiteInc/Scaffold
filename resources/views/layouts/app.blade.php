<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="version" content="1">

        {{-- Helps prevent 419 issues --}}
        <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

        <meta property="og:description" content="">
        <meta property="og:title" content="">
        <meta property="og:image" content="">
        <meta property="og:url" content="{{ url()->current() }}">

        <title>App | @yield('page-title', 'My App')</title>

        <link rel="icon" type="image/ico" href="">

        @if (auth()->user() && auth()->user()->dark_mode)
            <link rel="stylesheet" type="text/css" href="{{ mix('css/dark-app.css') }}">
        @else
            <link rel="stylesheet" type="text/css" href="{{ mix('css/light-app.css') }}">
        @endif

        @livewireStyles
        @laravelPWA
    </head>
    <body>
        <div id="app">
            @if (! request()->routeIs(['home']))
                @include("layouts.app-nav")
            @else
                @include("layouts.home-nav")
            @endif

            <div class="container-fluid">
                @yield("app-content")

                <cookielaw
                    version="{{ config('app.version', 'v1') }}"
                ></cookielaw>

                <modal></modal>
                <content-modal></content-modal>
                <pending-modal></pending-modal>
                <vue-snotify></vue-snotify>

                <session
                    user='{!! optional(auth()->user())->jsonSessionData() !!}'
                    message='{!! session('message') !!}'
                    info='{!! session('info') !!}'
                    warning='{!! session('warning') !!}'
                    error_message='{!! sessionErrorMessage() !!}'
                ></session>
            </div>

            @yield("alerts")
        </div>

        @routes

        @yield('pre-app-js')

        <script src="https://kit.fontawesome.com/40ca63cf3f.js"></script>
        <script src="{{ mix('/js/app.js') }}"></script>

        @yield('javascript')

        @livewireScripts
        @forms
    </body>
</html>