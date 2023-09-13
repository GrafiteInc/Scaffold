<div class="row">
    <div class="col-md-12 mb-4">
        <ul class="nav nav-pills mb-4 p-1 rounded bg-body-tertiary">
            <li class="nav-item">
                <a class="{{ route_link_class(['teams.edit', $team->id]) }}" href="{{ route('teams.edit', [$team->id]) }}">Settings</a>
            </li>
            <li class="nav-item">
                <a class="{{ route_link_class(['teams.members', $team->id]) }}" href="{{ route('teams.members', [$team->id]) }}">Members</a>
            </li>
        </ul>
    </div>
</div>