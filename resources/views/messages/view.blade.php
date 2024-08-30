<x-app-layout>
    <x-slot name="title">{{ $message->subject }}</x-slot>
    <x-slot name="navigation"></x-slot>

    <body class="inbox-page">
        <div class="inbox-navigation">
            <div class="inbox-navigation-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>
            <div class="inbox-navigation-item">
                <a href="/messages/received/all">Inbox</a>
            </div>
            <div class="inbox-navigation-item">
                <a href="/messages/{{ $message->id }}">{{ $message->subject }}</a>
            </div>
        </div>
        <div class="container">
            <div class="grid-x grid-margin-x">
                <div class="cell small-4 medium-2 text-center">
                    <div class="inbox-show-message-sender-avatar">
                        <a href="{{ route('user.profile', $message->fromUser->id) }}">
                            <img class="inbox-show-message-sender-avatar-image" src="{{ ($message->fromUser->id == 1) ? asset('img/branding/icon_text.png') : $message->fromUser->get_headshot() }}">
                        </a>
                    </div>
                    <a href="{{ route('user.profile', $message->fromUser->id) }}" class="inbox-show-message-sender-username">{{ $message->fromUser->username }}</a>
                </div>
                <div class="cell small-8 medium-10">
                    <div class="grid-x grid-margin-x">
                        <div class="auto cell">
                            <div class="inbox-show-message-title">{{ $message->subject }}</div>
                        </div>
                        @if ($message->fromUser->id != Auth::user()->id)
                            <div class="shrink cell">
                                <a href="/messages/reply/{{ $message->id }}" class="button button-blue">Reply</a>
                            </div>
                        @endif
                    </div>
                    @if ($message->receiver_id == Auth::user()->id)
                        <div class="inbox-show-message-received">Received {{ $message->created_at->diffForHumans() }}</div>
                    @else
                        <div class="inbox-show-message-received">Sent {{ $message->created_at->diffForHumans() }}</div>
                    @endif
                    <hr class="inbox-show-message-divider">
                    <div class="inbox-show-message-body" style="white-space: pre-line">{{ $message->body }}</div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>