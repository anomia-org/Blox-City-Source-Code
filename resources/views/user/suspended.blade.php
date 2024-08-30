<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Account Suspended</x-slot>
    <div class="container">
        <h5 style="font-weight:500;">Your account has been suspended</h5>
        <p style="font-size:16px;">We have deemed that your account has violated our Terms of Service, and as such a punishment has been applied to your account. Further violations of our Terms of Service may result in the termination of your account.</p>
        <div class="info" style="font-size:16px;">
            <div class="info-name" style="font-weight:bold;">Reviewed:</div>
            <div class="info-result">{{ $ban->created_at->format('M d, Y h:i A') }}</div>
        </div>
        <div class="info" style="font-size:16px;padding-top:8px;">
            <div class="info-name" style="font-weight:bold;">Reason:</div>
            <div class="info-result">{{ $category }}</div>
        </div>
        @if (!empty($ban->content))
            <div class="info" style="font-size:16px;padding-top:8px;">
                <div class="info-name" style="font-weight:bold;">Content:</div>
                <div class="container">{{ $ban->content }}</div>
            </div>
        @endif
        @if (!empty($ban->note))
            <div class="info" style="font-size:16px;padding-top:8px;">
                <div class="info-name" style="font-weight:bold;">Moderator Note:</div>
                <div class="info-result">{{ $ban->note }}</div>
            </div>
        @endif
        <div class="push-15"></div>
        <div class="text-center">
            @if ($ban->length == 'closed')
                <p>Your account has been closed. Thank you for playing!</p>
            @else
                @if (strtotime($ban->expires_at) < time())
                    <form action="{{ route('suspended.reactivate') }}" method="POST">
                        {{ csrf_field() }}
                        <input class="form-checkbox" id="acceptTos" type="checkbox" name="accept_tos">
                        <label class="form-label" for="accept_tos">I have read and agree to follow the <a href="/notes/terms">terms of service</a>.</label>
                        <div class="push-15"></div>
                        <button class="button button-blue" id="reactivateButton" type="submit" disabled>Reactivate Account</button>
                    </form>
                    <div class="push-15"></div>
                @endif
            @endif
            <a href="{{ route('logout') }}" class="button button-red">Logout</a>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(function() {
                $('#acceptTos').on('change', function() {
                    if (this.checked) {
                        $('#reactivateButton').attr('disabled', false);
                    } else {
                        $('#reactivateButton').attr('disabled', true);
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>