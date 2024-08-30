<x-app-layout>
    <meta name="community-info" data-id="{{ $guild->id }}">
    <x-slot name="title">Manage "{{ $guild->name }}"</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-10 medium-offset-1">
            <div class="container">
                <h5>Manage "{{ $guild->name }}"</h5>
                <hr>
                <form action="{{ route('groups.edit.general.post', $guild->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label">Description</label>
                    <textarea class="form-input" id="desc" name="desc" rows="5" placeholder="(optional)" style="white-space: pre-line" @if($guild->owner->id != auth()->user()->id) disabled @endif>{{ $guild->desc }}</textarea>

                    <label class="form-label">Icon</label>
                    <input class="form-control" type="file" name="image" id="formFile" required>
                    <button class="button button-blue" type="submit">Update</button>
                </form>
            </div>
            <div class="push-25 show-for-small-only"></div>
        </div>
    </div>
</x-app-layout>