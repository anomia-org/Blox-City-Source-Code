<x-app-layout>
    <x-slot name="title">Compose a new message</x-slot>
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
                <a href="{{ route('messages.compose.view', $user) }}">Compose</a>
            </div>
        </div>
        <div class="container">
            <h5>Composing a new message for "{{ $user->username }}"</h5>
            <form method="post" action="/messages/create">
                @csrf
                <input type="hidden" name="to" value="{{ $user->id }}">
                <input class="form-input" type="text" name="subject" placeholder="Subject">
                <textarea class="form-input" name="body" placeholder="What do you want to say to {{ $user->username }}?" rows="5"></textarea>
                <button class="inbox-button" type="submit">Send</button>
            </form>
        </div>
    </body>
</x-app-layout>