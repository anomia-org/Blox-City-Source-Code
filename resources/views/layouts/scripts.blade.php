<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/js/foundation.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).foundation(); 

        let data = $('meta[name="user-data"]');

        if (data.attr('data-authenticated') == 'false') {
            window.userData = {
                authenticated: false,
                csrf: $('meta[name="csrf-token"]').attr('content')
            };
        } else {
            window.userData = {
                authenticated: true,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                id: parseInt(data.attr('data-id')),
                username: data.attr('data-username'),
                vip: parseInt(data.attr('data-vip')),
                coins: parseInt(data.attr('data-coins')),
                cash: parseInt(data.attr('data-cash')),
                angle: data.attr('data-angle')
            };
        }

        var sidebar = false;

        $('#sidebarToggler').click(function() {
            sidebar = !sidebar;

            if (sidebar) {
                $('.sidebar').removeClass('hide');
            }
            else {
                $('.sidebar').addClass('hide');
            }
        });
    });
</script>
<script src="{{ asset('js/dropdown-menu.js?v=3') }}"></script>
@auth
    <script defer>
        Echo.private('App.Models.User.'+{{ auth()->user()->id }})
        .listen('UserNotification', (e) => {
            if(e.type) {
                if(e.url) {
                    toastr.info(""+e.message, "Notification", {iconClass: ""+e.type, onclick: function () { window.location.href = ''+e.url }})
                } else {
                    toastr.info(""+e.message, "Notification", {iconClass: ""+e.type})
                }
            } else {
                toastr.info(""+e.message, "Notification");
            }
        });
    </script>
@endauth
@yield('additional_js')
