<x-app-layout>
    <x-slot name="title">Ads - Creator Panel</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="notes-page">
        <div class="grid-x grid-margin-x">
            <div class="cell medium-3">
                <h5>Creator Panel</h5>
                <a href="https://www.bloxcity.com/market/create" class="button button-green" style="width:100%;margin-bottom:10px;"><i class="fa-duotone fa-circle-plus"></i>&nbsp; Create</a>
                <div class="container notes-sidebar">
                    <a href="{{ route('user.creator-area.shirts') }}" class="notes-sidebar-item">Shirts</a>
                    <a href="{{ route('user.creator-area.pants') }}" class="notes-sidebar-item">Pants</a>
                    <a href="{{ route('user.creator-area.ads') }}" class="notes-sidebar-item active">Ads</a>
                </div>
                <div class="push-25 show-for-small-only"></div>
            </div>
            <div class="cell medium-9">
                <h5>Ads</h5>
                <div class="container">
                    We're working to get this feature back up as quickly as possible. Stay tuned!
                </div>
            </div>
        </div>
    </body>
</x-app-layout>