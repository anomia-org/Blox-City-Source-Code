@foreach($blurbs as $blurb)
    <div class="dashboard-status">
        <div class="grid-x grid-margin-x">
            <div class="cell small-3 medium-2 text-center">
                <div class="dashboard-status-creator-avatar">
                    <a href="{{ route('user.profile', $blurb->owner->id) }}">
                        <img class="dashboard-status-creator-avatar-image" src="{{ $blurb->owner->get_headshot() }}">
                    </a>
                </div>
                <a href="{{ route('user.profile', $blurb->owner->id) }}" class="dashboard-status-creator">{{ $blurb->owner->username }}</a>
            </div>
            <div class="cell small-9 medium-10">
                <div class="dashboard-status-content">{{ $blurb->text }}</div>
                <div class="dashboard-status-time"><i class="icon icon-time-ago"></i> {{ $blurb->created_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
@endforeach
<div class="container" style="border:none!important;">
    {{ $blurbs->onEachSide(1)->links('vendor.pagination.default') }}
</div>

                           