<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="navigation"></x-slot>
        <body class="dashboard-page">
        <div class="grid-x grid-margin-x">
            <div class="cell medium-3">
                <div class="container dashboard-avatar-container mb-25">
                    <img class="dashboard-avatar" src="{{ Auth::user()->get_avatar() }}">
                        </div>
                        <div class="dashboard-header">Updates</div>
                        <div class="container dashboard-blog-container">
                            @foreach($posts as $post)
                                <div class="dashboard-blog-post">
                                    <a href="{{ $post['url'] }}" class="blog-post-title" target="_blank">{{ $post['title'] }}</a>
                                    <div class="blog-post-body">{{ $post['excerpt'] }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="push-25 show-for-small-only"></div>
                    </div>
                    <div class="cell medium-9">
                        <div class="container mb-25">
                            <form action="{{ route('user.blurb.update') }}" method="POST">
                            @csrf
                                <input class="form-input" type="text" name="text" placeholder="What's up?" value="{{ old('text') }}">
                            </form>
                        </div>
                        <div class="dashboard-header">Feed</div>
                        <div class="container dashboard-feed-container">
                            @if($blurbs->isEmpty())
                                <div class="feed-no-notifications">You have no notifications.</div>
                                <div class="feed-why-not">Why not try <a href="#">searching for users</a> or <a href="{{ route('forum.index') }}">chatting with users</a> in our forum?</div>
                            @else
                                @include('components.load_user_feed')
                            @endif
                        </div>
                        <div class="push-10"></div>
                    </div>
                </div>
            </div>
        </body>
</x-app-layout>