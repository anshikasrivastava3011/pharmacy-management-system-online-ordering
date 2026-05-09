<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('index') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>

            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Fullscreen -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- Grid Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('index') }}" class="dropdown-item">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>

                <a href="{{ url('/medicines') }}" class="dropdown-item">
                    <i class="fas fa-pills mr-2"></i> Medicines
                </a>

                <a href="{{ url('/orders') }}" class="dropdown-item">
                    <i class="fas fa-shopping-cart mr-2"></i> Orders
                </a>
            </div>
        </li>

        @guest
        @if (Route::has('login'))
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('login') }}">Login</a>
        </li>
        @endif
        @else
        <!-- Admin/User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" role="button">
                @role('pharmacy')
                <img src="{{ asset('storage/pharmacies_Images/' . Auth::user()->pharmacy->avatar_image) }}"
                    class="img-circle elevation-2 mr-2"
                    alt="User Image"
                    width="30">
                @endrole

                @role('doctor')
                <img src="{{ asset('storage/doctors_Images/' . Auth::user()->doctor->avatar_image) }}"
                    class="img-circle elevation-2 mr-2"
                    alt="User Image"
                    width="30">
                @endrole

                @role('admin')
                <img src="{{ asset('dist/img/admin.jpg') }}"
                    class="img-circle elevation-2 mr-2"
                    alt="Admin Image"
                    width="30">
                @endrole

                <span>{{ Auth::user()->name }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    {{ Auth::user()->name }}
                </span>

                <div class="dropdown-divider"></div>

                <a href="{{ route('index') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Dashboard
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item border-0 bg-transparent">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>
        @endguest
    </ul>
</nav>