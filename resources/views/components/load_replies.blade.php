@foreach($replies as $reply)
    <!-- Begin reply -->
        <div class="container">
        <div class="forum-container forum-post-container " id="reply_1">
<div class="grid-x grid-margin-x">
<div class="cell small-4 medium-3 text-center">
<div class="forum-thread-creator-username">
<div class="forum-thread-status @if($reply->owner->isOnline()) status-online @else status-offline @endif" title="{{ $reply->owner->username }} is @if($reply->owner->isOnline()) online @else offline @endif"  data-tooltip></div>
<a href="{{ route('user.profile', $reply->owner->id) }}">{{ $reply->owner->username }}</a>
</div>
<a href="{{ route('user.profile', $reply->owner->id) }}">
<img class="forum-thread-creator-avatar" style="margin-top:10px;margin-bottom:10px;" src="{{ $reply->owner->get_avatar() }}">
</a>

@if ($reply->owner->power > 0)
	<img src="{{ asset('/img/forum/admin.png') }}" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="Administrator" alt="Administrator">
@endif

						    @if ($reply->owner->membership > 0)
								@if ($reply->owner->membership == 1)
									<img src="/img/forum/bronze.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Bronze" alt="BLOX City Bronze">
								@endif
								@if ($reply->owner->membership == 2)
									<img src="/img/forum/silver.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Silver" alt="BLOX City Silver">
								@endif
								@if ($reply->owner->membership == 3)
									<img src="/img/forum/gold.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Gold" alt="BLOX City Gold">
								@endif
							@endif

							@if ($reply->owner->posts() >= 500)
								<img src="/img/forum/pforumer.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="Pro Forumer" alt="Pro Forumer">
							@endif
                            <br>

<div class="forum-thread-stats">
<div class="grid-x grid-margin-x">
<div class="cell small-6 medium-3 medium-offset-2">
<strong>Posts</strong>
</div>
<div class="cell small-6 medium-3">
{{ $reply->owner->get_short_num($reply->owner->posts()) }}
</div>
</div>
<div class="grid-x grid-margin-x">
<div class="cell small-6 medium-3 medium-offset-2">
<strong>Joined</strong>
</div>
<div class="cell small-6 medium-3">
{{ $reply->owner->created_at->format('m/d/Y') }}
</div>
</div>
<div class="grid-x grid-margin-x">
                                    <div class="cell small-6 medium-3 medium-offset-2">
                                        <strong>Networth</strong>
                                    </div>
                                    <div class="cell small-6 medium-3 text-cash">
                                        ${{ $reply->owner->get_short_num($reply->owner->getUserValue()) }}
                                    </div>
                                </div>
</div>
</div>
<div class="cell small-8 medium-9">
<div class="forum-thread-time-posted"><i class="icon icon-time-ago"></i> Posted {{ $reply->created_at->diffForHumans() }}</div>

<div class="forum-thread-report">
    <!-- 1
    <a href="#">
        <i class="icon icon-favorite"></i>
    </a>-->
    <a href="{{ route('report.reply', $reply->id) }}">
        <i class="icon icon-report"></i>
    </a>
</div>

@if(!$reply->thread->locked) 
    <a href="{{ route('forum.thread.quote', ['thread' => $reply->thread_id, 'quote_id' => $reply->id, 'quote_type' => 2]) }}" class="forum-thread-quote">
        <i class="icon icon-quote"></i>
    </a>
@endif
<?php
          if($reply->quote_id != NULL)
          {
              $quote = NULL;
              if($reply->quote_type == 1)
              {
                  $quote = \App\Models\Thread::where('id', $reply->quote_id)->get()->first();
              } elseif($reply->quote_type == 2) {
                  $quote = \App\Models\Reply::where('id', $reply->quote_id)->get()->first();
              }

              if($quote->exists)
              {
        ?>

            <!-- Begin quote -->
            <div class="forum-quote">
<div class="forum-quote-body">{{ $quote->body }}</div>
<div class="forum-quote-footer"><a>{{ $quote->owner->username }}</a>, {{ $quote->created_at->diffForHumans() }}</div>
</div>
            <!-- End quote -->
        <?php
              }
          }
        ?>
<div class="forum-thread-body" style="white-space: pre-wrap">{{ $reply->body }}</div>
<div class="forum-signature">{{ $reply->owner->signature }}</div>
                        
                            @if(auth()->user()->power > 0)
                            <div class="forum-mod-tools">
                                @if($reply->scrubbed)
                                    <div class="forum-mod-tool">
                                        <a href="#" onclick="event.preventDefault();document.getElementById('reply-scrub-{{ $reply->id }}').submit()">Unscrub</a>
                                        <form method="POST" id="reply-scrub-{{ $reply->id }}" action="{{ route('forum.reply.scrub', $reply->id) }}" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                @else
                                    <div class="forum-mod-tool">
                                        <a href="#" onclick="event.preventDefault();document.getElementById('reply-scrub-{{ $reply->id }}').submit()">Scrub</a>
                                        <form method="POST" id="reply-scrub-{{ $reply->id }}" action="{{ route('forum.reply.scrub', $reply->id) }}" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                @endif
                                </div>
                            @endif
                        

</div>
</div>
</div>
        </div>
@endforeach
<div class="container" style="border:none!important;">
    {{ $replies->onEachSide(1)->links('vendor.pagination.default') }}
</div>