<x-admin-layout>
    <x-slot name="title">Info</x-slot>
    <div class="card">
        <div class="card-header">Site Statistics</div>
        <div class="card-body text-center">
            <div class="row">
                @foreach ($siteData as $title => $value)
                    <div class="col-6 col-md-3">
                        <h4>{{ $value }}</h4>
                        <h5 class="text-muted">{{ $title }}</h5>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Economy Statistics</div>
        <div class="card-body text-center">
            <div class="row">
                @foreach ($economyData as $title => $value)
                    <div class="col-6 col-md-3">
                        <h4>{{ $value }}</h4>
                        <h5 class="text-muted">{{ $title }}</h5>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Server Information</div>
        <div class="card-body text-center">
            <div class="row">
                @foreach ($serverData as $title => $value)
                    <div class="col-6 col-md-3">
                        <h4>{{ $value }}</h4>
                        <h5 class="text-muted">{{ $title }}</h5>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>