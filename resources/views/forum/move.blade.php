<x-app-layout>
    <x-slot name="title">Moving "{{ $thread->title }}"</x-slot>
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
                        <a href="{{ route('forum.topic', $thread->topic) }}">{{ $thread->topic->name }}</a>
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
            Move "{{ $thread->title }}"
        </div>
        <div class="container forum-container">
            <form action="{{ route('forum.thread.move.post', $thread->id) }}" method="POST">
                @csrf
                <strong>Move thread to...</strong>
                <select class="form-input" name="topic">
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}" @if ($thread->topic_id == $topic->id) selected @endif>{{ $topic->name }}</option>
                    @endforeach
                </select>
                <button class="forum-button" type="submit">Move</button>
            </form>
        </div>
    </body>
</x-app-layout>
