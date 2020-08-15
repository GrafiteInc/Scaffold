<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta property="og:description" content="">
        <meta property="og:title" content="">
        <meta property="og:image" content="">
        <meta property="og:url" content="{{ url()->current() }}">

        <title>App | @yield('page-title', 'My App')</title>

        <link rel="icon" type="image/ico" href="">

        @if (auth()->user() && auth()->user()->dark_mode)
            <link rel="stylesheet" type="text/css" href="/css/dark-app.css">
        @else
            <link rel="stylesheet" type="text/css" href="/css/light-app.css">
        @endif
    </head>
    <body>
        <div id="app">
            @if (!request()->routeIs(['home']))
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
                <vue-snotify></vue-snotify>

                <session
                    user='{!! optional(auth()->user())->jsonSessionData() !!}'
                    message='{!! session('message') !!}'
                    error_message='{!! sessionErrorMessage() !!}'
                ></session>
            </div>

            @yield("alerts")
        </div>

        @routes

        @yield('pre-app-js')

        <script src="https://kit.fontawesome.com/40ca63cf3f.js"></script>
        <script src="/js/app.js"></script>

        @yield('javascript')

        @formMaker
    </body>
</html>