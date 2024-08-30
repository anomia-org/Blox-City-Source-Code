<x-admin-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <div class="card">
        <div class="card-header">{{ $title }}</div>
        <div class="card-body">
            <form action="{{ route('ais.create_item.create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="row">
                    <div class="col-md-6">
                        <label for="name">Name</label>
                        <input class="form-control mb-2" type="text" name="name" placeholder="Item Name">
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col">
                                <label for="cash">
                                    <span>Cash</span>
                                </label>
                                <input class="form-control mb-2" type="number" name="cash" placeholder="Cash" min="0" max="1000000">
                            </div>
                            <div class="col">
                                <label for="coins">
                                    <span>Coins</span>
                                </label>
                                <input class="form-control mb-2" type="number" name="coins" placeholder="Coins" min="0" max="1000000">
                            </div>
                        </div>
                    </div>
                </div>
                <label for="description">Description</label>
                <textarea class="form-control mb-2" name="description" placeholder="Item Description" rows="5"></textarea>
                <label for="stock">Special Stock</label>
                <input class="form-control mb-2" type="number" name="stock" placeholder="Special Stock" min="0" max="500">
                <label for="onsale_for">Onsale For</label>
                <select class="form-control mb-2" name="onsale_for">
                    <option value="forever" selected>Forever</option>
                    <option value="1_hour">1 Hour</option>
                    <option value="12_hours">12 Hours</option>
                    <option value="1_day">1 Day</option>
                    <option value="3_days">3 Days</option>
                    <option value="7_days">7 Days</option>
                    <option value="14_days">14 Days</option>
                    <option value="21_days">21 Days</option>
                    <option value="1_month">1 Month</option>
                </select>
                <div class="row">
                    @if ($type != 'head')
                        <div class="col-12 mb-2">
                            <label for="texture">Texture @if($type != 'face') (optional) @endif</label><br>
                            <input name="texture" type="file" accept=".png,.jpg,.jpeg">
                        </div>

                        @if ($type != 'face')
                            <div class="col-12 mb-2">
                                <label for="material">Material (optional)</label><br>
                                <input name="material" type="file" accept=".mtl">
                            </div>
                        @endif
                    @endif

                    @if ($type != 'face')
                        <div class="col-12 mb-2">
                            <label for="model">Model</label><br>
                            <input name="model" type="file" accept=".obj">
                        </div>
                    @endif
                </div>
                <label>Options</label>
                <div class="row mb-1">
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="collectible">
                            <label class="form-check-label" for="collectible">Collectible</label>
                        </div>
                    </div>
                </div>
                <button class="green w-100" type="submit">Create</button>
            </form>
        </div>
    </div>
</x-admin-layout>