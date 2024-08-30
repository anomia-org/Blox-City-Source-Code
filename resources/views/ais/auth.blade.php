<x-app-layout class="authentication d-flex justify-content-center align-items-center flex-column">
    <x-slot name="title">A.IS Authentication</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="auth-page">

        <div class="grid-x">
            <div class="cell medium-6 medium-offset-3">
                <div class="container auth-container">
                    <h5 class="mb-25">Hi {{ auth()->user()->username }}! Let's verify it's you.</h5>
                    <form method="POST" action="{{ route('ais.auth.post') }}">
                        @csrf
                        <div class="input-parent has-icon">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name="password" class="form-input" placeholder="Password" required="required" />
                        </div>
                        <div class="mb-10"></div>
                        <div>
                            Please contact your department lead if you need help signing in.
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </body>

</x-app-layout>