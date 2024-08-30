<x-app-layout>
    <x-slot name="title">Reply to a message</x-slot>
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
                <a href="{{ route('messages.reply.view', $message->id) }}">Reply</a>
            </div>
        </div>
        <div class="container">
            <div class="inbox-quote">
                <div class="forum-quote-body" style="white-space: pre-line">{{ $message->body }}</div>
                <div class="forum-quote-footer"><a href="{{ route('user.profile', $message->fromUser->id) }}">{{ $message->fromUser->username }}</a>, {{ $message->created_at->format('m-d Y h:i A') }}</div>
            </div>
            <form method="post" action="/messages/reply">
                @csrf
                <input type="hidden" name="message_id" value="{{ $message->id }}">
                <textarea class="form-input" name="body" placeholder="Begin writing your message here..." rows="5"></textarea>
                <button class="inbox-button" type="submit">Reply</button>
            </form>
        </div>
    </body>
</x-app-layout>