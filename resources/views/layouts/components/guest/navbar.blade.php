<div class="container-fluid">
    <nav class="navbar bg-primary sticky-top p-0 mx-n3">
        <a class="navbar-brand m-2 text-white" href="/">
            <span class="fas fa-fw fa-cogs"></span> {{ config('app.name') }}
        </a>
        <ul class="navbar-nav px-3">
            <li class="nav-item">
                @guest
                    <a href="{{ route('login') }}" class="nav-link text-white">
                        <span class="fas fa-sign-in-alt"></span>
                        Login
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="nav-link text-white">
                        <span class="fas fa-fw fa-tachometer-alt"></span>
                        Dashboard
                    </a>
                @endguest
            </li>
        </ul>
    </nav>
</div>
