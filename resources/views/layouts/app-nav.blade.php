<nav class="navbar navbar-header sticky-top flex-md-nowrap p-0">
    <a class="navbar-brand bg-primary mr-0" href="/"><span class="fa fa-cogs"></span> Brand</a>
    <ul class="navbar-nav mr-auto">
        <span class="navbar-text ml-4">
            <a class="sidebar-toggle text-light mr-3"><i class="fa fa-bars"></i></a>
            <span class="navbar-text navbar-title p-0">@yield('page-title', 'Dashboard')</span>
        </span>
    </ul>
    <ul class="navbar-nav px-3">
        <li class="nav-item">
            @if (auth()->user())
                <div class="dropdown">
                    <notification-badge></notification-badge>
                    <button class="btn btn-text nav-user-dropdown dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="nav-avatar border border-primary shadow-sm" style="background-image: url({{ auth()->user()->avatar_url }});"></div>
                        <span class="navbar-text">{{ Str::limit(auth()->user()->name, 16) }}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('user.settings') }}">Settings</a>
                        <a class="dropdown-item" href="{{ route('user.security') }}">Security</a>
                        <a class="dropdown-item" href="{{ route('user.invites') }}">Invites</a>
                        <a class="dropdown-item" href="{{ route('user.notifications') }}">Notifications <notification-marker></notification-marker></a>
                        <a class="dropdown-item" href="{{ route('user.api-tokens') }}">API Tokens</a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('user.billing') }}">Billing</a>

                        @if (auth()->user()->hasRole('admin'))
                            <div class="dropdown-divider"></div>
                            <h5 class="dropdown-header">Admin</h5>
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            <a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a>
                            <a class="dropdown-item" href="{{ route('admin.roles.index') }}">Roles</a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('contact') }}">Contact</a>
                        <div class="dropdown-divider"></div>
                        {!! form()->action('post', 'logout', 'Logout', ['class' => 'dropdown-item']) !!}
                    </div>
                </div>
            @endif
        </li>
    </ul>
</nav>
