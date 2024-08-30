<x-app-layout>
    <x-slot name="title">Messages</x-slot>
    <x-slot name="navigation"></x-slot>

    <body class="inbox-page">
        <div class="tabs inbox-tabs">
            <div class="tab">
                <a href="/messages/received/all" class="tab-link {{ ($type == 'received') ? 'active' : '' }}">Incoming</a>
            </div>
            <div class="tab">
                <a href="/messages/sent/all" class="tab-link {{ ($type == 'sent') ? 'active' : '' }}">Sent</a>
            </div>
        </div>
        <div class="inbox-messages">
            @forelse ($messages as $message)
                <div class="inbox-container @if ($message->seen) is-seen @endif">
                    <div class="inbox-message-sender-avatar">
                        @if ($type == 'received')
                            <img class="inbox-message-sender-avatar-image" src="{{ ($message->fromUser->id == 1) ? asset('/img/branding/icon_text.png') : $message->fromUser->get_headshot() }}">
                        @else
                            <img class="inbox-message-sender-avatar-image" src="{{ ($message->toUser->id == 1) ? asset('/img/branding/icon_text.png') : $message->toUser->get_headshot() }}">
                        @endif
                    </div>
                    <div class="inbox-message-details">
                        <a href="{{ route('messages.view', $message->id) }}" class="inbox-message-title">{{ $message->subject }}</a>
                        @if ($type == 'received')
                            <div class="inbox-message-sender">from <a href="{{ route('user.profile', $message->fromUser->id) }}">{{ $message->fromUser->username }}</a> {{ $message->created_at->diffForHumans() }}</div>
                        @else
                            <div class="inbox-message-sender">to <a href="{{ route('user.profile', $message->toUser->id) }}">{{ $message->toUser->username }}</a> {{ $message->created_at->diffForHumans() }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="container inbox-container" style="padding-bottom:15px;">
                    <span style="font-size:18px;font-weight:light;">You have no incoming mail.</span>
                </div>
            @endforelse
        </div>
        {{ $messages->onEachSide(1)->links('vendor.pagination.default') }}
    </body>
</x-app-layout>