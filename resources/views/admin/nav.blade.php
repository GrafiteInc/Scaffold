<ul class="nav nav-pills mb-4 rounded p-1 bg-body-tertiary">
    <li class="nav-item">
        <a class="{{ route_link_class('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class('admin.users.*') }}" href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class(['admin.roles.*']) }}" href="{{ route('admin.roles.index') }}">Roles</a>
    </li>
    <li class="nav-item">
        <a class="{{ route_link_class(['admin.announcements.*']) }}" href="{{ route('admin.announcements.create') }}">Announcements</a>
    </li>
</ul>
