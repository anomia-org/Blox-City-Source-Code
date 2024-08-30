<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} / Antelope Internal Systems</title>

    <!-- PRECONNECT -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">

    <!-- META -->
    <link rel="shortcut icon" href="{{ config('site.icon') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FONTS -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;0,600;0,700;1,500;1,600;1,700&amp;display=swap">

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css?v=6') }}">
</head>
<body>
        <nav class="navbar navbar-expand">
            <div class="container">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="https://www.bloxcity.com/" class="nav-link @if(request()->is('admin')) active @endif"><i class="fas fa-arrow-left"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ais.index') }}" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ais.info') }}" class="nav-link">Info</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="#" class="dropdown-toggle username">
                            <div class="username-holder">{{ auth()->user()->username }}</div>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main-holder container">
            {{ $slot }}
        </div>

        <div class="footer-push"></div>
        <footer>
            <div>© {{ date('Y') }} BLOX City. All rights reserved.</div>
            <div style="font-size:14px;">Sharing photos and videos of this panel is strictly prohibited and doing so will cause you to lose your administrative privileges.</div>
        </footer>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    @include('components.toastr')

    @if(isset($script) && $script)
        {{ $script }}
    @endif

</body>
</html>