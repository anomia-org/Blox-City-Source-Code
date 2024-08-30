<x-app-layout>
<x-slot name="navigation"></x-slot>
    <x-slot name="title">Log in</x-slot>
    <body class="auth-page">
        <div id="app">
            <div class="grid-x">
                <div class="cell medium-6 medium-offset-4">
                    <div class="container auth-container">
                        <h5 class="mb-25">Log in</h5>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input class="form-input" type="text" name="username" placeholder="Username" value required>
                            <input class="form-input" type="password" name="password" placeholder="Password" required>
                            <div class="col-1-1" style="margin-top:5px;">
                                {!! HCaptcha::display(['data-theme' => 'dark']) !!}
                            </div>
                            <div class="grid-x align-middle">
                                <div class="cell auto">
                                    <div class="push-15"></div>
                                    <button class="button button-blue" type="submit">Log in</button>
                                    @if (Route::has('password.request'))
                                        <a class="float-right" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>
