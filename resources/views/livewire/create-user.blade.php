<form wire:submit.prevent="create">
    @csrf

    <div class="form-group @error('email') is-invalid @enderror">
        <label for="email" class="required">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email" required="required" wire:model="email">
    </div>
    <div class="form-group @error('username') is-invalid @enderror">
        <label for="username" class="required">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Username" required="required" wire:model="username">
    </div>
    <div class="form-group @error('password') is-invalid @enderror">
        <label for="password" class="required">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" required="required" wire:model="password">
    </div>
    <div class="form-group">
        <label for="confirm-password" class="required">Confirm Password</label>
        <input type="password" id="confirm-password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required="required" wire:model="password_confirmation">
    </div>
    <div class="form-group @error('birthday') is-invalid @enderror">
        <label for="birthday" class="required">Birthday</label>
        <input type="date" name="birthday" class="form-control" required="required" wire:model="birthday">
    </div>
    <div class="form-group">
        <div class="custom-checkbox">
            <input type="checkbox" id="agree-to-terms" name="tos_agree" required autocomplete="tos_agree" wire:model="tos_agree">
            <label for="agree-to-terms">I agree to the <a href="#" class="hyperlink">Terms of Service</a></label>
        </div>
    </div>
    <input class="btn btn-success btn-block" type="submit" value="Register">
    @foreach($errors->all() as $error)
    <script>
        toastDangerAlert('Error', '{{ $error }}');
    </script>
    @endforeach
</form>
