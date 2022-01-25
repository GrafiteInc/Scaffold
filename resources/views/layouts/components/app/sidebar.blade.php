{{-- <div class="mx-4 mb-3">
    <h4 class="m-0">
        <span class="fa fa-fw fa-cogs"></span>
        <span class="ps-2">{{ config('app.name') }}</span>
    </h4>
</div> --}}

<ul class="nav flex-column">
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
</ul>
