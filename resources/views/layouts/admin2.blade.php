<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} / Antelope Internal Systems</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/branding/favicon.png') }}" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?r=6" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v6.1.1/css/pro.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.min.css" rel="stylesheet">
</head>

<body {{ $attributes }}>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow border-bottom d-md-none">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="{{ route('ais.index') }}">
            <img class="navbar-brand-logo" src="{{ asset('img/branding/ais.png') }}">
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </header>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block mt-0 sidebar collapse d-md-flex flex-column flex-shrink-0 ">
                <div class="position-sticky pt-3">
                    <div class="sidebar-brand d-none d-md-block">
                        <a href="{{ route('ais.index') }}"><img class="sidebar-brand-logo" src="{{ asset('img/branding/ais.png') }}"></a>
                        <hr class="mb-0">
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/')) active @endif" aria-current="page" href="{{ route('ais.index') }}">
                                <i class="bi-house-door"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/reports*')) active @endif" href="{{ route('ais.reports') }}">
                                <i class="bi-shield-exclamation"></i>
                                Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/users*')) active @endif" href="{{ route('ais.users') }}">
                                <i class="bi-people"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/ips*')) active @endif" href="{{ route('ais.ips') }}">
                                <i class="bi-terminal"></i>
                                IPs
                            </a>
                        </li>
                    </ul>
                    <h6 class="sidebar-heading mt-4 mb-1 text-muted text-uppercase"><span>User Created Content</span></h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/clothing*')) active @endif" href="{{ route('ais.clothing') }}">
                                <i class="bi-bag"></i>
                                Clothing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/guilds*')) active @endif" href="{{ route('ais.guilds') }}">
                                <i class="bi-person-video2"></i>
                                Guilds
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/comments*')) active @endif" href="{{ route('ais.comments') }}">
                                <i class="bi-chat-left-text"></i>
                                Comments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/blurbs*')) active @endif" href="{{ route('ais.blurbs') }}">
                                <i class="bi-chat-right-quote-fill"></i>
                                Blurbs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/ads*')) active @endif" href="{{ route('ais.ads') }}">
                                <i class="bi-badge-ad"></i>
                                Ads
                            </a>
                        </li>
                    </ul>
                    @if(auth()->user()->power > 3)
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center mt-4 mb-1 text-muted text-uppercase">
                        <span>Executive Tools</span>
                        <a class="link text-muted d-none" href="#" aria-label="Info">
                            <i class="bi-info-circle"></i>
                        </a>
                    </h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/executive/settings*')) active @endif" href="{{ route('ais.settings') }}">
                                <i class="bi-gear"></i>
                                Site Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->is('admin/executive/directory*')) active @endif" href="#">
                                <i class="bi-person-badge"></i>
                                Staff Directory
                            </a>
                        </li>
                    </ul>
                    @endif
                    <h6 class="sidebar-heading mt-4 mb-1 text-muted text-uppercase"><span>Helpful Links</span></h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi-link-45deg"></i>
                                Moderation Guide
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi-link-45deg"></i>
                                Staff Rules & Policies
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.bloxcity.com/">
                                <i class="bi-link-45deg"></i>
                                BLOX City Website
                            </a>
                        </li>
                    </ul>
                    <div class="sidebar-user mt-3">
                        <hr class="mt-0">
                        <img src="{{ auth()->user()->get_headshot() }}" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong>Kyle</strong>&nbsp;<span class="font-weight-normal text-danger">[L{{ auth()->user()->power }}]</span>
                    </div>
                </div>
            </nav>

            <!-- BEGIN CONTENT -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">{{ $title }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted">A.IS v0.01</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
                {{ $slot }}
            </main>
            <!-- END CONTENT -->

            <!-- JavaScript -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <script src="{{ asset('js/app.js') }}"></script>
            <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
            <script src="{{ asset('js/custom.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
            @include('components.toastr')

            @if(isset($script) && $script)
            {{ $script }}
            @endif
</body>

</html>