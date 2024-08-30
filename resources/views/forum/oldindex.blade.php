<x-app-layout>
    <x-slot name="title">Forums</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="card p-3 px-4 mb-2 mt-md-2 mobile-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="dropdown shrink">
                <button class="text-xl bg-transparent border-0 p-0 text-white" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(!isset($_GET['topic'])) All Topics @else {{ \App\Models\Topic::where('id', '=', $_GET['topic'])->get()->first()->name }} @endif
                    <i class="bi bi-chevron-down text-sm ms-2 align-middle"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <span class="text-center dropdown-item-text notification-dropdown-title p-0">Topics</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider mb-1" />
                    </li>
                    <li>
                        <a class="dropdown-item @if(!isset($_GET['topic'])) active @endif" href="{{ route('forum.index') }}">All Topics</a>
                    </li>
                    @foreach($categories as $category)
                        <span class="dropdown-item-text text-small text-uppercase text-muted text-bold" href="#">{{ $category->name }}</span>
                        @foreach($topics as $topic)
                            @if($topic->category_id == $category->id)
                                <li>
                                    <a class="dropdown-item @if(isset($_GET['topic']) && $_GET['topic'] == $topic->id) active @endif" href="?topic={{ $topic->id }}"><i class="bi bi-circle-fill" style="color:{{ $topic->color }};margin-right:1px;"></i> {{ $topic->name }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endforeach
                </ul>
            </div>
            <div class="w-100 mx-2 mx-md-4 input-parent has-icon">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Search...">
            </div>
            @auth
                <div class="shrink">
                    <a href="#" class="text-xl text-white" data-bs-toggle="tooltip" title="My Threads">
                        <i class="bi bi-chat-left-text"></i>
                    </a>
                </div>
            @endauth
        </div>
    </div>
    <div class="row align-items-center mt-4 mt-md-2 mb-4">
        <div class="col-md-8">
            <ul class="nav nav-pills nav-rounded mt-3 mt-md-0 mb-1 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link @if(!isset($_GET['sort']) || $_GET['sort'] == "recent") active @endif" aria-current="page" href="@if(isset($_GET['topic'])) ?topic={{ $_GET['topic'] }}&sort=recent @else ?sort=recent @endif">Most Recent</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(isset($_GET['sort']) && $_GET['sort'] == "trending") active @endif" href="@if(isset($_GET['topic'])) ?topic={{ $_GET['topic'] }}&sort=trending @else ?sort=trending @endif">What's Hot</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(isset($_GET['sort']) && $_GET['sort'] == "official") active @endif" href="?sort=official">Official Posts</a>
                </li>
            </ul>
        </div>
        <div class="col-md-4 text-end">
            @auth
                <a href="{{ route('forum.thread.create') }}" class="btn btn-primary btn-rounded d-none d-md-inline-block" style="margin-top: 2px">Create Thread</a>
                <a href="{{ route('forum.thread.create') }}" class="btn btn-primary btn-floating d-flex d-md-none">
                    <i class="bi bi-plus"></i>
                </a>
            @endauth
        </div>
    </div>
    <h2>Threads</h2>
    <span id="post-data">
        @include('components.load_threads')
    </span>
    <div class="mb-md-3">&nbsp;</div>

    <x-slot name="script">
        <script>
            var query = window.location.search;
            var pageUrl = '';
            if(query) {
                pageUrl = query+'&page=';
            } else {
                pageUrl = '?page=';
            }
            function loadMoreData(page) {
                $.ajax({
                    url:pageUrl+''+page,
                    type:'get',
                })
                    .done(function(data) {
                        if(data.html == " ") {
                            return;
                        }
                        $("#post-data").append(data.html);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        console.log("Server not responding...");
                    });
            }

            var page = 1;
            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    page++;
                    loadMoreData(page);
                }
            });
        </script>
    </x-slot>
</x-app-layout>
