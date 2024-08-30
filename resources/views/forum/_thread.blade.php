<div class="grid-x grid-margin-x align-middle forum-post-grid @if ($thread->deleted) is-deleted @endif">
    <div class="cell medium-8">
        <div class="forum-post-creator-avatar">
            <img class="forum-post-creator-avatar-image" src="{{ ($thread->owner->id == 1) ? asset('img/branding/icon_text.png') : $thread->owner->get_headshot() }}">
        </div>
        <div class="forum-post-details">
            <a href="{{ route('forum.thread', ['thread' => $thread]) }}" class="forum-post-name @if ($thread->pinned) forum-post-name-pinned @endif"> @if($thread->pinned) <i class="fa-sharp fa-solid fa-thumbtack" style="color:dodgerblue;"></i> @endif  @if ($thread->deleted) <i class="fa-sharp fa-solid fa-trash-can" style="color:red;"></i> @endif {{ $thread->title }}</a>
            <div class="forum-post-poster">posted by <a <?php if($thread->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($thread->owner->membership > 0) { echo "style='font-weight:bold;color: ".$thread->owner->membershipColor()."';"; } ?> href="{{ route('user.profile', $thread->owner->id) }}">{{ $thread->owner->username }}</a> {{ $thread->created_at->diffForHumans() }}</div>
        </div>
    </div>
    <div class="cell medium-1 text-center hide-for-small-only">
        <div class="forum-container-stat">{{ number_format($thread->replies()->count()) }}</div>
    </div>
    <div class="cell medium-1 text-center hide-for-small-only">
        <div class="forum-container-stat">{{ number_format($thread->views) }}</div>
    </div>
    <div class="cell medium-2 text-right hide-for-small-only">
        
        <a href="{{ route('forum.thread', ['thread' => $thread]) }}" class="forum-container-stat forum-container-stat-last-post">{{ $thread->title }}</a>
            <div class="forum-container-stat forum-container-stat-last-poster">
                @if (!$thread->latestReply()->exists())
                    by <a <?php if($thread->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($thread->owner->membership > 0) { echo "style='font-weight:bold;color: ".$thread->owner->membershipColor()."';"; } ?> href="{{ route('user.profile', $thread->owner->id) }}">{{ $thread->owner->username }}</a>, {{ $thread->created_at->diffForHumans() }}
                @else
                    by <a <?php if($thread->latestReply->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($thread->latestReply->owner->membership > 0) { echo "style='font-weight:bold;color: ".$thread->latestReply->owner->membershipColor()."';"; } ?> href="{{ route('user.profile', $thread->latestReply->owner->id) }}">{{ $thread->latestReply->owner->username }}</a>, {{ $thread->latestReply->created_at->diffForHumans() }}
                @endif
            </div>
    </div>
</div>