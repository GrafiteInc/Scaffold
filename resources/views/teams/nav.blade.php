<div class="row">
    <div class="col-md-12 mb-4">
        <ul class="nav nav-tabs flex-sm-row mb-4 nav-tabs-custom">
            <li class="nav-item">
                <a class="{{ route_link_class('teams.edit') }}" href="{{ route('teams.edit', [$team->id]) }}">Settings</a>
            </li>
            <li class="nav-item">
                <a class="{{ route_link_class('teams.members') }}" href="{{ route('teams.members', [$team->id]) }}">Members</a>
            </li>
        </ul>
    </div>
</div>