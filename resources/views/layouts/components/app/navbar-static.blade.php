<nav class="navbar navbar-header navbar-expand fixed-top flex-md-nowrap app-with-sidebar-nav p-0">
    <div class="container-fluid">
        <span class="navbar-text ms-2">
            <span class="sidebar-toggle me-3">
                <i class="fas fa-bars"></i>
            </span>
            <span class="navbar-text navbar-title p-0">
                @yield('page-title', 'Dashboard')
            </span>
        </span>
        <ul class="navbar-nav ms-auto">
            <x-app-navbar></x-app-navbar>
        </ul>
    </div>
</nav>
