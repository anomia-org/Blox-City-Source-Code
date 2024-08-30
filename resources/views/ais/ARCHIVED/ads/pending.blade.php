<x-admin-layout>
    <x-slot name="title">Pending</x-slot>
    <div class="row">
        @foreach($ads as $ad)
        <div class="col-12">
            <h3>Ad #{{ $ad->id }}</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-lg-3">
                                    <h4>Asset</h4>
                                    <img class="img-fluid" src="{{ $ad->image_path }}">
                                </div>
                                <div class="col-12 col-lg-9">
                                    <h4>Upload Info</h4>
                                    <b>Uploaded by:</b> <a href="{{ route('user.profile', $ad->owner->id) }}">{{ $ad->owner->username }}</a><br>
                                    <b>Uploaded at:</b> {{ $ad->created_at }}<br>
                                    <br>
                                    <a class="btn btn-lg btn-success" onclick="event.preventDefault();document.getElementById('accept-{{ $ad->id }}').submit();" style="cursor:pointer;">Accept</a>
                                    <a class="btn btn-lg btn-danger" onclick="event.preventDefault();document.getElementById('decline-{{ $ad->id }}').submit();" style="cursor:pointer;">Deny</a>
                                    <form method="POST" id="accept-{{ $ad->id }}" action="{{ route('ais.ads.accept', $ad->id) }}" class="d-none">
                                        @csrf
                                    </form>
                                    <form method="POST" id="decline-{{ $ad->id }}" action="{{ route('ais.ads.decline', $ad->id) }}" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">{{ $ads->links('vendor.pagination.default') }}</div>
</x-admin-layout>