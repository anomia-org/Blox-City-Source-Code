<x-app-layout>
    <x-slot name="title">{{ $thread->title }}</x-slot>
    <x-slot name="navigation"></x-slot>
	<body class="forum-page">
    <div id="app">
        <div class="text-center show-for-small-only">
            <a href="https://www.bloxcity.com/forum/my-threads" class="button button-blue">My Threads</a>
            <a href="https://www.bloxcity.com/forum/search" class="button button-red">Search Forum</a>
        </div>
        <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-6">
                <div class="forum-navigation">
                    <div class="forum-navigation-item">
                        <a href="/forum">Forum</a>
                    </div>
                <div class="forum-navigation-item">
                    <a href="/forum">{{ $category->name }}</a>
                </div>
                <div class="forum-navigation-item">
                    <a href="{{ route('forum.topic', $topic) }}">{{ $topic->name }}</a>
                </div>
            </div>
        </div>
        <div class="text-right cell small-12 medium-6 hide-for-small-only">
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
        {{ $thread->title }}
        </div>

        @if($replies->currentPage() == 1)
            <div class="container">
                <div class="forum-container forum-post-container ">
                    <div class="grid-x grid-margin-x">
                        <div class="text-center cell small-4 medium-3">
                            <div class="forum-thread-creator-username">
                                <div class="forum-thread-status @if($thread->owner->isOnline()) status-online @else status-offline @endif" title="{{ $thread->owner->username }} is @if($thread->owner->isOnline()) online @else offline @endif" data-tooltip></div>
                                <a href="{{ route('user.profile', $thread->owner) }}">{{ $thread->owner->username }}</a>
                            </div>
                            <a href="{{ route('user.profile', $thread->owner) }}">
                                <img class="forum-thread-creator-avatar" style="margin-top:10px;margin-bottom:10px;" src="{{ $thread->owner->get_avatar() }}">
                            </a>
                            @if ($thread->owner->power > 0)
								<img src="{{ asset('/img/forum/admin.png') }}" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="Administrator" alt="Administrator">
							@endif

						    @if ($thread->owner->membership > 0)
								@if ($thread->owner->membership == 1)
									<img src="/img/forum/bronze.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Bronze" alt="BLOX City Bronze">
								@endif
								@if ($thread->owner->membership == 2)
									<img src="/img/forum/silver.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Silver" alt="BLOX City Silver">
								@endif
								@if ($thread->owner->membership == 3)
									<img src="/img/forum/gold.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Gold" alt="BLOX City Gold">
								@endif
							@endif

							@if ($thread->owner->posts() >= 500)
								<img src="/img/forum/pforumer.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="Pro Forumer" alt="Pro Forumer">
							@endif
                            <br>
                            <div class="forum-thread-stats">
                                <div class="grid-x grid-margin-x">
                                    <div class="cell small-6 medium-3 medium-offset-2">
                                        <strong>Posts</strong>
                                    </div>
                                    <div class="cell small-6 medium-3">
                                        {{ $thread->owner->get_short_num($thread->owner->posts()) }}
                                    </div>
                                </div>
                                <div class="grid-x grid-margin-x">
                                    <div class="cell small-6 medium-3 medium-offset-2">
                                        <strong>Joined</strong>
                                    </div>
                                    <div class="cell small-6 medium-3">
                                        {{ $thread->owner->created_at->format('m/d/Y') }}
                                    </div>
                                </div>
                                <div class="grid-x grid-margin-x">
                                    <div class="cell small-6 medium-3 medium-offset-2">
                                        <strong>Networth</strong>
                                    </div>
                                    <div class="cell small-6 medium-3 text-cash">
                                        ${{ $thread->owner->get_short_num($thread->owner->getUserValue()) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cell small-8 medium-9">
                            <div class="forum-thread-time-posted"><i class="icon icon-time-ago"></i> Posted {{ $thread->created_at->diffForHumans() }}</div>
                            <div class="forum-thread-report">
                                <a href="{{ route('report.threads', $thread->id) }}">
                                    <i class="icon icon-report"></i>
                                </a>
                            </div>
                            <div class="forum-thread-body" style="white-space: pre-wrap">{{ $thread->body }}</div>
                            <div class="forum-signature">{{ $thread->owner->signature }}</div>
                            
                            @if(auth()->user()->power > 0)
                                <div class="forum-mod-tools">
                                    @if(!$thread->stuck)
                                        @if(!$thread->pinned)
                                            <div class="forum-mod-tool">
                                                <a href="#" onclick="event.preventDefault();document.getElementById('thread-pin').submit()">Pin</a>
                                                <form method="POST" id="thread-pin" action="{{ route('forum.thread.pin', $thread->id) }}">
                                                    @csrf
                                                </form>
                                            </div>
                                        @else
                                            <div class="forum-mod-tool">
                                                <a href="#" onclick="event.preventDefault();document.getElementById('thread-pin').submit()">Unpin</a>
                                                <form method="POST" id="thread-pin" action="{{ route('forum.thread.pin', $thread->id) }}">
                                                    @csrf
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                    @if($thread->locked)
                                        <div class="forum-mod-tool">
                                            <a href="#" onclick="event.preventDefault();document.getElementById('thread-unlock').submit()">Unlock</a>
                                            <form method="POST" id="thread-unlock" action="{{ route('forum.thread.lock', $thread->id) }}" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    @else
                                        <div class="forum-mod-tool">
                                            <a href="#" onclick="event.preventDefault();document.getElementById('thread-unlock').submit()">Lock</a>
                                            <form method="POST" id="thread-unlock" action="{{ route('forum.thread.lock', $thread->id) }}" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    @endif
                                    @if($thread->scrubbed)
                                        <div class="forum-mod-tool">
                                            <a href="#" onclick="event.preventDefault();document.getElementById('thread-scrub').submit()">Unscrub</a>
                                            <form method="POST" id="thread-scrub" action="{{ route('forum.thread.scrub', $thread->id) }}" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    @else
                                        <div class="forum-mod-tool">
                                            <a href="#" onclick="event.preventDefault();document.getElementById('thread-scrub').submit()">Scrub</a>
                                            <form method="POST" id="thread-scrub" action="{{ route('forum.thread.scrub', $thread->id) }}" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    @endif
                                    @if($thread->deleted)
                                        <div class="forum-mod-tool">
                                            <a href="#" onclick="event.preventDefault();document.getElementById('thread-delete').submit()">Undelete</a>
                                            <form method="POST" id="thread-delete" action="{{ route('forum.thread.delete', $thread->id) }}" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    @else
                                        <div class="forum-mod-tool">
                                            <a href="#" onclick="event.preventDefault();document.getElementById('thread-delete').submit()">Delete</a>
                                            <form method="POST" id="thread-delete" action="{{ route('forum.thread.delete', $thread->id) }}" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    @endif
                                    <div class="forum-mod-tool">
                                        <a href="{{ route('forum.thread.move', $thread->id) }}">Move</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @include('components.load_replies')
    @auth
        @if (!$thread->locked || ($thread->locked && Auth::user()->power > 1))
            <div class="push-15"></div>
            <div class="text-center">
                <a href="{{ route('forum.thread.reply', ['thread' => $thread->id]) }}" class="forum-button">Reply</a>
            </div>
        @endif
    @endauth
</div>
    

    <x-slot name="script">
        <script>
            var query = window.location.search;
            var pageUrl = '';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.like-thread-btn', function(){
                var id = $(this).data('id');
                var c = $('#th-'+id+'-count').html();
                var cObj = $('#like-thread');

                $.ajax({
                    type:'POST',
                    url:'/forum/thread/'+id+'/like',
                    data:{id:id},
                    success:function(data){
                        if(jQuery.isEmptyObject(data.success)){
                            $('#th-'+id+'-count').html(parseInt(c)-1);
                            $(cObj).removeClass("bi-heart-fill");
                            $(cObj).addClass("bi-heart");
                        }else{
                            $('#th-'+id+'-count').html(parseInt(c)+1);
                            $(cObj).removeClass("bi-heart")
                            $(cObj).addClass("bi-heart-fill");
                        }
                    }
                });
            });

            $(document).on('click', '.like-reply-btn', function(){
                var id = $(this).data('id');
                var c = $('#r-'+id+'-count').html();
                var cObj = $('#like-reply-'+id);

                $.ajax({
                    type:'POST',
                    url:'/forum/reply/'+id+'/like',
                    data:{id:id},
                    success:function(data){
                        if(jQuery.isEmptyObject(data.success)){
                            $('#r-'+id+'-count').html(parseInt(c)-1);
                            $(cObj).removeClass("bi-heart-fill");
                            $(cObj).addClass("bi-heart");
                        }else{
                            $('#r-'+id+'-count').html(parseInt(c)+1);
                            $(cObj).removeClass("bi-heart")
                            $(cObj).addClass("bi-heart-fill");
                        }
                    }
                });
            });

        </script>
    </x-slot>
</x-app-layout>
