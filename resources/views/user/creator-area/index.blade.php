<x-app-layout>
    <x-slot name="title">Creator Panel</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="notes-page">
        <div class="grid-x grid-margin-x">
            <div class="cell medium-3">
                <h5>Creator Panel</h5>
                <div class="container notes-sidebar">
                    <a href="{{ route('user.creator-area.shirts') }}" class="notes-sidebar-item @if (Route::is('user.creator-area.shirts') || Route::is('user.creator-area.shirts.*')) active @endif">Shirts</a>
                    <a href="{{ route('user.creator-area.pants') }}" class="notes-sidebar-item @if (Route::is('user.creator-area.pants') || Route::is('user.creator-area.pants.*')) active @endif">Pants</a>
                    <a href="{{ route('user.creator-area.ads') }}" class="notes-sidebar-item @if (Route::is('user.creator-area.ads') || Route::is('user.creator-area.ads.*')) active @endif">Ads</a>
                </div>
                <div class="push-25 show-for-small-only"></div>
            </div>
            <div class="cell medium-9">
                <h5>Shirts</h5>
                <div class="container">
                    Okay
                </div>
            </div>
        </div>
    </body>
</x-app-layout>