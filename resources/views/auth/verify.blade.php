<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Verify Your Email</x-slot>
    <body class="auth-page">
        <div id="app">
            <div class="grid-x">
                <div class="cell medium-6 medium-offset-4">
                    <div class="container auth-container">
                        <h5 class="mb-25">Verify Your Email Address</h5>
                        <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                            <button class="button button-blue" type="submit">
                                Request Verification
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

</x-app-layout>
