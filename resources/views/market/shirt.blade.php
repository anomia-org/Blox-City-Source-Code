<x-app-layout>
    <x-slot name="title">Create Shirt</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="grid-x grid-margin-x">
<div class="cell small-12 medium-6 ">
<div class="container">
<h5>Create New Shirt</h5>
<hr>
<form action="{{ route('market.create.shirt.process') }}" method="POST" enctype="multipart/form-data">
 @csrf
<label class="form-label">Name</label>
<input class="form-input" type="text" id="title" name="title" placeholder="Name" value required>
<label class="form-label">Description</label>
<textarea class="form-input" id="description" name="description" rows="5"></textarea>

<label class="form-label">Template</label>
<input class="form-control" type="file" name="image" id="formFile" required>
<button class="button button-blue" type="submit">Create</button>
</form>
</div>
<div class="push-25 show-for-small-only"></div>
</div>
<div class="cell small-12 medium-6">
<div class="container">
<h5>Template</h5>
<hr>
<a href="https://i.vgy.me/ci0CU3.png" target="_blank">
<img src="https://i.vgy.me/ci0CU3.png" style="width:100%;">
</a>
<p style="color:red;"><br>Roblox templates are not compatible on BLOX City.</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</x-app-layout>