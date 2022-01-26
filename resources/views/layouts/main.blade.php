<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="version" content="v{{ config('laravelpwa.version', '1') }}">

        {{-- Helps prevent 419 issues --}}
        <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

        <meta property="og:description" content="">
        <meta property="og:title" content="">
        <meta property="og:image" content="">
        <meta property="og:url" content="{{ url()->current() }}">

        <title>{{ config('app.name') }} | @yield('page-title', 'Scaffold')</title>

        <link rel="icon" type="image/ico" href="">

        @include('layouts.components.theme-styles')

        @formStyles
        @chartStyles
        @htmlStyles
        @chartCdn
        @laravelPWA
        @missionControl
    </head>
    <body>
        <div id="app" class="min-vh-100">
            <div class="w-100 bmx-overflow-x-hidden">
                @yield("app-content")
            </div>

            <cookie-law
                version="{{ config('app.version', 'v1') }}"
            ></cookie-law>
            <confirmation-modal></confirmation-modal>
            <content-modal></content-modal>
            <pending-overlay></pending-overlay>
            <notifications></notifications>

            @yield("alerts")
        </div>

        @routes

        {!! javascript_session_data() !!}

        @yield('pre-app-js')

        <script src="{{ mix('/js/app.js') }}"></script>

        @yield('javascript')

        @formScripts
        @chartScripts
    </body>
</html>