<form wire:submit.prevent="login">
    @csrf
    <div class="form-group @error('username') is-invalid @enderror">
        <label for="username" class="required">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}" required="required" wire:model="username">
    </div>
    <div class="form-group @error('password') is-invalid @enderror">
        <label for="password" class="required">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" required="required" wire:model="password">
    </div>
    <div class="form-group">
        <div class="custom-checkbox">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Remember Me</label>
        </div>
    </div>
    <input class="btn btn-success btn-block" type="submit" value="Login">
    @if (Route::has('password.request'))
        <div class="text-right mt-10">
            <a href="{{ route('password.request') }}" class="hyperlink">Forgot password?</a>
        </div>
    @endif

    @foreach($errors->all() as $error)
        <script>
            toastDangerAlert('Error', '{{ $error }}');
        </script>
    @endforeach
</form>
