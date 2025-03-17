<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Fleethub Logo" style="height: 40px;">
        </a>

        <!-- Hamburger -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('deal.*') ? 'active' : '' }}" href="{{ route('deal.index') }}">
                            <i class="bi bi-briefcase"></i> Deals
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('quote.*') ? 'active' : '' }}" href="{{ route('quote.index') }}">
                            <i class="bi bi-file-text"></i> Quotes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('order.*') ? 'active' : '' }}" href="{{ route('order.index') }}">
                            <i class="bi bi-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}" href="{{ route('customer.index') }}">
                            <i class="bi bi-people"></i> Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vehicle.*') ? 'active' : '' }}" href="{{ route('vehicle.index') }}">
                            <i class="bi bi-truck"></i> Vehicles
                        </a>
                    </li>
                @endauth
            </ul>

            <!-- Right Side Navigation -->
            <ul class="navbar-nav">
                @auth
                    <!-- Add New Dropdown -->
                    <li class="nav-item dropdown me-3">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="addNewDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-plus-circle"></i> Add New
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="addNewDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('quote.create') }}">
                                    <i class="bi bi-file-text"></i> New Quote
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.create') }}">
                                    <i class="bi bi-person-plus"></i> New Customer
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('vehicle.create') }}">
                                    <i class="bi bi-truck"></i> New Vehicle
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('deal.create') }}">
                                    <i class="bi bi-briefcase"></i> New Deal
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-gear"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="bi bi-box-arrow-in-right"></i> Log in
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>
