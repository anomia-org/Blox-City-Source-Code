<x-admin-layout>
    <x-slot name="title">Pending</x-slot>
    <div class="row">
        @foreach($items as $item)
        <div class="col-12">
            <h3>Upload #{{ $item->id }}</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-lg-3">
                                    <h4>Asset</h4>
                                    <img class="img-fluid" src="https://cdn.bloxcity.com/{{ $item->hash }}.png">
                                </div>
                                <div class="col-12 col-lg-9">
                                    <h4>Upload Info</h4>
                                    <b>Type:</b> {{ $item->get_type() }}<br>
                                    <b>Uploaded by:</b> <a href="{{ route('user.profile', $item->owner->id) }}">{{ $item->owner->username }}</a><br>
                                    <b>Uploaded at:</b> {{ $item->created_at }}<br>
                                    <br>
                                    <h5 class="mb-0">Title</h5>
                                    <p>{{ $item->name }}</p>
                                    <h5 class="mb-0">Description</h5>
                                    <p>{{ $item->desc }}</p>
                                    <br>
                                    <a class="btn btn-lg btn-success" onclick="event.preventDefault();document.getElementById('accept-{{ $item->id }}').submit();" style="cursor:pointer;">Accept</a>
                                    <a class="btn btn-lg btn-danger" onclick="event.preventDefault();document.getElementById('decline-{{ $item->id }}').submit();" style="cursor:pointer;">Deny</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">{{ $items->links('vendor.pagination.default') }}</div>
</x-admin-layout>