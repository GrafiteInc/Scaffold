<nav class="navbar navbar-header sticky-top flex-md-nowrap p-0">
    <a class="navbar-brand mr-0" href="/"><span class="fa fa-cogs"></span> Brand</a>
    <ul class="navbar-nav mr-auto">
        <span class="navbar-text ml-4 page-title">
            <a class="sidebar-toggle text-light mr-3"><i class="fa fa-bars"></i></a>
            <span class="text">@yield('page-title', 'Dashboard')</span>
        </span>
    </ul>
    <ul class="navbar-nav px-3">
        <li class="nav-item">
            @if (auth()->user())
                <div class="dropdown">
                    <button class="btn btn-text dropdown-toggle btn-account" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="profile-icon" style="background-image: url({{ auth()->user()->avatar_url }});"></div>
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('user.settings') }}">Settings</a>
                        <a class="dropdown-item" href="{{ route('user.security') }}">Security</a>
                        <a class="dropdown-item" href="{{ route('user.teams') }}">Teams</a>
                        <a class="dropdown-item" href="{{ route('user.invites') }}">Invites</a>
                        <a class="dropdown-item" href="{{ route('user.notifications') }}">Notifications</a>

                        @if (auth()->user()->hasRole('admin'))
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            <a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a>
                            <a class="dropdown-item" href="{{ route('admin.roles.index') }}">Roles</a>
                        @endif

                        <div class="dropdown-divider"></div>
                        {!! form()->action('post', 'logout', 'Logout', ['class' => 'dropdown-item']) !!}
                    </div>
                </div>
            @endif
        </li>
    </ul>
</nav>
