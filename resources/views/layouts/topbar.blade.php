    <nav class="topbar">
        <div class="hide-for-large">
            <div class="topbar-sidebar-toggler" id="sidebarToggler" style="margin-left:0;margin-top:3px;padding:0 15px;">
                <i class="fas fa-bars"></i>
            </div>
            <style>
                .topbar-logo-mobile {
                    margin-left: 25%;
                }

                @media only screen and (min-width: 40em) {
                    .topbar-logo-mobile {
                        margin-left: 40%;
                    }
                }
            </style>
            <a href="{{ (Auth::check()) ? route('dashboard') : route('index') }}" class="topbar-logo topbar-logo-mobile">
                <img src="{{ asset('img/branding/icon_text.png') }}">
            </a>
            <div class="topbar-right">
                @guest
                    <a class="topbar-link dropdown" data-toggle="topbar-user-dropdown">
                        <i class="fas fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-content navbar-user-dropdown" id="topbar-user-dropdown">
                        <li class="dropdown-item"><a href="{{ route('login') }}">Login</a></li>
                        <li class="dropdown-item"><a href="{{ route('register') }}">Register</a></li>
                    </ul>
                @else
                    <a class="topbar-link dropdown" data-toggle="topbar-user-dropdown-mobile">
                        <img id="topbarAvatarMobile" src="{{ auth()->user()->get_headshot() }}" style="border-radius:50%;width:35px;height:35px;">
                    </a>
                    <ul class="dropdown-content navbar-user-dropdown" id="topbar-user-dropdown-mobile">
                        <li class="dropdown-item"><a href="{{ route('user.profile', auth()->user()->id) }}">Profile</a></li>
                        <li class="dropdown-item"><a href="{{ route('customize.index') }}">Character</a></li>
                        <li class="dropdown-item"><a href="{{ route('user.settings') }}">Settings</a></li>
                        <li class="dropdown-item"><a href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                @endguest
            </div>
        </div>
        <div class="show-for-large">
            <div class="topbar-left">
                <a href="{{ (Auth::check()) ? route('dashboard') : route('index') }}">
                    <img src="{{ asset('img/branding/icon_text.png') }}" style="height:60px;padding: 5px 5px;">
                </a>
                <a href="{{ (Auth::check()) ? route('dashboard') : route('index') }}" class="topbar-link"><i class="fa-duotone fa-house" style="margin-right:10px;"></i>Home</a>
                <a href="#" class="topbar-link"><i class="fa-duotone fa-gamepad-modern" style="margin-right:10px;"></i>Games</a>
                <a href="{{ route('market.index') }}" class="topbar-link"><i class="fa-duotone fa-shop" style="margin-right:10px;"></i> Market</a>
                <a href="{{ route('forum.index') }}" class="topbar-link"><i class="fa-duotone fa-messages" style="margin-right:10px;"></i> Forum</a>
                @auth
                    <a href="{{ route('upgrade.index') }}" class="topbar-link"><i class="fa-duotone fa-cart-shopping-fast" style="margin-right:10px;"></i> Upgrade</a>
                @endauth
                <a class="topbar-link dropdown" data-toggle="topbar-more-dropdown">
                    More &nbsp;<i class="fas fa-caret-down"></i>
                </a>
                <ul class="dropdown-content @auth navbar-more-dropdown-auth @else navbar-more-dropdown @endauth" id="topbar-more-dropdown">
                    @auth 
                    <li class="dropdown-item"><a href="{{ route('groups.index') }}"><i class="fa-duotone fa-people-group" style="margin-right:10px;"></i> Groups</a></li>
                    <li class="dropdown-item"><a href="{{ route('user.creator-area') }}"><i class="fa-duotone fa-file-plus" style="margin-right:10px;"></i> Create</a></li>
                    @endauth
                    <li class="dropdown-item"><a href="{{ route('users.search') }}"><i class="fa-duotone fa-users" style="margin-right:10px;"></i> Users</a></li>
                    <li class="dropdown-item"><a href="https://discord.gg/6GFAa2faMg"><i class="fa-brands fa-discord" style="margin-right:10px;"></i> Discord</a></li>
                </ul>
            </div>
            <div class="topbar-right">
                @guest
                    <a href="{{ route('login') }}" class="topbar-link">Login</a>
                    <a href="{{ route('register') }}" class="topbar-link">Register</a>
                @else
                    <a href="{{ route('messages.index', ['received', 'all']) }}" class="topbar-link" title="{{ auth()->user()->unreadMessages()->count() }} Messages" data-position="bottom" data-tooltip><i class="icon icon-inbox"></i> {{ auth()->user()->unreadMessages()->count() }}</a>
                    <a href="{{ route('user.myfriends') }}" class="topbar-link" title="{{ auth()->user()->getFriendRequests()->count() }} Friend Requests" data-position="bottom" data-tooltip><i class="icon icon-friends"></i> {{ auth()->user()->getFriendRequests()->count() }}</a>
                    <a href="{{ route('user.money') }}" class="topbar-link" title="{{ number_format(auth()->user()->cash) }} Cash" data-position="bottom" data-tooltip><i class="currency currency-cash currency-sm currency-align"></i> {{ auth()->user()->get_short_num(auth()->user()->cash) }}</a>
                    <a href="{{ route('user.money') }}" class="topbar-link" title="{{ number_format(auth()->user()->coins) }} Coins" data-position="bottom" data-tooltip><i class="currency currency-coin currency-sm currency-align"></i> {{ auth()->user()->get_short_num(auth()->user()->coins) }}</a>
                    <a class="topbar-link dropdown" data-toggle="topbar-user-dropdown">
                        <img id="topbarAvatar" src="{{ auth()->user()->get_headshot() }}" style="border-radius:50%;width:25px;height:25px;margin-right:5px;">
                        {{ Str::limit(Auth::user()->username, 10, '...') }} &nbsp;
                        <i class="fas fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-content navbar-user-dropdown" id="topbar-user-dropdown">
                        <li class="dropdown-item"><a href="{{ route('user.profile', auth()->user()->id) }}"><i class="fa-duotone fa-user" style="margin-right:10px;"></i> Profile</a></li>
                        <li class="dropdown-item"><a href="{{ route('customize.index') }}"><i class="fa-duotone fa-user-pen" style="margin-right:10px;"></i> Character</a></li>
                        <li class="dropdown-item"><a href="{{ route('user.settings') }}"><i class="fa-duotone fa-gear" style="margin-right:10px;"></i> Settings</a></li>
                        @if (Auth::user()->power > 0)
                        <li class="dropdown-item"><a href="{{ route('ais.index') }}"><i class="fa-duotone fa-star-shooting" style="margin-right:10px;"></i> Admin</a></li>
                        @endif
                        <li class="dropdown-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa-duotone fa-right-from-bracket" style="margin-right:10px;"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                @endguest
            </div>
        </div>
    </nav>
    <div class="topbar-push"></div>