<li class="nav-item">
    <a class="{{ route_link_class('dashboard', 'active') }}" href="{{ route('dashboard') }}">
        <span class="fas fa-tachometer-alt fa-fw"></span> Dashboard
    </a>
</li>

<li class="nav-item">
    <a class="{{ route_link_class(['teams', 'teams.*'], 'active') }}" href="{{ route('teams') }}">
        <span class="fas fa-users fa-fw"></span> Teams
    </a>
</li>