@foreach($items as $item)
<div class="cell small-6 medium-2 market-item-cell">
                    <a href="{{ route('market.item', $item->id) }}" title="{{ $item->name }}">
                        @if($item->special) <div class="ribbon"><span>Collectible</span></div> @endif
                        <img class="market-item-thumbnail " src="{{ $item->get_render() }}">
                    </a>
                    <a href="{{ route('market.item', $item->id) }}" class="market-item-name" title="{{ $item->name }}">{{ $item->name }}</a>
					@if($item->creator_type == 1)
                     <div class="market-item-creator">Creator: <a href="{{ route('user.profile', $item->creator_id) }}">{{ $item->owner->username }}</a></div>
                        @elseif($item->creator_type == 2)
                            <div class="market-item-creator">Creator: <a href="{{ route('groups.view', $item->creator_id) }}">{{ $item->owner->name }}</a></div>
                        @endif
                    
                    <div class="market-item-price">
					
					    @if(!$item->free())
                            @if($item->stock() > 0 || $item->stock() == -1)
                                @if($item->cash > 0)
                                <div class="market-item-price-cash">
                                <i class="icon icon-cash"></i> {{ $item->get_short_price($item->cash) }}
                                </div>
                                @endif
                                @if($item->coins > 0)
                                <div class="market-item-price-coins">
                                <i class="icon icon-coins"></i> {{ $item->get_short_price($item->coins) }}
                                </div>
                                @endif
                            @endif
                        @else
                        Free
                        @endif

                        @if($item->cash == 0 && $item->coins == 0)
                        
					    @endif
                       
                        @if($item->stock() > 0 && $item->special)
						<p style="color:#E71D36">{{ $item->stock() }} Remaining</p>
                        @elseif($item->stock() <= 0 && $item->special)
                        <p style="color:#E71D36">Sold Out</p>
                        @endif

                        @if($item->offsale_at != NULL && $item->special == 0 && !$item->offsale_at->isPast() && (($item->cash > 0 && $item->coins > 0) || ($item->coins < 0 && $item->cash < 0)))
                        <p style="color:#E71D36">Offsale in {{ $item->offsale_at->diffForHumans(null, true, true) }}</p>
                        @endif					   
   
                       
                    </div>
                </div>
				@endforeach
                