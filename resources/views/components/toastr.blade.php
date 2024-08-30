<script>
    toastr.options =
        {
            "closeButton": true,
            "progressBar": true,
            "newestOnTop": true,
        }
    // success message popup notification
    @if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}", "Success");
    @endif

    // info message popup notification
    @if(Session::has('info'))
    toastr.info("{{ Session::get('info') }}", "Information");
    @endif

    // warning message popup notification
    @if(Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}", "Warning");
    @endif

    // error message popup notification
    @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}", "Error");
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}", "Error");
        @endforeach
    @endif
</script>
