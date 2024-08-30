<x-app-layout>
    <x-slot name="title">Quoting "{{ $quote->owner->username }}" on "{{ $thread->title }}"</x-slot>
    <x-slot name="navigation"></x-slot>
	<body class="forum-page">
<div id="app">
<div class="page-wrapper">
<div class="grid-container  forum-grid">
<div class="grid-x">
<div class="cell medium-10 medium-offset-1">
<div class="show-for-small-only text-center">
</div>
<div class="grid-x grid-margin-x">
<div class="cell small-9 medium-6">
<div class="forum-navigation">
<div class="forum-navigation-item">
<a href="/forum">Forum</a>
</div>
<div class="forum-navigation-item">
<a href="{{ route('forum.topic', $topic->id) }}">{{ $topic->name }}</a>
</div>
<div class="forum-navigation-item">
<a href="{{ route('forum.thread', $thread) }}">{{ $thread->title }}</a>
</div>
</div>
</div>
<div class="cell medium-6 text-right hide-for-small-only">
<div class="forum-auth-navigation">
<div class="forum-auth-navigation-item">
</div>
<div class="forum-auth-navigation-item">
</div>
</div>
</div>
</div>
<div class="forum-header forum-thread-header">
Quote {{ $quote->owner->username }} in "{{ $thread->title }}"
</div>
<div class="container forum-container">
<div>
<div></div>
<div></div>
</div>
<form method="POST" action="{{ route('forum.thread.quote.post', ['thread' => $thread->id, 'quote_id' => $quote->id, 'quote_type' => $quote_type]) }}">
@csrf
<textarea class="form-input" id="body" name="body" placeholder="Write your post here." rows="5"></textarea>
<div class="push-15"></div>
<button class="forum-button" type="submit">Post</button>
</form>
</div>
</div>
</div>
</div>
</div>
</x-app-layout>
