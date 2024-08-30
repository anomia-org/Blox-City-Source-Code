<x-admin-layout>
    <x-slot name="title">Pending</x-slot>
    <div class="row">
        @foreach($guilds as $guild)
        <div class="col-12">
            <h3>Guild #{{ $guild->id }}</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-lg-3">
                                    <h4>Asset</h4>
                                    <img class="img-fluid" src="https://cdn.bloxcity.com/{{ $guild->thumbnail_url }}">
                                </div>
                                <div class="col-12 col-lg-9">
                                    <h4>Upload Info</h4>
                                    <b>Uploaded by:</b> <a href="{{ route('user.profile', $guild->owner->id) }}">{{ $guild->owner->username }}</a><br>
                                    <b>Guild created at:</b> {{ $guild->created_at }}<br>
                                    <b>Guild name:</b> {{ $guild->name }}<br>
                                    <br>
                                    <a class="btn btn-lg btn-success" onclick="event.preventDefault();document.getElementById('accept-{{ $guild->id }}').submit();" style="cursor:pointer;">Accept</a>
                                    <a class="btn btn-lg btn-danger" onclick="event.preventDefault();document.getElementById('decline-{{ $guild->id }}').submit();" style="cursor:pointer;">Deny</a>
                                    <form method="POST" id="accept-{{ $guild->id }}" action="{{ route('ais.guilds.accept', $guild->id) }}" class="d-none">
                                        @csrf
                                    </form>
                                    <form method="POST" id="decline-{{ $guild->id }}" action="{{ route('ais.guilds.decline', $guild->id) }}" class="d-none">
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
    <div class="d-flex justify-content-center">{{ $guilds->links('vendor.pagination.default') }}</div>
</x-admin-layout>