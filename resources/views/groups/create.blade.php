<x-app-layout>
    <x-slot name="title">Create Group</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-6 medium-offset-3">
            <div class="container">
                <h5>Create Group</h5>
                <hr>
                <form action="{{ route('groups.create.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label">Name</label>
                    <input class="form-input" type="text" id="name" name="name" placeholder="Name" value required>
                    <label class="form-label">Description</label>
                    <textarea class="form-input" id="desc" name="desc" rows="5" placeholder="(optional)"></textarea>

                    <label class="form-label">Icon</label>
                    <input class="form-control" type="file" name="image" id="formFile" required>
                    <button class="button button-blue" type="submit">Create (<i class="currency currency-cash currency-xs currency-align"></i> 30)</button>
                </form>
            </div>
            <div class="push-25 show-for-small-only"></div>
        </div>
    </div>
</x-app-layout>