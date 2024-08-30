<x-admin-layout>
    <x-slot name="title">Comments</x-slot>
    <div class="row">

        <div class="col-md-12 col-xl-12">
            <h3>Existing Comments</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <h2 class="mb-0">{{ number_format(App\Models\Comment::get()->count()) }}</h2>
                            <p class="mb-0">Comments</p>
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