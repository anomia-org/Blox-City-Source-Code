<!-- Top nav-->
<div class="navbar navbar-dark sticky-top bg-dark d-none d-md-flex flex-md-nowrap p-0 border-bottom">
    <a class="navbar-brand col-auto me-0 px-3" href="/"><img class="d-none d-md-block" src="{{ asset('img/branding/long_logo_text.png') }}" width="150px"></a>
    <div class="col-auto d-md-none"></div>
    <input class="form-control form-control-dark w-100 d-none d-md-block" type="text" placeholder="Search anything on BLOX City..." aria-label="Search">

    @auth
    <!-- Top Nav Menus-->
    <div class="col-auto">
        <!-- Notification Nav Section-->
        <div class="navbar-nav d-none d-md-inline-block">
            <div class="nav-item text-nowrap nav-pill">
                <!-- Bell icon -->
                <span class="dropdown">
                    <a class="nav-pill-section nav-pill-icon-left position-relative icon-dropdown" href="#" role="button" id="bellDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi-bell nav-pill-section-icon" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                        </svg>
                        @if(auth()->user()->unread_notifications()->count() >= 1)
                            <span class="position-absolute top-30 start-75 translate-middle badge border border-dark rounded-circle bg-primary p-1">
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        @endif
                    </a>
                    <!-- Bell Dropdown -->
                    <ul class="dropdown-menu notification-dropdown" aria-labelledby="bellDropdown" data-bs-popper="static">
                        <li class="text-center">
                            <span class="dropdown-item-text notification-dropdown-title">Notifications @if(auth()->user()->unread_notifications()->count() >= 1) <span class="badge rounded-pill bg-primary notif-badge">{{ auth()->user()->get_short_num(auth()->user()->unread_notifications()->count()) }}</span> @endif </span></li>
                        <li>
                            <hr class="dropdown-divider mb-0">
                        </li>
                        @if(auth()->user()->unread_notifications()->count() >= 1)
                            @foreach(auth()->user()->unread_notifications()->paginate(5) as $notif)
                                @if(!($notif->type == 6 || $notif->type == 7))
                                <li class="notif @if(!$notif->read) notif-new @endif">
                                    <a href="{{ $notif->url }}" class="text-decoration-none notif-link">
                                        <div class="d-flex pt-3 border-bottom w-100">
                                            <img class="notif-pfp" src="{{ $notif->from->get_headshot() }}">
                                            <div class="pb-3 mb-0 small lh-sm w-100 notif-text">
                                                <div class="text-light">{{ $notif->message }}</div>
                                                <span class="d-block text-muted">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                @elseif(($notif->type == 6 || $notif->type == 7))
                                <li class="notif @if(!$notif->read) notif-new @endif">
                                    <a href="#" class="text-decoration-none notif-link">
                                        <div class="d-flex pt-3 border-bottom w-100">
                                            <img class="notif-icon notif-icon-primary" src="{{ asset('img/icons/award.png') }}">
                                            <div class="pb-3 mb-0 small lh-sm w-100 notif-text">
                                                <div class="text-light notif-truncate">You recieved the <strong>Friends badge</strong></div>
                                                <span class="d-block text-muted">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                @endif
                            @endforeach
                        @endif
                        <li class="text-center">
                            <div class="dropdown-item-text notif-info">
                                @if(auth()->user()->unread_notifications()->count() < 1) You're all caught up!<br> @endif
                                <a href="{{ route('notifications') }}">View All</a>&nbsp;&nbsp;
                                @if(auth()->user()->unread_notifications()->count() >= 1)
                                <a href="#" onclick="event.preventDefault();document.getElementById('mark-all').submit();" style="cursor:pointer;">Mark All as Read</a>
                                <form method="POST" id="mark-all" action="{{ route('user.notifications.read') }}" class="d-none">
                                    @csrf
                                </form>
                                @endif
                            </div>
                        </li>
                    </ul>
                </span>
                <!-- End notifications dropdown -->

                <!-- Begin friends dropdown -->
                <span class="dropdown">

                    <!-- Friend icon -->
                    <a class="nav-pill-section position-relative icon-dropdown" href="#" role="button" id="friendDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi-people nav-pill-section-icon" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                        </svg>
                        @if(auth()->user()->getFriendRequests()->count() > 0)
                        <span class="position-absolute top-30 start-55 translate-middle badge border border-dark rounded-circle bg-primary p-1">
                            <span class="visually-hidden">unread messages</span>
                        </span>
                        @endif
                    </a>

                    <!-- Friend Dropdown -->
                    <ul class="dropdown-menu notification-dropdown" aria-labelledby="friendDropdown" data-bs-popper="static">
                        <li class="text-center">
                            <span class="dropdown-item-text notification-dropdown-title">Friend Requests</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider mb-0">
                        </li>
                        @if(auth()->user()->getFriendRequests()->count() >= 1)
                            @foreach(auth()->user()->getFriendRequests(5) as $request)
                                <li class="notif">
                                    <div class="d-flex pt-3 border-bottom w-100">
                                        <a href="{{ route('user.profile', $request->sender->id) }}"><img class="notif-pfp" src="{{ $request->sender->get_headshot() }}"></a>
                                        <div class="pb-3 mb-0 small lh-sm w-100 notif-text">
                                            <div class="text-light">
                                                From <a href="{{ route('user.profile', $request->sender->id) }}" class="text-light"><strong>{{ $request->sender->username }}</strong></a><span class="text-muted"> • {{ $request->created_at->diffForHumans() }}</span>
                                            </div>
                                            <a class="text-success" onclick="event.preventDefault();document.getElementById('accept-{{ $request->sender->id }}').submit();" style="cursor:pointer;">Accept</a> or <a class="text-danger" onclick="event.preventDefault();document.getElementById('decline-{{ $request->sender->id }}').submit();" style="cursor:pointer;">Decline</a>
                                            <form method="POST" id="accept-{{ $request->sender->id }}" action="{{ route('user.friends.accept', $request->sender->id) }}" class="d-none">
                                                @csrf
                                            </form>
                                            <form method="POST" id="decline-{{ $request->sender->id }}" action="{{ route('user.friends.decline', $request->sender->id) }}" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                        <li class="text-center">
                            <div class="dropdown-item-text notif-info">

                                @if(auth()->user()->getFriendRequests()->count() >= 1)
                                <a href="{{ route('user.myfriends') }}">View All</a>&nbsp;&nbsp;
                                <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#acceptFriendsModal">Accept All</a>&nbsp;&nbsp;
                                <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#declineFriendsModal">Decline All</a>
                                @else
                                You're all caught up!
                                <br />
                                <a href="{{ route('user.myfriends') }}">View All</a>
                                @endif
                            </div>
                        </li>
                    </ul>
                </span>

                <!-- End friends dropdown -->

                <span class="dropdown">
                    <!-- Messages icon -->
                    <a class="nav-pill-section nav-pill-icon-right position-relative icon-dropdown" href="#" style="position: relative; right: 1px" role="button" id="messagesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-envelope"></i>
                        @if(auth()->user()->unreadMessages()->count() >= 1) <span class="position-absolute top-30 start-60 translate-middle badge border border-dark rounded-circle bg-primary p-1"><span class="visually-hidden">unread messages</span></span> @endif
                    </a>
                    <!-- Messages Dropdown -->
                    <ul class="dropdown-menu notification-dropdown" aria-labelledby="messagesDropdown" data-bs-popper="static">
                        <li class="text-center">
                            <span class="dropdown-item-text notification-dropdown-title">Messages @if(auth()->user()->unreadMessages()->count() >= 1) <span class="badge rounded-pill bg-primary notif-badge">{{ auth()->user()->get_short_num(auth()->user()->unreadMessages()->count()) }}</span> @endif </span>
                        </li>
                        <li>
                            <hr class="dropdown-divider mb-0" />
                        </li>
                        @if(auth()->user()->unreadMessages()->count() >= 1)
                            @foreach(auth()->user()->unreadMessages()->paginate(5) as $message)
                            <li class="notif">
                                <a href="#" class="text-decoration-none">
                                    <div class="d-flex pt-3 border-bottom w-100">
                                        <img class="notif-pfp" src="{{ $message->fromUser->get_headshot() }}" />
                                        <div class="pb-3 mb-0 small lh-sm w-100 notif-text">
                                            <div class="text-light notif-truncate">
                                                From <strong>{{ $message->fromUser->username }}</strong>
                                            </div>
                                            <span class="text-muted">{{ $message->created_at->diffForHumans() }} • <a href="{{ route('messages.view', $message->id) }}">View</a></span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        @endif
                        <li class="text-center">
                            <div class="dropdown-item-text notif-info">
                                @if(auth()->user()->unreadMessages()->count() < 1) You're all caught up!<br /> @endif
                                <a href="{{ route('messages.index', ['received', 'all']) }}">All Messages</a>
                            </div>
                        </li>
                    </ul>
                </span>


            </div>

        </div>

        <!-- Money Section-->
        <div class="navbar-nav d-none d-md-inline-block">
            <div class="nav-item text-nowrap nav-pill">
                <a href="{{ route('user.money') }}" class="nav-pill-section nav-pill-icon-left font-weight-normal" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ number_format(auth()->user()->cash) }} Cash">
                    <i class="currency currency-cash currency-lg currency-align"></i>
                    <span>&nbsp;{{ auth()->user()->get_short_num(auth()->user()->cash) }}</span>
                </a>

                <a href="{{ route('user.money') }}" class="nav-pill-section nav-pill-icon-right font-weight-normal" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ number_format(auth()->user()->coins) }} Coins">
                    <i class="currency currency-coin currency-lg currency-align"></i>
                    <span>&nbsp;{{ auth()->user()->get_short_num(auth()->user()->coins) }}</span>
                </a>
            </div>
        </div>

        <!-- End money section -->

        <!-- User Section-->
        <div class="navbar-nav dropdown d-inline-block">
            <div class="nav-item text-nowrap nav-pill" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="position: relative; bottom: 1px;">
                <a href="#" class="nav-link nav-user">
                    <div class="nav-user-username d-none d-md-inline-block text-light">{{ auth()->user()->username }}</div>
                    <img class="nav-pfp" src="{{ auth()->user()->get_headshot() }}">
                </a>
            </div>
            <ul class="dropdown-menu dropdown-menu-end user-dropdown" aria-labelledby="userDropdown" data-bs-popper="static">
                <li><a class="dropdown-item" href="{{ route('user.profile', auth()->user()->id) }}"><i class="bi bi-person"></i> Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('customize.index') }}"><i class="bi bi-pencil-square"></i> Edit Character</a></li>
                <li><a class="dropdown-item" href="{{ route('user.settings') }}"><i class="bi bi-gear"></i> Account Settings</a></li>
                @if(auth()->user()->power > 0)
                    <li><a class="dropdown-item text-warning" href="https://east.bloxcity.com" target="_blank"><i class="bi bi-arrow-right-circle"></i> Admin Panel</a></li>
                @endif
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i> Log out</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </ul>
        </div>
        <!-- end user section -->
    </div>

    <!-- end top nav menus -->

    <!-- Decline All Trades Modal -->
    <div class="modal fade" id="declineTradesModal" tabindex="-1" aria-labelledby="declineTradesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-5">
                    <h5 class="mb-4">Are you sure that you want <br class="d-none d-md-inline-block">to decline all incoming trades?</h5>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, nevermind</button>
                    <button type="button" class="btn btn-danger">Decline all</button>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->getFriendRequests()->count() >= 1)
    <!-- Accept All Friends Modal -->
    <div class="modal fade" id="acceptFriendsModal" tabindex="-1" aria-labelledby="acceptFriendsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body text-center py-5">
                    <h5 class="mb-4">
                        Are you sure that you want to <br class="d-none d-md-inline-block">accept all incoming friend requests?
                    </h5>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, nevermind</button>
                    <button type="button" class="btn btn-primary" onclick="event.preventDefault();document.getElementById('accept-fr-all').submit();">Accept all</button>
                    <form method="POST" id="accept-fr-all" class="d-none" action="{{ route('user.friends.accept.all') }}">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline All Friends Modal -->
    <div class="modal fade" id="declineFriendsModal" tabindex="-1" aria-labelledby="declineFriendsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body text-center py-5">
                    <h5 class="mb-4">
                        Are you sure that you want to <br class="d-none d-md-inline-block">decline all incoming friend requests?
                    </h5>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, nevermind</button>
                    <button type="button" class="btn btn-danger" onclick="event.preventDefault();document.getElementById('decline-fr-all').submit();">Decline all</button>
                    <form method="POST" id="decline-fr-all" class="d-none" action="{{ route('user.friends.decline.all') }}">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @endauth

</div>

<!-- Side nav-->
<div class="sidebar-container d-none d-md-flex">
    <div class="d-flex flex-column flex-shrink-0 sidebar">
        <ul class="nav nav-pills nav-flush flex-column mb-auto text-center mt-2">
            <li class="nav-item">
                <a href="/" class="nav-link sidebar-icon @if(request()->is('dashboard*')) side-nav-active @endif my-3 py-0" aria-current="page" title="Home" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                    <h4 class="m-0"><i class="bi bi-house-door"></i></h4>
                </a>
            </li>
            <li>
                <a href="{{ route('market.index') }}" class="nav-link sidebar-icon @if(request()->is('market*')) side-nav-active @endif my-3 py-0" title="Marketplace" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Marketplace">
                    <h4 class="m-0"><i class="bi bi-shop-window"></i></h4>
                </a>
            </li>
            <li>
                <a href="{{ route('forum.index') }}" class="nav-link sidebar-icon @if(request()->is('forum*')) side-nav-active @endif my-3 py-0" title="Forums" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Forums">
                    <h4 class="m-0"><i class="bi bi-menu-up"></i></h4>
                </a>
            </li>
            <li>
                <a href="{{ route('guilds.index') }}" class="nav-link sidebar-icon @if(request()->is('guilds*')) side-nav-active @endif my-3 py-0" title="Guilds" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Guilds">
                    <h4 class="m-0"><i class="bi bi-person-video2"></i></h4>
                </a>
            </li>
            @auth
            <li>
                <a href="{{ route('user.creator-area.index') }}" class="nav-link sidebar-icon @if(request()->is('creator-area*')) side-nav-active @endif my-3 py-0" title="Create" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Create">
                    <h4 class="m-0"><i class="bi bi-pencil"></i></h4>
                </a>
            </li>
            @endauth
        </ul>
        <div>
            <ul class="nav nav-pills nav-flush flex-column mb-auto text-center mt-2">
                @auth
                <li class="nav-item">
                    <a href="#" class="nav-link sidebar-icon my-3 py-0" aria-current="page" title="Upgrade" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Upgrade">
                        <h4 class="m-0 text-warning"><i class="bi bi-stars"></i></h4>
                    </a>
                </li>
                @endauth
                <li class="nav-item">
                    <a href="https://blog.bloxcity.com/" target="_blank" rel="noopener noreferrer" class="nav-link sidebar-icon my-3 py-0" aria-current="page" title="Blog" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Blog">
                        <h4 class="m-0"><i class="bi bi-rss"></i></h4>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!--Mobile Nav-->
<div class="d-md-none">
    <header class="mobile-nav navbar navbar-dark bg-dark flex-nowrap px-3  border-top">
        <div class="container mobile-nav-container">
            <!-- Home -->
            <div class="col-auto">
                <a href="{{ route('index') }}" class="nav-link mobile-nav-link-active p-0">
                    <h3 class="m-0"><i class="bi bi-house-door"></i></h3>
                </a>
            </div>
            <!-- Search -->
            <div class="col-auto">
                <a href="#" class="nav-link mobile-nav-link p-0">
                    <h3 class="m-0"><i class="bi bi-compass"></i></h3>
                </a>
            </div>
            <!-- Marketplace -->
            <div class="col-auto">
                <a href="{{ route('market.index') }}" class="nav-link mobile-nav-link p-0">
                    <h3 class="m-0"><i class="bi bi-shop-window"></i></h3>
                </a>
            </div>
            <!-- Forum -->
            <div class="col-auto">
                <a href="{{ route('forum.index') }}" class="nav-link mobile-nav-link p-0">
                    <h3 class="m-0"><i class="bi bi-menu-up"></i></h3>
                </a>
            </div>
            <!-- User -->
            <div class="col-auto dropup">
                <a href="#" class="nav-link mobile-nav-link p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <h3 class="m-0"><i class="bi bi-person-circle"></i></h3>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    @auth
                    <li><span class="dropdown-item-text"><b>{{ auth()->user()->username }}</b></span></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><span class="dropdown-item-text text-small text-muted text-bold" href="#">MONEY</span></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('user.money') }}">
                            <i class="currency currency-cash currency-lg currency-align"></i>&nbsp;{{ auth()->user()->get_short_num(auth()->user()->cash) }}&nbsp;&nbsp;&nbsp;
                            <i class="currency currency-coin currency-lg currency-align"></i>&nbsp;{{ auth()->user()->get_short_num(auth()->user()->coins) }}</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><span class="dropdown-item-text text-small text-muted text-bold" href="#">UPDATES</span></li>
                    <li><a class="dropdown-item" href="{{ route('notifications') }}"><i class="bi bi-bell"></i> Notifications
                    @if(auth()->user()->unread_notifications()->count() > 0)
                        @if(auth()->user()->unread_notifications()->count() < 100)
                        <span class="badge rounded-pill bg-primary notif-badge">{{ auth()->user()->unread_notifications()->count() }}</span>
                        @else
                        <span class="badge rounded-pill bg-primary notif-badge">99+</span>
                        @endif
                    @endif
                </a></li>
                    <!-- <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-left-right"></i> Incoming Trades<span class="badge rounded-pill bg-primary notif-badge">2</span></a></li> -->
                    <li>
                        <a class="dropdown-item" href="{{ route('user.myfriends') }}"><i class="bi bi-people"></i> Friend Requests
                            @if(auth()->user()->getFriendRequests()->count() > 0)
                                @if(auth()->user()->getFriendRequests()->count() < 100) <span class="badge rounded-pill bg-primary notif-badge">{{ auth()->user()->getFriendRequests()->count() }}</span>
                                @else
                                <span class="badge rounded-pill bg-primary notif-badge">99+</span>
                                @endif
                                @endif
                        </a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('messages.index', ['received', 'all']) }}"><i class="bi-envelope"></i> Messages</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><span class="dropdown-item-text text-small text-muted text-bold" href="#">LINKS</span></li>
                    <li><a class="dropdown-item" href="{{ route('guilds.index') }}"><i class="bi bi-person-video2"></i> Guilds</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.profile', auth()->user()->id) }}"><i class="bi bi-person"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('customize.index') }}"><i class="bi bi-pencil-square"></i> Edit Character</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.settings') }}"><i class="bi bi-gear"></i> Account Settings</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-stars"></i> Upgrade</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i> Log out</a></li>
                    @endauth
                    @guest
                    <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                    <li><a class="dropdown-item" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> Register</a></li>
                    @endguest
                </ul>
            </div>
            <!-- End user -->
        </div>
    </header>
</div>