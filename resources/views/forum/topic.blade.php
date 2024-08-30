<x-app-layout>
    <x-slot name="title">{{ $topic->name }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="forum-page">
        @auth
            <div class="show-for-small-only text-center">
                <a href="#" class="button button-blue">My Threads</a>
                <a href="#" class="button button-red">Search Forum</a>
                <a href="{{ route('forum.thread.create', ['topic' => $topic]) }}" class="button button-green">Create</a>
            </div>
        @endauth
        <div class="grid-x grid-margin-x">
            <div class="cell small-9 medium-6">
                <div class="forum-navigation">
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.index') }}">Forum</a>
                    </div>
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.index') }}">{{ $topic->category->name }}</a>
                    </div>
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.topic', ['topic' => $topic]) }}">{{ $topic->name }}</a>
                    </div>
                </div>
            </div>
            @auth
                <div class="cell medium-6 text-right hide-for-small-only">
                    <div class="forum-auth-navigation">
                        <div class="forum-auth-navigation-item">
                            <a href="#">My Threads</a>
                        </div>
                        <div class="forum-auth-navigation-item">
                            <a href="#">Search Forum</a>
                        </div>
                        <div class="forum-auth-navigation-item">
                            <a href="{{ route('forum.thread.create', ['topic' => $topic]) }}">Create Thread</a>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
        <div class="forum-header forum-post-header" style="background:{{ $topic->color }}!important">
            <div class="grid-x grid-margin-x">
                <div class="cell medium-8">
                    Post
                </div>
                <div class="cell medium-1 text-center hide-for-small-only">
                    Replies
                </div>
                <div class="cell medium-1 text-center hide-for-small-only">
                    Views
                </div>
                <div class="cell medium-2 text-right hide-for-small-only">
                    Last Post
                </div>
            </div>
        </div>
        <div class="forum-container forum-topic-container">
            @forelse ($threads as $thread)
                @include('forum._thread', ['thread' => $thread])
            @empty
                <div class="cell">There are no threads in this topic.</div>
            @endforelse
            {{ $threads->onEachSide(1)->links('vendor.pagination.default') }}
        </div>
    </body>
</x-app-layout>