<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
            📋 Task Manager
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}"
                       href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active fw-semibold' : '' }}"
                       href="{{ route('about') }}">Tentang</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('tasks.index') }}">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}"
                        href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active fw-semibold' : '' }}"
                        href="{{ route('categories.index') }}">Kategori</a>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                @auth
                    <li class="nav-item">
                        <span class="nav-link text-muted small py-lg-0">
                            Halo, <strong>{{ auth()->user()->name }}</strong>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active fw-semibold' : '' }}"
                           href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary btn-sm text-white px-3 {{ request()->routeIs('register') ? 'active' : '' }}"
                           href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>