<x-app-layout>
    <x-slot name="title">Forums</x-slot>
    <x-slot name="navigation"></x-slot>
	<body class="forum-page">
    <div id="forum_holder">
        @foreach($categories as $category)
		&nbsp;
            <div class="forum-header" style="background:{{ $category->color }}!important">
                <div class="grid-x grid-margin-x">
                    <div class="cell medium-8">
                        {{ $category->name }}
                    </div>
                    <div class="cell medium-1 text-center hide-for-small-only">
                        Threads
                    </div>
                    <div class="cell medium-1 text-center hide-for-small-only">
                        Replies
                    </div>
                    <div class="cell medium-2 text-right hide-for-small-only">
                        Last Post
                    </div>
                </div>
            </div>
                @forelse ($topics as $topic)
                    @if($topic->category_id == $category->id)
                        <div class="forum-container">
                            <div class="grid-x grid-margin-x align-middle">
                                <div class="cell medium-8">
                                    <a href="{{ route('forum.topic', $topic->id) }}">
                                        <div class="forum-container-topic-name">{{ $topic->name }}</div> @php $hello = $topic->disableCache()->where('id', $topic->id)->first(); @endphp
                                        <div class="forum-container-topic-description">{{ $hello->description }}</div>
                                    </a>
                                </div>
                                <div class="cell medium-1 text-center hide-for-small-only">
                                    <div class="forum-container-stat">{{ number_format($topic->threads()->count()) }}</div>
                                </div>
                                <div class="cell medium-1 text-center hide-for-small-only">
                                    <div class="forum-container-stat">{{ number_format($topic->replies()->count()) }}</div>
                                </div>
                                <div class="cell medium-2 text-right hide-for-small-only">
                                    @if($topic->latestThread()->exists() || $topic->latestReply()->exists())
                                        @if($topic->latestReply()->exists())
                                            @if($topic->latestThread->created_at->gt($topic->latestReply->created_at))
                                                <a href="{{ route('forum.thread', $topic->latestThread->id) }}" class="forum-container-stat forum-container-stat-last-post"><?php if(strlen(nl2br(e($topic->latestThread->title))) > 20) { echo substr(nl2br(e($topic->latestThread->title)), 0, 20) . '...'; } else { echo nl2br(e($topic->latestThread->title)); } ?></a>
                                                <div class="forum-container-stat forum-container-stat-last-poster">
                                                    by <a <?php if($topic->latestThread->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($topic->latestThread->owner->membership > 0) { echo "style='font-weight:bold;color: ".$topic->latestThread->owner->membershipColor()."';"; } ?> href="{{ route('user.profile', $topic->latestThread->owner->id) }}">{{ $topic->latestThread->owner->username }}</a>, {{ $topic->latestThread->created_at->diffForHumans() }}
                                                </div>
                                            @else
                                                <a href="{{ route('forum.thread', $topic->latestReply->thread->id) }}" class="forum-container-stat forum-container-stat-last-post"><?php if(strlen(nl2br(e($topic->latestReply->thread->title))) > 20) { echo substr(nl2br(e($topic->latestReply->thread->title)), 0, 20) . '...'; } else { echo nl2br(e($topic->latestReply->thread->title)); } ?></a>
                                                <div class="forum-container-stat forum-container-stat-last-poster">
                                                    by <a <?php if($topic->latestReply->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($topic->latestReply->owner->membership > 0) { echo "style='font-weight:bold;color: ".$topic->latestReply->owner->membershipColor()."';"; } ?> href="{{ route('user.profile', $topic->latestReply->owner->id) }}">{{ $topic->latestReply->owner->username }}</a>, {{ $topic->latestReply->created_at->diffForHumans() }}
                                                </div>
                                            @endif
                                        @else
                                            <a href="{{ route('forum.thread', $topic->latestThread->id) }}" class="forum-container-stat forum-container-stat-last-post"><?php if(strlen(nl2br(e($topic->latestThread->title))) > 20) { echo substr(nl2br(e($topic->latestThread->title)), 0, 20) . '...'; } else { echo nl2br(e($topic->latestThread->title)); } ?></a>
                                            <div class="forum-container-stat forum-container-stat-last-poster">
                                                by <a <?php if($topic->latestThread->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($topic->latestThread->owner->membership > 0) { echo "style='font-weight:bold;color: ".$topic->latestThread->owner->membershipColor()." ';"; } ?> href="{{ route('user.profile', $topic->latestThread->owner->id) }}">{{ $topic->latestThread->owner->username }}</a>, {{ $topic->latestThread->created_at->diffForHumans() }}
                                            </div>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="forum-container">
                        There are currently no forum topics.
                    </div>
                @endforelse

        @endforeach
&nbsp;      
        
</x-app-layout>