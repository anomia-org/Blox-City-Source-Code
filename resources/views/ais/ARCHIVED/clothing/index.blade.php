<x-admin-layout>
    <x-slot name="title">Clothing</x-slot>
    <div class="row">

        <div class="col-md-6 col-xl-6">
            <h3>Pending Uploads</h3>
            <div class="card mb-4">
                <a href="{{ route('ais.clothing.pending') }}" class="text-body font-weight-normal text-decoration-none">
                    @if(App\Models\Item::where('pending', '1')->get()->count() > 0)
                    <div class="card-header bg-danger-800">
                        <i class="bi-exclamation-triangle-fill text-danger"></i> Needs Attention!
                    </div>
                    @else
                    <div class="card-header">
                        <i class="bi-check-lg text-success"></i> Caught up
                    </div>
                    @endif
                    <div class="card-body">
                        <h2 class="mb-1">{{ App\Models\Item::where('pending', '1')->get()->count() }}</h2>
                        <p class="mb-0">Pending Items</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-6 col-xl-6">
            <h3>Existing Clothing</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6 text-center">
                            <h2 class="mb-0">{{ number_format(App\Models\Item::where('type', '4')->get()->count()) }}</h2>
                            <p class="mb-0">Shirts</p>
                        </div>
                        <div class="col-6 text-center">
                            <h2 class="mb-0">{{ number_format(App\Models\Item::where('type', '5')->get()->count()) }}</h2>
                            <p class="mb-0">Pants</p>
                        </div>
                    </div>
                    <form class="row">
                        <div class="col">
                            <label class="visually-hidden">Search</label>
                            <input type="text" class="form-control" id="search" placeholder="Type a query...">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>