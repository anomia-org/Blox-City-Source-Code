<x-app-layout>
    <x-slot name="title">Replying to "{{ $thread->title }}"</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="forum-page">
        <div class="show-for-small-only text-center">
            <a href="#" class="button button-blue">My Threads</a>
            <a href="#" class="button button-red">Search Forum</a>
        </div>
        <div class="grid-x grid-margin-x">
            <div class="cell small-9 medium-6">
                <div class="forum-navigation">
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.index') }}">Forum</a>
                    </div>
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.index') }}">{{ config('app.name') }}</a>
                    </div>
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.topic', ['topic' => $thread->topic]) }}">{{ $thread->title }}</a>
                    </div>
                </div>
            </div>
            <div class="cell medium-6 text-right hide-for-small-only">
                <div class="forum-auth-navigation">
                    <div class="forum-auth-navigation-item">
                        <a href="#">My Threads</a>
                    </div>
                    <div class="forum-auth-navigation-item">
                        <a href="#">Search Forum</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="forum-header forum-thread-header">
            Reply to "{{ $thread->title }}"
        </div>
        <div class="container forum-container">
            <form action="{{ route('forum.thread.reply.post', $thread->id) }}" method="POST">
                @csrf
                <textarea class="form-input" name="body" id="body" placeholder="Body (max 3,000 characters)" maxlength="3000" rows="6"></textarea>
                <div class="push-15"></div>
                <button class="forum-button" type="submit">Reply</button>
            </form>
        </div>
    </body>

</x-app-layout>
