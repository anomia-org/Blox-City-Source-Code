<x-app-layout>
    <x-slot name="title">Friends</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="user-friends-page">
        <h5>Friends ({{ $user->get_short_num($user->getFriends()->count()) }})</h5>
        <div class="container">
            <div class="grid-x grid-margin-x">
                @forelse($friends as $friend)
                    <div class="cell small-6 medium-2 user-friend mb-25">
                        <a href="{{ route('user.profile', $friend->id) }}">
                            <img class="user-friend-avatar" src="{{ $friend->get_avatar() }}">
                        </a>
                        <a href="{{ route('user.profile', $friend->id) }}" class="user-friend-username">
                            {{ $friend->username }}
                        </a>
                    </div>
                @empty
                    <div class="auto cell">You currently have no friends.</div>
                @endforelse
            </div>
            {{ $friends->links('vendor.pagination.default') }}
        </div>
    </body>
</x-app-layout>