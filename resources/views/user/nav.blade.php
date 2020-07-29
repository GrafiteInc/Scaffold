<ul class="nav nav-tabs mb-4 d-none d-mb-block nav-tabs-custom flex-column flex-sm-row">
    <li class="nav-item">
        <a class="{{ route_link_class('user.settings') }}" href="{{ route('user.settings') }}">Settings</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class('user.security') }}" href="{{ route('user.security') }}">Security</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class(['user.invites', 'user.invites.*']) }}" href="{{ route('user.invites') }}">Invites</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class(['user.teams', 'user.teams.*']) }}" href="{{ route('user.teams') }}">Teams</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class('user.notifications') }}" href="{{ route('user.notifications') }}">Notifications</a>
    </li>
</ul>
