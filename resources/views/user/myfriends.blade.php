<x-app-layout>
    <x-slot name="title">My Friends</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="user-friends-page">
        <h5>Requests ({{ auth()->user()->get_short_num(auth()->user()->getFriendRequests()->count()) }})</h5>
        <div class="container">
            <div class="grid-x grid-margin-x">
                @forelse($requests as $request)
                    <div class="cell small-6 medium-2 user-friend mb-25">
                        <a href="{{ route('user.profile', $request->sender->id) }}">
                            <img class="user-friend-avatar" src="{{ $request->sender->get_avatar() }}">
                        </a>
                        <a href="{{ route('user.profile', $request->sender->id) }}" class="user-friend-username">
                            {{ $request->sender->username }}
                        </a>
                        <div class="push-10"></div>
                        <form method="POST" id="accept-{{ $request->sender->id }}" action="{{ route('user.friends.accept', $request->sender->id) }}" style="display:inline-block;">
                            @csrf
                            <button class="button button-green" type="submit">Accept</button>
                        </form>
                        <form method="POST" id="decline-{{ $request->sender->id }}" action="{{ route('user.friends.decline', $request->sender->id) }}" style="display:inline-block;">
                            @csrf
                            <button class="button button-red" type="submit">Decline</button>
                        </form>
                    </div>
                @empty
                    <div class="auto cell">You currently have no incoming friend requests.</div>
                @endforelse
            </div>
            {{ $requests->links('vendor.pagination.default') }}
        </div>
    </body>
</x-app-layout>