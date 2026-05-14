<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('dist/img/PharmacyLogo.png') }}">
    <title>Pharmacy System</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

@php
    $isClient = auth()->check() && auth()->user()->hasRole('client');
@endphp

<body class="hold-transition {{ $isClient ? 'layout-top-nav' : 'sidebar-mini layout-fixed' }} layout-navbar-fixed layout-footer-fixed">
<script>
    if (localStorage.getItem('pharmacy-theme') !== 'light') {
        document.body.classList.add('dark-mode');
    }
</script>

<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__wobble"
             src="{{ asset('dist/img/Transparent-PharmacyLogo.gif') }}"
             alt="PharmacyLogo"
             height="100"
             width="100">
    </div>

    @include('partials.header')

    @if(!$isClient)
        @include('partials.sidebar')
    @endif

    <div class="content-wrapper">
        <div class="content-header">
            <div class="{{ $isClient ? 'container' : 'container-fluid' }}">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <span class="m-0 fs-3">Pharmacy System </span>
                        <span class="fs-4 fw-light">@yield('title')</span>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @if($isClient)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('client.dashboard') }}">Home</a>
                                </li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ route('index') }}">Home</a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active">Pharmacy System</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    @if(!$isClient)
        <aside class="control-sidebar control-sidebar-dark"></aside>
    @endif

    @include('partials.footer')
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@yield('scripts')
@stack('scripts')

<script>
(function () {
    var sidebar = document.querySelector('.main-sidebar');
    var themeIcon = document.getElementById('theme-icon');
    var themeToggle = document.getElementById('theme-toggle');

    function applyTheme(theme) {
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');

            if (themeIcon) {
                themeIcon.className = 'fas fa-sun';
            }

            if (sidebar) {
                sidebar.classList.remove('sidebar-light-primary');
                sidebar.classList.add('sidebar-dark-primary');
            }

            localStorage.setItem('pharmacy-theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');

            if (themeIcon) {
                themeIcon.className = 'fas fa-moon';
            }

            if (sidebar) {
                sidebar.classList.remove('sidebar-dark-primary');
                sidebar.classList.add('sidebar-light-primary');
            }

            localStorage.setItem('pharmacy-theme', 'light');
        }
    }

    applyTheme(document.body.classList.contains('dark-mode') ? 'dark' : 'light');

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            applyTheme(document.body.classList.contains('dark-mode') ? 'light' : 'dark');
        });
    }
})();
</script>

</body>
</html>