<div class="card p-14">
    <div class="card-title font-size-16 font-weight-medium pb-10">My Feed</div>
    <form wire:submit.prevent="add">
        <div class="input-group">
            <input type="text" name="blurb" class="form-control @error('blurb') is-invalid @enderror" placeholder="What's poppin', {{ Auth::user()->username }}?" wire:model="blurb">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">Post</button>
            </div>
        </div>
        @error('blurb')
        <script>
            toastDangerAlert('Error', '{{ $message }}');
        </script>
        @enderror
    </form>
    @if($blurbs->isEmpty())
        <p class="text-center">Your feed is empty :(</p>
    @else
        <hr>
    @endif
    @foreach($blurbs as $blurb)
        <!-- Status box start -->
            <div class="status-box">
                <a href="{{ route('user.profile', $blurb->owner->id) }}">
                    <img src="/static/img/headshot.png" class="rounded-circle avatar-bg w-64 online-website">
                </a>
                <div class="status-text pl-80 pb-8">
                    <a href="{{ route('report.blurb', $blurb->id) }}" class="absolute right bottom report"><i class="far fa-flag"></i></a>
                    <span class="font-size-13"><a href="{{ route('user.profile', $blurb->owner->id) }}" class="font-weight-semi-bold">{{ $blurb->owner->username }}</a> updated their status:</span>
                    <div class="font-size-14 mb-5">"{{ $blurb->text }}"</div>
                    <span class="text-muted font-size-10"><i class="fas fa-clock"></i> {{ $blurb->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <hr>
            <!-- Status box end -->
    @endforeach
    {{ $blurbs->links('vendor.pagination.default') }}
</div>
