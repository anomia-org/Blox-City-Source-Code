<x-app-layout>
    <x-slot name="title">Create an account</x-slot>
	<x-slot name="navigation"></x-slot>
	<body class="auth-page">
<div id="app">
<div class="grid-x">
<div class="cell medium-6 medium-offset-4">
<div class="container auth-container">
<h5 class="mb-25">Register</h5>
<form method="POST" action="{{ route('register') }}" id="registerForm">
@csrf
<input class="form-input" type="email" name="email" placeholder="Email Address" value required>
<input class="form-input" type="text" name="username" placeholder="Username" value required>
<input class="form-input" type="password" name="password" placeholder="Password" required>
<input class="form-input" type="password" name="password_confirmation" placeholder="Password (again)" required>
<input class="form-input" type="date" name="birthday" placeholder="Birthday" required>
<input class="form-checkbox" type="checkbox" name="tos_agree" id="tos_agree">
<label class="form-label" for="tos_agree">I agree to follow the <a href="https://www.bloxcity.com/notes/terms">terms of service</a></label>
<div class="col-1-1" style="margin-top:5px;">
{!! HCaptcha::display(['data-theme' => 'dark']) !!}
</div>
<div class="push-15"></div>
<button class="button button-blue" type="submit">Register</button>
</form>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</x-app-layout>
