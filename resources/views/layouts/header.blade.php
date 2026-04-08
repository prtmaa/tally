<nav class="main-header navbar navbar-expand navbar-light navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-light" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Fullscreen Button -->
        {{-- <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li> --}}

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('images/user.jpg') }}" class="user-image img-circle elevation-2">
                <span class="d-none d-md-inline text-light">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <!-- User info -->
                <li class="user-header bg-primary">
                    <img src="{{ asset('images/user.jpg') }}" class="user-image img-circle elevation-2">
                    <p>
                        {{ Auth::user()->name }}
                        <small>{{ Auth::user()->email }}</small>
                    </p>
                </li>

                <!-- Menu Footer -->
                <li class="user-footer">
                    <a href="#" class="btn btn-default">Profil</a>

                    <a href="{{ route('logout') }}" class="btn btn-default float-right"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </li>

    </ul>
</nav>
