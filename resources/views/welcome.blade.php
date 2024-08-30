<x-app-layout>
    <x-slot name="title">Let Your Creativity Flow</x-slot>
   <x-slot name="navigation"></x-slot>

<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind:400,500,600,700">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300">


</head>
<body class="landing-page">
<div id="app">

<div class="grid-x">
<div class="cell medium-4">
<div class="landing-container">
<div class="landing-header">The place to create.</div>
<div class="landing-text">Create an account for free</div>
<form method="POST" action="{{ route('register') }}">
@csrf
<input class="form-input" type="email" name="email" placeholder="Enter your email" value required>
<input class="form-input" type="text" name="username" placeholder="Choose a username" value required>
<input class="form-input" type="password" name="password" placeholder="Create a password" required>
<input class="form-input" type="password" name="password_confirmation" placeholder="Type password again" required>
<input class="form-input" type="date" name="birthday" placeholder="Birthday" required>
<input class="form-checkbox" type="checkbox" name="tos_agree" id="tos_agree" required="required">
<label class="form-label" for="tos_agree">I agree to follow the <a href="/notes/terms">terms of service</a></label>
<div class="col-1-1" style="margin-top:5px;">
{!! HCaptcha::display(['data-theme' => 'dark']) !!}
</div>
<div class="push-15"></div>
<button class="button button-block button-blue" type="submit">Sign Up</button>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

</div>


</x-app-layout>
