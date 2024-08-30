@extends('layouts.app')

@section('title', 'Verify Identity')

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-5">
            <div class="card p-0">
                <div class="card-header border-bottom text-white">Confirm Password</div>
                <div class="p-20">
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        <div class="form-group @error('password') is-invalid @enderror">
                            <label for="password" class="required">Password</label>
                            @error('password')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                            <input type="password" name="password" class="form-control" placeholder="Password" required="required">
                        </div>
                        <input class="btn btn-success btn-block" type="submit" value="Confirm Password">
                    </form>

                    <script type="text/javascript">

                        @if(count($errors))
                            @foreach($errors->all() as $error)
                                toastDangerAlert("Error", "<?php echo $error; ?>");
                            @endforeach
                        @endif

                        function toastDangerAlert(title, content) {
                            halfmoon.initStickyAlert({
                                content: content,
                                title: title,
                                alertType: "alert-danger",
                                fillType: "filled"
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection