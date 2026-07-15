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
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}"
                           href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active fw-semibold' : '' }}"
                           href="{{ route('about') }}">Tentang</a>
                    </li>
                @endguest
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('tasks.*') ? 'active fw-semibold' : '' }}"
                        href="#"
                        id="tasksDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                            Tasks
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="tasksDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}"
                                href="{{ route('tasks.index') }}">
                                    Daftar Task
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item {{ request()->routeIs('tasks.pending') ? 'active' : '' }}"
                                href="{{ route('tasks.pending') }}">
                                    Pending
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}"
                        href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active fw-semibold' : '' }}"
                        href="{{ route('categories.index') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile*') ? 'active fw-semibold' : '' }}"
                        href="{{ route('profile') }}">Profile</a>
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