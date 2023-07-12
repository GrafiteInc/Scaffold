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

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://kit.fontawesome.com/e46847d218.js" crossorigin="anonymous"></script>

        @session
        @formStyles
        @htmlStyles
        @chartStyles
        @chartCdn
        @laravelPWA
        @missionControl
        @fathom

        @stack('styles')
        <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    </head>
    <body>
        <div id="app" class="min-vh-100">
            {{-- cookie law --}}
            <x-html-tag component="\App\View\Components\Global\PendingOverlay" />
            <x-html-tag component="\App\View\Components\Global\Notifications" />
            <x-html-tag component="\App\View\Components\Global\ConfirmationModal" />
            <x-html-tag component="\App\View\Components\Global\LivewireConfirmationModal" />
            <x-html-tag component="\App\View\Components\Global\ActionModal" />
            <x-html-tag component="\App\View\Components\Global\ContentModal" />
            <x-html-tag component="\App\View\Components\Global\Offcanvas" />

            <div class="w-100 bmx-overflow-x-hidden min-vh-100">
                @yield("app-content")
            </div>
        </div>

        @routes

        @stack('pre-app-js')

        <script src="{{ mix('/js/app.js') }}"></script>

        @livewireScripts
        {{-- Because we need to reload the assets in case of Styles + JS --}}
        @forms
        {{-- Because we need to reload the assets in case of Styles + JS --}}
        @htmlAssets
        @chartScripts

        @stack('javascript')
    </body>
</html>