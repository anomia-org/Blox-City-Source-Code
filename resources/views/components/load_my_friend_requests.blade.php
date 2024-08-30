@foreach($requests as $request)
    <div class="col-md-6 col-lg-4">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <img src="{{ $request->sender->get_headshot() }}" class="headshot rounded-circle me-3" width="80" />
                <div class="w-100 min-w-0">
                    <a href="{{ route('user.profile', $request->sender->id) }}" class="d-block truncate text-xl fw-semibold text-light">{{ $request->sender->username }}</a>
                    <div class="d-flex gap-2 mt-1">
                        <form method="POST" id="accept-{{ $request->sender->id }}" action="{{ route('user.friends.accept', $request->sender->id) }}" class="d-none">
                            @csrf
                        </form>
                        <form method="POST" id="decline-{{ $request->sender->id }}" action="{{ route('user.friends.decline', $request->sender->id) }}" class="d-none">
                            @csrf
                        </form>
                        <a href="#" class="btn btn-success btn-sm w-100" onclick="event.preventDefault();document.getElementById('accept-{{ $request->sender->id }}').submit();">Accept</a>
                        <a href="#" class="btn btn-danger btn-sm w-100" onclick="event.preventDefault();document.getElementById('decline-{{ $request->sender->id }}').submit();">Decline</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach