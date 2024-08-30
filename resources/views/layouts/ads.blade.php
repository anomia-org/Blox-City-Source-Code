@if(($_SERVER['REQUEST_URI'] != "/") || ($_SERVER['REQUEST_URI'] != "/" && $_SERVER['REQUEST_URI'] != "/site/offline"))
    @if(auth()->user()->membership != 0 && auth()->user()->id != 2)
        <div class="grid-x">
            <div class="cell medium-4 medium-offset-4">
                <center><img src="/img/ads/leaderboard.png" /></center>
                <div class="push-5"></div>
                <a href="#" class="float-right" style="color:red;">Report Ad</a>
            </div>
        </div>
        <div class="push-50"></div>
    @endif
@endif