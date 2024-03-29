<ul class="nav nav-pills mb-4 p-1 rounded bg-body-tertiary">
    <li class="nav-item">
        <a class="{{ route_link_class(['user.settings', 'user.settings.*']) }}" href="{{ route('user.settings') }}">Settings</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class(['user.security', 'user.security.*']) }}" href="{{ route('user.security') }}">
            Security
            @if (request()->user()->hasUnconfirmedTwoFactor())
                <span class="fa fa-exclamation-triangle"></span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class('user.api-tokens') }}" href="{{ route('user.api-tokens') }}">API Tokens</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class(['user.invites', 'user.invites.*']) }}" href="{{ route('user.invites') }}">Invites</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class('user.notifications') }}" href="{{ route('user.notifications') }}">Notifications</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class('user.billing') }}" href="{{ route('user.billing') }}">Billing</a>
    </li>
</ul>