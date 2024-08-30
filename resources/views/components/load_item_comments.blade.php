@foreach($comments as $comment)
    <div class="item-comment grid-x grid-margin-x">
        <div class="cell small-3 medium-2 text-center">
            <a href="{{ route('user.profile', $comment->owner->id) }}">
                <img src="{{ $comment->owner->get_avatar() }}">
            </a>
            <a href="{{ route('user.profile', $comment->owner->id) }}" class="comment-creator">{{ $comment->owner->username }}</a>
        </div>
        <div class="cell small-9 medium-10">
            <div class="comment-time-posted"><i class="icon icon-time-ago"></i> Posted {{ $comment->created_at->diffForHumans() }}</div>
            <a href="{{ route('report.comment', $comment->id) }}" class="comment-report">
            <i class="icon icon-report"></i>
            </a>
            <div class="comment-body" style="white-space: pre-line">{{ $comment->text }}</div>
            @if(auth()->user()->power > 0)
                <hr>
                <div class="forum-mod-tools">
                    @if($comment->scrubbed)
                        <div class="forum-mod-tool">
                            <a href="#" onclick="event.preventDefault();document.getElementById('comment-scrub-{{ $comment->id }}').submit()">Unscrub</a>
                            <form method="POST" id="comment-scrub-{{ $comment->id }}" action="{{ route('market.comment.scrub', $comment->id) }}" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @else
                        <div class="forum-mod-tool">
                            <a href="#" onclick="event.preventDefault();document.getElementById('comment-scrub-{{ $comment->id }}').submit()">Scrub</a>
                            <form method="POST" id="comment-scrub-{{ $comment->id }}" action="{{ route('market.comment.scrub', $comment->id) }}" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endforeach