<nav class="navbar navbar-header navbar-expand flex-md-nowrap app-nav-fluid p-0">
    <div class="container-fluid">
        <a class="navbar-brand m-2" href="/">
            <span class="fas fa-fw fa-cogs"></span> {{ config('app.name') }}
        </a>
        <span class="navbar-text ms-2">
            <span class="navbar-text navbar-title p-0">
                @yield('page-title', 'Dashboard')
            </span>
        </span>
        <ul class="navbar-nav ms-auto">
            <x-app-navbar></x-app-navbar>
        </ul>
    </div>
</nav>
