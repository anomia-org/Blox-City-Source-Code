<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
use App\Models\User;
use Carbon\Carbon;
//use DB;
if(Auth::check()) {
    $expiresAt = Carbon::now()->addMinutes(2);
    User::where('id', Auth::user()->id)->update(array('last_online' => $expiresAt));
}


?>
<!DOCTYPE html>
<html lang="en" class="auto-scaling-disabled">
<head>
    <!-- Meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta name="viewport" content="width=device-width" />

    <title>@yield('title') - BLOX City</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icon -->
    <link rel="icon" href="/static/img/bv_light.png" />

    <!-- Javascript -->
    <!-- <script src="/static/js/app.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/halfmoon@1.1.1/js/halfmoon.js"></script>
    <script src="/static/js/bv-base.js?r=<?php echo mt_rand(100000, 999999);?>"></script>


    <!--google fonts-->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Wendy+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/static/css/bv-base/halfmoon.css?r=<?php echo mt_rand(100000, 999999);?>">
    @auth <link rel="stylesheet" type="text/css" href="{{ \Illuminate\Support\Facades\Auth::user()->theme() }}"> @endauth
    @guest<link rel="stylesheet" type="text/css" href="/static/css/bv-base/buildaverse.css?r=<?php echo mt_rand(100000, 999999);?>"> @endguest
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.1/css/all.css"/>
    @livewireStyles


</head>

<body class="@auth @if(\Illuminate\Support\Facades\Auth::user()->theme == 1) dark-mode @endif @endauth @guest dark-mode @endguest with-custom-webkit-scrollbars with-custom-css-scrollbars" data-dm-shortcut-enabled="true" data-sidebar-shortcut-enabled="true">

@yield('modals')

<!-- Page wrapper start -->
<div id="page-wrapper" class="page-wrapper with-navbar with-sidebar with-transitions" data-sidebar-type="overlayed-sm-and-down">

    <!-- Sticky alerts -->
    <div class="sticky-alerts"></div>

    <!-- Navbar start -->
    <nav class="navbar header">
        <div class="navbar-content hidden-md-and-up">
            <button id="toggle-sidebar-btn" class="btn btn-action" type="button" onclick="halfmoon.toggleSidebar()">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
        </div>
        <a href="/" class="navbar-brand ml-10 ml-sm-20">
            <img src="/static/img/bv_light_long.png?r=1" class="hidden-dm">
            <img src="/static/img/bv_dark_long.png?r=1" class="hidden-lm">
        </a>
        <!-- Left side of navigation -->
        <ul class="navbar-nav d-none d-md-flex flex-fill">
            <div class="hidden-md-and-down search-input">
                <i class="far fa-search"></i>
                <input class="search-input-field" type="text" placeholder="Search for anything on Buildaverse...">
            </div>
        </ul>
        <!-- Right side of navigation -->
        <div class="navbar-content ml-auto">
            @auth
                <div class="nav-btn-group hidden-md-and-down">
                    <div class="nav-group-item primary">
                        <div class="nav-avatar">
                            <img class="img-fluid rounded-circle" src="/static/img/headshot.png">
                        </div>
                        <div class="nav-text">{{ auth()->user()->username }}</div>
                        <div class="nav-btn">
                            <span class="font-size-16 material-icons mt-4">edit</span>
                        </div>
                    </div>
                    <div class="nav-group-item">
                        <i class="font-size-24 cash mr-10 fas fa-money-bill"></i>
                        <div class="nav-text">{{ auth()->user()->get_short_num(auth()->user()->cash) }}</div>
                    </div>
                    <div class="nav-group-item">
                        <i class="font-size-24 coin mr-10 fas fa-coins"></i>
                        <div class="nav-text">{{ auth()->user()->get_short_num(auth()->user()->coins) }}</div>
                    </div>
                </div>
                <div class="nav-btn-group">
                    <div class="nav-group-item-button">
                        <span class="font-size-24 material-icons">notifications</span>
                        <div class="notification-badge">9+</div>
                    </div>
                    <div class="nav-group-item-button">
                        <a href="{{ route('user.friends') }}" class="no-style"><span class="font-size-24 mt-5 material-icons">group_add</span></a>
                        @if(auth()->user()->getPendingsCount() > 0)
                            @if(auth()->user()->getPendingsCount() < 100)
                                <div class="notification-badge">{{ auth()->user()->getPendingsCount() }}</div>
                            @else
                                <div class="notification-badge">99+</div>
                            @endif
                        @endif
                    </div>
                    <div class="nav-group-item-button">
                        <span class="font-size-24 material-icons">swap_horiz</span>
                        <div class="notification-badge">2</div>
                    </div>
                    <div class="dropdown with-arrow">
                        <div class="nav-group-item-button" data-toggle="dropdown">
                            <span class="font-size-24 material-icons">settings</span>
                        </div>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- mobile only -->
                            <a href="#" class="dropdown-item cash hidden-md-and-up"><i class="fas fa-money-bill"></i> {{ Auth::user()->get_short_num(Auth::user()->cash) }}</a>
                            <a href="#" class="dropdown-item coin hidden-md-and-up"><i class="fas fa-coins"></i> {{ Auth::user()->get_short_num(Auth::user()->coins) }}</a>
                            <!-- end mobile only -->
                            <a href="{{ route('user.settings') }}" class="dropdown-item"><i class="fas fa-cog"></i> Settings</a>
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary mr-5" role="button">Register</a>
                <a href="{{ route('login') }}" class="btn btn-success" role="button">Login</a>
            @endguest
        </div>
    </nav>
    <!-- Navbar end -->

    <!-- Sidebar start -->
    <div class="sidebar">
            <ul>
                <li>
                    <a href="#" class="no-style">
                        <div class="nav-sidebar-icon">rocket_launch</div>
                        <div class="nav-sidebar-text">Worlds</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('market.index') }}" class="no-style">
                        <div class="nav-sidebar-icon">store</div>
                        <div class="nav-sidebar-text">Market</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('forum.index') }}" class="no-style">
                        <div class="nav-sidebar-icon">question_answer</div>
                        <div class="nav-sidebar-text">Forum</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guilds.index') }}" class="no-style">
                        <div class="nav-sidebar-icon">castle</div>
                        <div class="nav-sidebar-text">Guilds</div>
                    </a>
                </li>
                @auth
                <li>
                    <a href="{{ route('user.creator-area.index') }}" class="no-style">
                        <div class="nav-sidebar-icon">draw</div>
                        <div class="nav-sidebar-text">Create</div>
                    </a>
                </li>
                @endauth
                <div class="flex-fill"></div>
                @auth
                <li class="nav-item-special">
                    <a href="#" class="no-style">
                        <div class="nav-sidebar-icon">star</div>
                        <div class="nav-sidebar-text">Upgrade</div>
                    </a>
                </li>
                @endauth
                <li>
                    <a href="#" class="no-style">
                        <div class="nav-sidebar-icon">rss_feed</div>
                        <div class="nav-sidebar-text">Blog</div>
                    </a>
                </li>
            </ul>
    </div>
    <!-- Sidebar end -->

    <!-- Content wrapper start -->
    <div class="content-wrapper">



        @if(\Illuminate\Support\Facades\DB::table('site_settings')->where('banner_enabled', '=', '1')->exists())
            @php
                $settings = \Illuminate\Support\Facades\DB::table('site_settings')->where('banner_enabled', '=', '1')->first();
            @endphp
            <div class="banner {{ $settings->banner_color }}">
                {!! $settings->banner_text !!}
            </div>
        @endif

        <!-- Container-fluid -->
        <div class="container-fluid">

            @yield('content')

        </div>
        <!-- Footer start -->
        <div class="custom-footer">
            <div class="container-fluid">
                <div class="row row-eq-spacing-lg">
                    <div class="col-lg-3">
                        <div class="content">
                            <h4 class="content-title font-size-16 mb-10">Navigate</h4>
                            <div>
                                <a href="{{ route('index') }}" class="custom-footer-link">Home</a>
                            </div>
                            <div>
                                <a href="#" class="custom-footer-link">Market</a>
                            </div>
                            <div>
                                <a href="{{ route('forum.index') }}" class="custom-footer-link">Forum</a>
                            </div>
                            <div>
                                <a href="#" class="custom-footer-link">Upgrade</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="content">
                            <h4 class="content-title font-size-16 mb-10">About</h4>
                            <div>
                                <a href="#" class="custom-footer-link">About Us</a>
                            </div>
                            <div>
                                <a href="#" class="custom-footer-link">Blog</a>
                            </div>
                            <div>
                                <a href="#" class="custom-footer-link">Corporate</a>
                            </div>
                            <div>
                                <a href="#" class="custom-footer-link">Careers</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="content">
                            <h4 class="content-title font-size-16 mb-10">Legal</h4>
                            <div>
                                <a href="#" class="custom-footer-link">Terms of Service</a>
                            </div>
                            <div>
                                <a href="#" class="custom-footer-link">Privacy Policy</a>
                            </div>
                            <div>
                                <a href="#" class="custom-footer-link">DMCA</a>
                            </div>
                            <div>
                                <a href="mailto:helpme@bloxcity.com" class="custom-footer-link">Contact Us</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="content">
                            <h4 class="content-title font-size-16 mb-10">Say Hello</h4>
                            <div>
                                <a href="https://discord.gg/buildaverse" target="_blank" rel="noopener" class="custom-footer-link">
                                    <i class="fab fa-discord" aria-hidden="true"></i> Discord
                                </a>
                            </div>
                            <div>
                                <a href="#" target="_blank" rel="noopener" class="custom-footer-link">
                                    <i class="fab fa-youtube" aria-hidden="true"></i> YouTube
                                </a>
                            </div>
                            <div>
                                <a href="https://twitter.com/playbuildaverse" target="_blank" rel="noopener" class="custom-footer-link">
                                    <i class="fab fa-twitter" aria-hidden="true"></i> Twitter
                                </a>
                            </div>
                            <div>
                                <a href="https://twitch.com/playbuildaverse" target="_blank" rel="noopener" class="custom-footer-link">
                                    <i class="fab fa-twitch" aria-hidden="true"></i> Twitch
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <p class="text-center text-muted">© Copyright 2022 Buildaverse. All rights reserved.</p>
            </div>
        </div>
        <!-- Footer end -->
    </div>
    <!-- Content wrapper end -->

</div>
<!-- Page wrapper end -->

@livewireScripts
</body>
</html>
