<nav class="navbar bg-dark sticky-top flex-md-nowrap p-0">
    <a class="navbar-brand mr-0" href="/"><span class="fa fa-cogs"></span> Brand</a>
    <ul class="navbar-nav px-3">
        <li class="nav-item">
            @if (!auth()->check())
                <a href="{{ route('login') }}" class="nav-link text-white">
                    <span class="fas fa-sign-in-alt"></span>
                    Login
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="nav-link text-white">
                    <span class="fas fa-tachometer-alt"></span>
                    Dashboard
                </a>
            @endif
        </li>
    </ul>
</nav>
