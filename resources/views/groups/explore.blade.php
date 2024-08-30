<x-app-layout>
    <x-slot name="title">Find Groups</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="groups-page users-page">
        <div class="container">
            <div class="grid-x grid-margin-x mb-25">
                <div class="auto cell">
                    <div class="search-header">Find Groups</div>
                </div>
                @auth
                    <div class="shrink cell">
                        <a href="{{ route('groups.index') }}" class="button button-blue"><i class="fa-duotone fa-people-group"></i>&nbsp; My Groups</a>
                        &nbsp;
                        <a href="{{ route('groups.create') }}" class="button button-green"><i class="fa-duotone fa-circle-plus"></i>&nbsp; Create Group</a>
                    </div>
                @endauth
            </div>
            <form action="{{ route('groups.search.post') }}" method="POST">
                @csrf
                <input class="form-input" type="text" name="search" placeholder="Search and press enter">
            </form>
        </div>
        <div class="push-15"></div>
        <div id="users">
        @forelse ($guilds as $guild)
            <div class="container user-container">
                <div class="grid-x grid-margin-x align-middle">
                    <div class="cell small-3 medium-2 text-center">
                        <a href="{{ route('groups.view', $guild) }}">
                            <div class="user-avatar" style="border:none!important;border-radius:0px!important;">
                                <img src="{{ $guild->thumbnail() }}">
                            </div>
                        </a>
                        <a href="{{ route('groups.view', $guild) }}" class="user-username">{{ $guild->name }}</a>
                    </div>
                    <div class="cell small-6 medium-4 medium-offset-1">
                        <div class="user-description">{{ $guild->desc ?? 'No description set.' }}</div>
                    </div>
                    <div class="cell small-3 medium-4 medium-offset-1 text-right">
                    @if($guild->is_private) <span style="color:red;">Private</span> @else <span style="color:green;">Public</span> @endif &bullet; {{ $guild->get_short_num($guild->members()->count()) }} @if($guild->members()->count() > 1) members @else member @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="container">
                <p>No users found.</p>
            </div>
        @endforelse
    </div>
    {{ $guilds->links('vendor.pagination.default') }}
    </body>
</x-app-layout>