<x-app-layout>
    <x-slot name="title">Search Users</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="users-page">
    <div class="container">
        <div class="grid-x grid-margin-x mb-25">
            <div class="auto cell">
                <div class="search-header">Search Users</div>
            </div>
            <div class="shrink cell">
                <p>{{ number_format($totalUsers) }} Total Users / <a href="/users/online" style="color:green;">Online</a></p>
            </div>
        </div>
        <form action="{{ route('users.search') }}" method="GET">
            <input class="form-input" type="text" name="search" placeholder="Search and press enter">
        </form>
    </div>
    <div class="push-15"></div>
    <div id="users">
        @forelse ($users as $user)
            <div class="container user-container">
                <div class="grid-x grid-margin-x align-middle">
                    <div class="cell small-3 medium-2 text-center">
                        <a href="{{ route('user.profile', ['user' => $user]) }}">
                            <div class="user-avatar">
                                <img class="user-avatar-image" src="{{ $user->get_headshot() }}">
                            </div>
                        </a>
                        <a href="{{ route('user.profile', ['user' => $user]) }}" class="user-username">{{ $user->username }}</a>
                    </div>
                    <div class="cell small-6 medium-8">
                        <div class="user-description" style="white-space: pre-line">{{ $user->biography ?? 'This user has no description.' }}</div>
                    </div>
                    <div class="cell small-3 medium-2 text-right">
                        <td width="15%" class="right-align">
                            <div style="font-size:12px;color:{!! ($user->isOnline()) ? 'green' : 'grey' !!}">Last seen {!! ($user->isOnline()) ? 'just now' : $user->last_online->diffForHumans() !!}</div>
                        </td>
                    </div>
                </div>
            </div>
        @empty
            <div class="container">
                <p>No users found.</p>
            </div>
        @endforelse
    </div>
    {{ $users->onEachSide(1)->links('vendor.pagination.default') }}
</body>
</x-app-layout>