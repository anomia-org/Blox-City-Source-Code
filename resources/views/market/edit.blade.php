<x-app-layout>
    <x-slot name="title">Editing "{{ $item->name }}"</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-6">
            <div class="container"> 
                <h5>Edit {{ $item->name }}</h5>
                <hr>
                <form action="{{ route('market.item.edit.post', $item->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <label class="form-label">Name</label>
                    <input class="form-input" type="text" name="title" placeholder="Name" value="{{ $item->name }}" required>
                    <label class="form-label">Description</label>
                    <textarea class="form-input" name="description" rows="5">{{ $item->desc }}</textarea>
                    <label class="form-label">Onsale</label>
                    <select class="form-input" name="onsale">
                        <option value="0" @if($item->cash == 0 && $item->coins == 0) selected @endif>No</option>
                        <option value="1" @if(($item->cash > 0 || $item->coins > 0) || ($item->cash < 0 || $item->coins < 0)) selected @endif>Yes</option>
                    </select>
                    <div id="sale-options" @if($item->cash == 0 && $item->coins == 0) style="display:none;" @endif>
                        <div class="grid-x grid-margin-x mb-15">
                            <div class="cell auto">
                                <label class="form-label">Coins</label>
                                <input class="form-input" type="number" name="coins" placeholder="Price" value="{{ $item->coins }}" required>
                            </div>
                            <div class="cell auto">
                                <label class="form-label">Cash</label>
                                <input class="form-input" type="number" name="cash" placeholder="Price" value="{{ $item->cash }}" required>
                            </div>
                        </div>
                    </div>
                    @if ((Auth::user()->power > 3) && ($item->type == 1 || $item->type == 2 || $item->type == 3))
                        <label class="form-label">Texture</label>
                        <input name="image" type="file">
                    @endif
                    @if ($item->type == 1 || $item->type == 2 || $item->type == 3)
                        <label class="form-label">Public View</label>
                        <select class="form-input" name="public_view">
                            <option value="0" @if (!$item->public_view) selected @endif>No</option>
                            <option value="1" @if ($item->public_view) selected @endif>Yes</option>
                        </select>
                        <label class="form-label">Collectible</label>
                        <select class="form-input" name="collectible">
                            <option value="0" @if (!$item->collectible) selected @endif>No</option>
                            <option value="1" @if ($item->collectible) selected @endif>Yes</option>
                        </select>
                        @if ($item->type != 7)
                            @if ($item->type != 2)
                                <label class="form-label">Texture</label>
                                <input name="image" type="file">
                            @endif
                            @if ($item->type == 2)
                                <label class="form-label">Face Texture</label>
                                <input name="faceimg" type="file">
                            @endif
                            @if ($item->type != 2)
                                <label class="form-label">Model</label>
                                <input name="model" type="file">
                            @endif
                        @else
                            <label class="form-label">Box Contents</label>
                            <input class="form-input" type="text" name="box_contents" placeholder="Enter item IDs (Example: 12, 75, 58)" required>
                        @endif
                    @endif
                    <input class="button button-blue" value="Update" type="submit" />
                </form>
            </div>
            <div class="push-25 show-for-small-only"></div>
        </div>
        <div class="cell small-12 medium-6">
            <div class="container">
                <h5>Thumbnail</h5>
                <hr>
                <div class="text-center">
                    <img src="{{ $item->get_render() }}" style="max-width:100%;">
                </div>
            </div>
        </div>
    </div>
    <x-slot name="script">
        <script>
            $(function() {
                $('select[name="onsale"]').change(function() {
                    if (this.value == 1) {
                        $('#sale-options').show();
                        $('input[name="price_coins"]').val('0');
                        $('input[name="price_cash"]').val('0');
                    } else {
                        $('#sale-options').hide();
                        $('input[name="price_coins"]').val('0');
                        $('input[name="price_cash"]').val('0');
                    }
                });

                @if ($item->type == 1 || $item->type == 2 || $item->type == 3 || $item->type == 7 || $item->type == 8)
                    $('select[name="collectible"]').change(function() {
                        if (this.value == 1) {
                            $('#collectible-options').show();
                            $('input[name="collectible_stock"]').val('');
                        } else {
                            $('#collectible-options').hide();
                            $('input[name="collectible_stock"]').val('0');
                        }
                    });
                @endif
            });
        </script>
    </x-slot>
</x-app-layout>