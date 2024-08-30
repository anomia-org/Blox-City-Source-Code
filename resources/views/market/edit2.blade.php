<x-app-layout>
    <x-slot name="title">Editing "{{ $item->name }}"</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h4>
                <a href="{{ route('market.item', $item->id) }}" class="text-sm align-middle text-muted me-2" data-bs-toggle="tooltip" title="Go back"><i class="bi bi-chevron-double-left fw-bold"></i></a>Editing "{{ $item->name }}"
            </h4> 
            <div class="card card-body">
                <div class="text-center">
                    <img src="{{ $item->get_render() }}" class="img-fluid" width="150">
                </div>
                <form method="POST" action="{{ route('market.item.edit.post', $item->id) }}">
                    @csrf
                    <label class="text-sm text-muted fw-bold">NAME:</label>
                    <input type="text" class="form-control mb-2" name="title" placeholder="Yellow Blazer" value="{{ $item->name }}" />
                    <label class="text-sm text-muted fw-bold">DESCRIPTION:</label>
                    <textarea class="form-control mb-3" rows="6" name="description" placeholder="Now in yellow!">{{ $item->desc }}</textarea>
                    <div class="text-muted text-sm fw-bold">PRICE</div>
                    <div class="d-flex gap-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="free" id="free" @if($item->cash < 0 && $item->coins < 0) checked @endif />
                            <label class="form-check-label" for="free">
                                Free
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="offsale" id="offsale" @if($item->cash == 0 && $item->coins == 0) checked @endif />
                            <label class="form-check-label" for="offsale">
                                Offsale
                            </label>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mb-3 @if(($item->cash == 0 && $item->coins == 0) || ($item->cash < 0 && $item->coins < 0)) d-none @endif" id="prices">
                        <div class="input-parent has-icon">
                            <i class="text-success bi bi-cash-stack"></i>
                            <input type="number" name="cash" class="form-control" placeholder="Price in Cash" value="{{ $item->cash }}" />
                        </div>
                        <div class="input-parent has-icon">
                            <i class="text-warning bi bi-coin"></i>
                            <input type="number" name="coins" class="form-control" placeholder="Price in Coins" value="{{ $item->coins }}" />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
    <div class="mb-2">&nbsp;</div>
    <x-slot name="script">
        <script>
            $('input:checkbox').change(function() {
                if($(this).is(':checked'))
                    $('#prices').addClass('d-none');
                else
                    $('#prices').removeClass('d-none');
            });
        </script>
    </x-slot>
</x-app-layout>