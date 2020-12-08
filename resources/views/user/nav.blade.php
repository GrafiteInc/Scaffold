<ul class="nav nav-tabs flex-sm-row mb-4 nav-tabs-custom">
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
    <li class="nav-item">
        <a class="{{ route_link_class('user.api-tokens') }}" href="{{ route('user.api-tokens') }}">API Tokens</a>
    </li>
</ul>