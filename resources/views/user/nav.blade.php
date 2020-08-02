<ul class="nav nav-tabs flex-column flex-sm-row mb-4">
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
        <a class="{{ route_link_class('user.notifications') }}" href="{{ route('user.notifications') }}">Notifications</a>
    </li>
</ul>
