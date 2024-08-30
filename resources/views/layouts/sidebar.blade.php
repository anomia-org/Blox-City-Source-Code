<div class="sidebar hide-for-large hide">
    <div class="sidebar-inner">
        <ul class="sidebar-items">
            <li class="item">
                <a href="{{ (Auth::check()) ? route('dashboard') : route('index') }}">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="item">
                <a href="#">
                    <i class="fas fa-gamepad"></i>
                    <span>Games</span>
                </a>
            </li>
            <li class="item">
                <a href="{{ route('market.index') }}">
                    <i class="fas fa-store-alt"></i>
                    <span>Market</span>
                </a>
            </li>
            <li class="item">
                <a href="{{ route('forum.index') }}">
                    <i class="fas fa-comments"></i>
                    <span>Forum</span>
                </a>
            </li>
            <li class="item">
                <a href="{{ route('users.search') }}">
                    <i class="fas fa-search"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="item">
                <a href="{{ route('upgrade.index') }}">
                    <i class="fas fa-shopping-basket"></i>
                    <span>Upgrade</span>
                </a>
            </li>
            @auth
                <li class="item">
                    <a href="{{ route('user.creator-area') }}">
                        <i class="fas fa-plus"></i>
                        <span>Create</span>
                    </a>
                </li>
                <li class="item">
                    <a href="{{ route('groups.index') }}">
                        <i class="fas fa-people-group"></i>
                        <span>Groups</span>
                    </a>
                </li>
            @endauth
            <li class="item">
                <a href="{{ config('blox.domains.blog') }}">
                    <i class="fas fa-pencil"></i>
                    <span>Blog</span>
                </a>
            </li>
        </ul>
        @auth
            <div class="sidebar-divider"></div>
            <ul class="sidebar-items">
                <li class="item">
                    <a href="{{ route('user.money') }}">
                        <i class="currency currency-cash currency-sm currency-align"></i>
                        <span>{{ auth()->user()->get_short_num(auth()->user()->cash) }} Cash</span>
                    </a>
                </li>
                <li class="item">
                    <a href="{{ route('user.money') }}">
                        <i class="currency currency-coin currency-lg currency-align"></i>
                        <span>{{ auth()->user()->get_short_num(auth()->user()->coins) }} Coins</span>
                    </a>
                </li>
                <li class="item">
                    <a href="{{ route('user.myfriends') }}">
                        <i class="icon icon-friends"></i>
                        <span>{{ auth()->user()->getFriendRequests()->count() }} Friend Requests</span>
                    </a>
                </li>
                <li class="item">
                    <a href="{{ route('messages.index', ['received', 'all']) }}">
                        <i class="icon icon-inbox"></i>
                        <span>{{ auth()->user()->unreadMessages()->count() }} Messages</span>
                    </a>
                </li>
                @if (Auth::user()->power > 0)
                    <li class="item">
                        <a href="{{ route('ais.index') }}">
                            <i class="icon icon-staff"></i>
                            <span>Panel</span>
                        </a>
                    </li>
                @endif
            </ul>
        @endauth
    </div>
</div>
