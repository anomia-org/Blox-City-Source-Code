                    @foreach($items as $item)
                        @if(!auth()->user()->isWearing($item->item))
                            <div class="col-md-3 col-6">
                                <div class="bg-gray-500 rounded p-2 position-relative">
                                    <a href="{{ route('market.item', $item->item->id) }}">
                                        <img src="{{ $item->item->get_render() }}" class="img-fluid" data-bs-toggle="tooltip" title="{{ $item->item->name }}" />
                                    </a>
                                        <button onclick="wearItem({{ $item->item->id }})" class="btn btn-success btn-sm position-absolute top-100 start-50 translate-middle px-3">
                                            Wear
                                        </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    
                    @if($items->count() < 1)
                    <div class="col-6">No items found :(</div>
                    @endif

                    <div class="d-flex justify-items-center">{{ $items->links('vendor.pagination.default') }}</div>