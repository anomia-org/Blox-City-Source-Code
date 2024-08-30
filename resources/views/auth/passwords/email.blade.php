<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Forgot Password</x-slot>
    <body class="auth-page">
    <a href="{{ route('index') }}" class="btn btn-secondary btn-sm position-absolute top-0 start-0 mt-2 ms-2"><i class="bi bi-arrow-return-left me-2"></i>Return</a>
        <div id="app">
            <div class="grid-x">
                <div class="cell medium-6 medium-offset-4">
                    <div class="container auth-container">
                        <h5 class="mb-25">Reset Password</h5>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <input type="email" name="email" class="form-input" placeholder="Email" value="{{ old('email') }}" required="required" autocomplete="email"/>
                            <div class="mb-3"></div>
                            <button class="button button-blue" type="submit">
                                Send Password Reset Link
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

</x-app-layout>