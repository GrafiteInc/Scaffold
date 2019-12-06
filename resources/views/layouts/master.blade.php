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

        @if (auth()->user()->dark_mode)
            <link rel="stylesheet" type="text/css" href="/css/dark-app.css">
        @else
            <link rel="stylesheet" type="text/css" href="/css/light-app.css">
        @endif
    </head>
    <body>
        @if (!request()->routeIs(['home']))
            @include("layouts.app-nav")
        @else
            @include("layouts.home-nav")
        @endif

        <div id="app" class="container-fluid">
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

        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <span>&copy; {!! date('Y'); !!} <a href="">You</a></span>
                @if (session('original_user'))
                    {!! form()->action('post', 'users.return-switch', 'Switch Back', [ 'class' => 'btn btn-secondary' ]) !!}
                @endif
            </div>
        </footer>

        <script src="https://kit.fontawesome.com/40ca63cf3f.js"></script>
        <script src="/js/app.js"></script>

        @yield('javascript')
    </body>
</html>