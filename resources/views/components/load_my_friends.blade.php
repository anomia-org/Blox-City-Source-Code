@foreach($friends as $friend)
    <div class="col-md-6 col-lg-4">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <img src="{{ $friend->get_headshot() }}" class="headshot rounded-circle me-3" width="80" />
                <div class="w-100 min-w-0">
                    <a href="{{ route('user.profile', $friend->id) }}" class="d-block truncate text-xl fw-semibold text-light">{{ $friend->username }}</a>
                    <a class="btn btn-danger btn-sm w-100 mt-1" onclick="event.preventDefault();document.getElementById('delete-{{ $friend->id }}').submit();">Unfriend</a>
                    <form method="POST" class="d-none" id="delete-{{ $friend->id }}" action="{{ route('user.friends.remove', $friend->id) }}">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach