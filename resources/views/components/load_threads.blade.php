@foreach($threads as $thread)
    <!-- Begin Thread -->
    <div class="card p-2 ps-3 p-md-3 border-start mb-3" style="border-color:{{ $thread->topic->color }}!important;">
        <div class="row align-items-center">
            <div class="col-3 col-md-3">
                <div class="d-flex align-items-center">
                    <a href="{{ route('user.profile', $thread->owner->id) }}">
                        <img src="{{ $thread->owner->get_headshot() }}" class="headshot rounded-circle d-none d-md-block" width="80" />
                        <img src="{{ $thread->owner->get_headshot() }}" class="headshot rounded-circle d-block d-md-none" width="60" />
                    </a>
                    <div class="ms-3 min-w-0 d-none d-md-block">
                        <a href="{{ route('user.profile', $thread->owner->id) }}" class="d-block truncate @if($thread->owner->power > 0) text-danger @else text-light @endif text-xl fw-semibold">
                            {{ $thread->owner->username }}
                        </a>
                        <div class="text-sm text-muted truncate">{{ $thread->owner->get_short_num($thread->owner->posts()) }} posts</div>
                        <div class="text-sm text-muted mt-1">{{ $thread->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-8 col-md-9 p-0">
                <div class="text-muted text-xs mb-1 d-block d-md-none">
                    Posted by
                    <a href="{{ route('user.profile', $thread->owner->id) }}" class="@if($thread->owner->power > 0) text-danger @else text-light @endif fw-semibold">{{ $thread->owner->username }}</a>
                    <span class="mx-1 text-muted">&bullet;</span>{{ $thread->created_at->diffForHumans() }}
                </div>
                <a href="{{ route('forum.thread', $thread->id) }}" class="text-muted">
                    <div class="truncate text-xl fw-semibold text-light d-none d-md-block">
                        @if($thread->stuck || $thread->pinned)
                            <span class="badge bg-danger text-xs fw-semibold position-relative" style="bottom:3px;">Pinned</span>
                        @elseif($thread->locked)
                            <span class="badge bg-secondary text-xs fw-semibold position-relative" style="bottom:3px;">Locked</span>
                        @endif
                        {{ $thread->title }}
                    </div>
                    <div class="truncate text-lg fw-semibold text-light d-block d-md-none">
                        @if($thread->stuck || $thread->pinned)
                            <i class="bi bi-pin-fill align-middle text-danger"></i>
                        @elseif($thread->locked)
                            <i class="bi bi-lock-fill align-middle text-muted"></i>
                        @endif
                        {{ $thread->title }}
                    </div>
                    <div class="mt-1 truncate d-block d-md-none">
                            <span class="text-primary fw-normal">
                                <i class="bi bi-chat-left-text me-1"></i><span class="text-sm">{{ $thread->owner->get_short_num($thread->replies()->count()) }}</span>
                            </span>
                        <span class="mx-1 text-muted text-xs">&bullet;</span>
                        <span class="text-danger fw-normal">
                                <i class="bi @guest bi-heart @endguest @auth @if(auth()->user()->forumHasLiked($thread->id, '1')) bi-heart-fill @else bi-heart @endif @endauth me-1"></i><span class="text-sm">{{ $thread->owner->get_short_num($thread->likes()->count()) }}</span>
                            </span>
                        <span class="mx-1 text-muted text-xs">&bullet;</span>
                        <span class="text-muted text-sm fw-normal">
                                @if($thread->latestReply()->exists())
                                {{ $thread->latestReply->created_at->diffForHumans(null, null, true) }}
                            @else
                                {{ $thread->created_at->diffForHumans(null, null, true) }}
                            @endif
                            </span>
                    </div>
                    <div class="truncate text-muted fw-normal d-none d-md-block">
                        {{ $thread->body }}
                    </div>
                    <div class="mt-1 truncate d-none d-md-block">
                            <span class="text-primary fw-normal">
                              <i class="bi bi-chat-left-text me-1"></i><span class="text-sm">{{ $thread->owner->get_short_num($thread->replies()->count()) }}</span>
                            </span>
                        <span class="mx-2 text-muted text-xs">&bullet;</span>
                        <span class="text-danger fw-normal">
                                <i class="bi @guest bi-heart @endguest @auth @if(auth()->user()->forumHasLiked($thread->id, '1')) bi-heart-fill @else bi-heart @endif @endauth me-1"></i><span class="text-sm">{{ $thread->owner->get_short_num($thread->likes()->count()) }}</span>
                            </span>
                        <span class="mx-2 text-muted text-xs">&bullet;</span>
                        <span class="text-info fw-normal">
                                <i class="bi bi-eye me-1"></i><span class="text-sm">{{ $thread->owner->get_short_num($thread->views) }}</span>
                            </span>
                        <span class="mx-2 text-muted text-xs d-none d-md-inline">&bullet;</span>
                        <span class="text-muted text-sm fw-normal d-block d-md-inline mt-1 mt-md-0">
                                @if($thread->latestReply()->exists())
                                Last active {{ $thread->latestReply->created_at->diffForHumans() }}
                            @else
                                Last active {{ $thread->created_at->diffForHumans() }}
                            @endif
                            </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- End thread -->
@endforeach
