<li class="{{ route_link_class('dashboard', 'active', 'nav-item') }}">
    <a class="nav-link" href="{{ route('dashboard') }}">
        <span class="fas fa-tachometer-alt fa-fw"></span> Dashboard
    </a>
</li>

<li class="{{ route_link_class(['teams', 'teams.*'], 'active', 'nav-item') }}">
    <a class="nav-link" href="{{ route('teams') }}">
        <span class="fas fa-users fa-fw"></span> Teams
    </a>
</li>
