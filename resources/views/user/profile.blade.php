<x-app-layout>
    <x-slot name="title">{{ $user->username }}</x-slot>
    <x-slot name="pageDescription">{{ $user->username }} is one of thousands of players on BLOXCity.com, the fastest growing user-generated sandbox platform. Join us today!</x-slot>
    <x-slot name="pageImage">{{ $user->get_avatar() }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <meta name="user-info" data-id="{{ $user->id }}">
    <body class="profile-page">
        <div class="grid-x grid-margin-x">
            <div class="cell medium-3">
                <div class="profile-header">
                    {{ $user->username }}
                    @if ($user->usernameHistory()->count() > 0)
                    @php
                        $i = 1;
                        $len = $user->usernameHistory()->count();
                    @endphp
                    <i
                        class="icon icon-username-history profile-username-history"
                        title="Previous usernames: @foreach ($user->usernameHistory() as $username) {{ $username->username }}@php if ($i < $len) { echo ', '; } $i++; @endphp @endforeach"
                        data-position="right"
                        data-tooltip
                    ></i>
                    @endif
                </div>
                <div class="container profile-avatar-container mb-15">
                    <img class="profile-avatar" style="margin-top:10px;margin-bottom:10px;" src="{{ $user->get_avatar() }}">
                    @auth
                        @if ($user->id != Auth::user()->id)
                            <div class="text-center">
                                @if (auth()->user()->isFriendWith($user))
                                    <form action="{{ route('user.friends.remove', $user) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button class="button button-red" type="submit" title="Remove Friend" data-tooltip><i class="icon icon-unfriend"></i></button>
                                    </form>
                                @elseif ($user->hasFriendRequestFrom(auth()->user()))
                                    <button class="button button-gray" type="submit" disabled><i class="icon icon-pending"></i></button>
                                @elseif (auth()->user()->hasFriendRequestFrom($user))
                                    <form action="{{ route('user.friends.accept', $user) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button class="button button-green" type="submit" title="Accept Friend" data-tooltip><i class="icon icon-pending"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('user.friends.add', $user) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button class="button button-green" type="submit" title="Add Friend" data-tooltip><i class="icon icon-friend"></i></button>
                                    </form>
                                @endif
                                @if ($user->privacy->message)
                                    <a href="{{ route('messages.compose.view', $user) }}" class="button button-blue" title="Send Message" data-tooltip><i class="icon icon-message"></i></a>
                                @endif
                            </div>
                        @endif
                    @endauth
                    @if ($user->isOnline())
                        <div class="profile-status status-online" title="Last seen {!! ($user->isOnline()) ? 'just now' : $user->last_online->diffForHumans() !!}" data-position="bottom" data-tooltip></div>
                    @else
                        <div class="profile-status status-offline" title="Last seen {!! ($user->isOnline()) ? 'just now' : $user->last_online->diffForHumans() !!}" data-position="bottom" data-tooltip></div>
                    @endif
                </div>
                @auth
                    @if (Auth::user()->power > 1)
                        <div class="text-center mb-15">
                            <a href="https://east.bloxcity.com/users/view/{{ $user->id }}" class="button button-blue" target="_blank">View in Panel</a>
                        </div>
                    @endif
                @endauth
                @if (!empty($user->biography))
                    <div class="container profile-container profile-description-container mb-15" style="white-space: pre-line">{{ $user->biography }}</div>
                @endif
                <div class="container profile-container profile-stats-container">
                    <div class="profile-stat">
                        <div class="profile-stat-name"><i class="fa-duotone fa-calendar-range"></i>&nbsp; Date Joined</div>
                        <div class="profile-stat-result">{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-name"><i class="fa-duotone fa-users"></i>&nbsp; Friends</div>
                        <div class="profile-stat-result">{{ number_format($user->getFriendsCount()) }}</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-name"><i class="fa-duotone fa-message-lines"></i>&nbsp; Posts</div>
                        <div class="profile-stat-result">{{ number_format($user->posts()) }}</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-name"><i class="fa-duotone fa-user-magnifying-glass"></i>&nbsp; Views</div>
                        <div class="profile-stat-result">{{ number_format($user->views) }}</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-name"><i class="currency currency-cash currency-sm currency-align"></i>&nbsp; Networth</div>
                        <div class="profile-stat-result">{{ number_format($user->getUserValue()) }}</div>
                    </div>
                </div>
                @auth
                    @if ($user->id != 1)
                        <div class="push-25"></div>
                        <a href="{{ route('report.user', $user) }}" style="color:inherit;"><i class="icon icon-report item-report"></i> Report</a>
                    @endif
                @endauth
                <div class="push-15 show-for-small-only"></div>
            </div>
            <div class="cell medium-9">
                @if ($user->blurb()->exists())
                    <div class="push-25 hide-for-small-only"></div>
                    <div class="container profile-container mb-15">
                        <strong>Personal Status:</strong> {{ strip_tags($user->blurb->text) }}
                    </div>
                @endif
                <div class="profile-header">Games</div>
                <div class="container profile-container profile-inventory-container mb-15">
                <div class="profile-no-results">This user does not have any active games.</div> 
                </div>
                <div class="profile-header">Achievements</div>
                <div class="container profile-container profile-achievements-container mb-15">
                    <div class="grid-x grid-margin-x">
                        @forelse ($badges as $badge)
                            <div class="cell small-6 medium-2 profile-achievement">
                                <a href="/achievements">
                                    <img class="profile-achievement-image" src="{{ $badge['image'] }}">
                                </a>
                                <a href="/achievements" class="profile-achievement-title">{{ $badge['name'] }}</a>
                                <div class="push-15 show-for-small-only"></div>
                            </div>
                        @empty
                            <div class="auto cell">
                                <div class="profile-no-results">This user does not have any achievements.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="grid-x align-middle">
                    <div class="cell auto">
                        <div class="profile-header">Friends</div>
                    </div>
                    <div class="cell shrink">
                        <a href="{{ route('user.friends', $user->id) }}" class="button button-blue profile-view-all">View All</a>
                    </div>
                </div>
                <div class="container profile-container profile-friends-container mb-15">
                    <div class="grid-x grid-margin-x">
                        @if($user->getFriendsCount() > 0)
                            @foreach($user->getFriends()->take(6) as $friend)
                                <div class="cell small-6 medium-2 profile-friend">
                                    <a href="{{ route('user.profile', ['user' => $friend]) }}">
                                        <img class="profile-friend-avatar" src="{{ $friend->get_avatar() }}">
                                    </a>
                                    <a href="{{ route('user.profile', ['user' => $friend]) }}" class="profile-friend-username">
                                        @if ($friend->isOnline())
                                            <div class="profile-friend-status status-online" title="Last seen {!! ($user->isOnline()) ? 'just now' : $user->last_online->diffForHumans() !!}" data-tooltip></div>
                                        @else
                                            <div class="profile-friend-status status-offline" title="Last seen {!! ($user->isOnline()) ? 'just now' : $user->last_online->diffForHumans() !!}" data-tooltip></div>
                                        @endif
                                        {{ $friend->username }}
                                    </a>
                                    <div class="push-15 show-for-small-only"></div>
                                </div>
                            @endforeach
                        @else
                            <div class="auto cell">
                                <div class="profile-no-results">This user has no friends.</div>
                            </div>
                        @endif
                    </div>
                </div>

                    <div class="grid-x align-middle">
                        <div class="cell auto">
                            <div class="profile-header">Groups</div>
                        </div>
                        <div class="cell shrink">
                            <a href="#" class="button button-blue profile-view-all">View All</a>
                        </div>
                    </div>
                    <div class="container profile-container profile-groups-container">
                        @if($user->guildsCount() < 1)
                            <div class="profile-no-results">This user is not in any groups.</div>
                        @else
                        <div class="grid-x grid-margin-x">
                            @foreach($user->guilds()->take(6) as $guild)
                                <div class="cell small-6 medium-2 profile-group">
                                    <a href="{{ route('groups.view', $guild) }}">
                                        <img class="profile-group-icon" src="{{ $guild->thumbnail() }}">
                                    </a>
                                    <div class="push-5"></div>
                                    <a href="{{ route('groups.view', $guild) }}" class="profile-group-name">
                                        {{ $guild->name }}
                                    </a>
                                    <div class="push-15 show-for-small-only"></div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="push-15"></div>

                <div class="profile-header">Inventory</div>
                <div class="container profile-container profile-inventory-container">
                    <div class="grid-x grid-margin-x">
                        <div class="cell small-12 medium-2">
                            <div class="profile-inventory-category" data-category="collectibles">Collectibles</div>
                            <div class="profile-inventory-category" data-category="hats">Hats</div>
                            <div class="profile-inventory-category" data-category="faces">Faces</div>
                            <div class="profile-inventory-category" data-category="accessories">Accessories</div>
                            <div class="profile-inventory-category" data-category="heads">Heads</div>
                            <div class="profile-inventory-category" data-category="t-shirts">T-Shirts</div>
                            <div class="profile-inventory-category" data-category="shirts">Shirts</div>
                            <div class="profile-inventory-category" data-category="pants">Pants</div>
                            <div class="push-15 show-for-small-only"></div>
                        </div>
                        <div class="cell small-12 medium-10">
                            <div id="inventory"></div>
                            <div id="inventoryButtons"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <x-slot name="script">
        <script src="{{ asset('js/site/inventory.js?v=5') }}"></script>
    </x-slot>
</x-app-layout>