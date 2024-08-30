@foreach($comments as $comment)
    <div class="section">
        <div class="d-flex gap-3 align-items-center">
            <img src="{{ $comment->owner->get_headshot() }}" class="img-fluid rounded-circle headshot" width="86" />
            <div class="w-100">
                <a href="{{ route('user.profile', $comment->owner->id) }}" class="text-xl fw-semibold @if($comment->owner->power > 0) text-danger @else text-light @endif">{{ $comment->owner->username }}</a>
                <div class="mb-2">{{ $comment->text }}</div>
                <div class="text-muted text-sm">Posted {{ $comment->created_at->diffForHumans() }}</div>
            </div>
            <div class="dropdown">
                <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical text-xl"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <span class="text-center dropdown-item-text notification-dropdown-title p-0">Actions</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider mb-1" />
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('report.wall_post', $comment->id) }}">Report</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endforeach