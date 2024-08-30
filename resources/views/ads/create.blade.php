<x-app-layout>
    <x-slot name="title">Advertise "{{ $item->name }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <h4>Advertise "{{ $item->name }}"</h4>
            <div class="card card-body">
                <img src="/img/ads/leaderboard.png" class="img-fluid rounded mb-1" />
                <div class="text-sm text-muted mb-2">
                    Your ad will look like this
                    <span class="text-xs mx-1">&bullet;</span>
                    <a href="/img/ads/leaderboard.png" class="text-muted fw-normal">Download Template</a>
                </div>
                <form method="post" action="/creator-area/advertise/{{ $item->id }}" enctype="multipart/form-data">
                    @csrf
                    <div class="input-parent has-icon">
                        <i class="text-success bi bi-cash-stack"></i>
                        <input type="text" name="bid" class="form-control" placeholder="Bid in Cash" />
                    </div>
                    <div class="my-2">
                        <input class="form-control" type="file" name="file" />
                    </div>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>
    </div>    
</x-app-layout>