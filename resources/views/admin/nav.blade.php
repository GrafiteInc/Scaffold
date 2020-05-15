<ul class="nav nav-tabs mb-4 d-none d-sm-none d-md-flex">
    <li class="nav-item">
        <a class="{{ route_link_class('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class('admin.users.*') }}" href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class(['admin.roles.*']) }}" href="{{ route('admin.roles.index') }}">Roles</a>
    </li>
</ul>
