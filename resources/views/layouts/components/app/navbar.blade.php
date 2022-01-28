<li class="nav-item">
    @if (session('original_user'))
        {!! form()->action('post', ['users.return-switch'], '<span class="fas fa-exchange-alt"></span>', [
            'class' => 'btn btn-text navbar-text me-2'
        ]) !!}
    @endif
</li>
<li class="nav-item">
    <a class="nav-link pe-4" href="{{ route('support') }}">
        <span class="fas fa-question"></span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link pe-2" href="{{ route('user.notifications') }}">
        <notification-badge></notification-badge>
        <span class="fas fa-bell"></span>
    </a>
</li>
<li class="nav-item">
    @if (auth()->user())
        <div class="dropdown">
            <button class="btn btn-text nav-user-dropdown dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="nav-avatar border border-primary shadow-sm" style="background-image: url({{ auth()->user()->avatar_url }});"></div>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ route('user.settings') }}">Settings</a>
                <a class="dropdown-item" href="{{ route('user.api-tokens') }}">API Tokens</a>
                <a class="dropdown-item" href="{{ route('user.invites') }}">Invites</a>
                <a class="dropdown-item" href="{{ route('user.notifications') }}">Notifications</a>

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
                {!! form()->action('post', 'logout', 'Logout', ['class' => 'dropdown-item']) !!}
            </div>
        </div>
    @endif
</li>