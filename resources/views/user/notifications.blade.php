<x-app-layout>
    <x-slot name="title">Notifications</x-slot>
    <x-slot name="navigation"></x-slot>
    <!-- Begin Category -->
    <div class="row justify-content-center">
        <div class="col-md-9">
            <h4 class="pb-0 mb-0">Notifications</h4>
            <a href="#" onclick="event.preventDefault();document.getElementById('mark-all').submit();" style="cursor:pointer;"><small>Mark All as Read</small></a>
            <div class="card card-body p-3">

                @if(auth()->user()->notifications()->count() >= 1)
                    @foreach(auth()->user()->notifications()->paginate(5) as $notif)
                        @if(!($notif->type == 6 || $notif->type == 7))

                        <div class="d-flex py-1 @if(!$notif->read) notif-new" style="background-color:#242424" @else " @endif>
                            <img class="p-2" src="{{ $notif->from->get_headshot() }}" height="105" width="105">
                            <div class="ms-3">
                                <p class="mb-1 mt-2">{{ $notif->message }}</p>
                                <p class="small text-muted mb-1">{{ $notif->created_at->diffForHumans() }}</p>
                                <p class="small text-muted mb-1"><a href="{{ $notif->url }}">View <i class="fas fa-angle-double-right"></i></a></p>
                            </div>
                        </div>
                        <hr style="margin:2px;">
                                
                        @elseif(($notif->type == 6 || $notif->type == 7))

                        <div class="d-flex py-1 @if(!$notif->read) notif-new" style="background-color:#242424" @else " @endif>
                            <img class="p-2" src="{{ $notif->from->get_headshot() }}" height="105" width="105">
                            <div class="ms-3">
                                <p class="mb-1 mt-2">{{ $notif->message }}</p>
                                <p class="small text-muted mb-1">{{ $notif->created_at->diffForHumans() }}</p>
                                <p class="small text-muted mb-1"><a href="{{ $notif->url }}">View <i class="fas fa-angle-double-right"></i></a></p>
                            </div>
                        </div>
                        <hr style="margin:2px;">

                        @endif
                    @endforeach
                @endif

            </div>
        </div>
    </div>
    
    <!-- End category -->

    <div class="mb-md-5">&nbsp;</div>
</x-app-layout>
