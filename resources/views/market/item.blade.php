<x-app-layout>
    <x-slot name="title">{{ $item->name }}</x-slot>
    <x-slot name="pageDescription">{{ $item->desc }}</x-slot>
    <x-slot name="pageImage">{{ $item->get_render() }}</x-slot>
    <x-slot name="navigation"></x-slot>

    <meta
        name="item-data"
        data-id="{{ $item->id }}"
        data-collectible="{{ $item->special }}"
    >

            <body class="item-page">
                <!-- Item data -->
                <div class="container mb-25">
                    <div class="grid-x grid-margin-x">
                        <div class="cell small-12 medium-3">
                            <img style="width:250px!important;" src="{{ $item->get_render() }}">
                            <div class="push-15"></div>
                            <div class="text-center">
                            </div>
                            <div class="push-25 show-for-small-only"></div>
                        </div>
                        <div class="cell small-12 medium-6">
                            <div class="item-name">{{ $item->name }}</div>
                            <div class="item-type">{{ $item->get_type() }}</div>
                            <div class="item-description" style="white-space: pre-line">
                                @if($item->desc != null)
                                {{ $item->desc }}
                                @else
                                No description set.
                                @endif</div>
                            <div class="push-25 show-for-small-only"></div>
                        </div>

                        <div class="text-center cell small-12 medium-3">
                            <div class="modal market-modal reveal" id="buy-modal" data-reveal>
                                <div class="modal-title" id="buy-modal-title"></div>
                                <div class="modal-content" id="buy-modal-body"></div>
                                <div class="modal-footer" id="buy-modal-footer"></div>
                            </div>
                            @auth
                                @if((!auth()->user()->owns($item) && $item->stock() > 0) || (!auth()->user()->owns($item) && $item->stock() == -1))
                                    @if($item->cash > 0)

                                    <!-- cash modal -->
                                    <div class="modal fade" style="padding:0!important;" id="coinModal" tabindex="-1" aria-labelledby="coinsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                @if(auth()->user()->cash >= $item->cash)
                                                    <form method="POST" action="{{ route('market.item.buy', [$item->id, '1']) }}">
                                                        @csrf
                                                        <button type="submit" class="button button-block button-cash item-buy-button">
                                                            Buy for {{ number_format($item->cash) }} Cash
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="button button-block button-cash item-buy-button">
                                                        Insufficient Funds
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end cash modal -->
                                    @endif

                                    @if($item->coins > 0)
                                    <!-- coins modal -->
                                    <div class="modal fade" style="padding:0!important;" id="coinModal" tabindex="-1" aria-labelledby="coinsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                @if(auth()->user()->coins >= $item->coins)
                                                    <form method="POST" action="{{ route('market.item.buy', [$item->id, '2']) }}">
                                                        @csrf
                                                        <button type="submit" class="button button-block button-coins item-buy-button">
                                                            Buy for {{ number_format($item->coins) }} Coins
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="button button-block button-coins item-buy-button">
                                                        Insufficient Funds
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end coins modal -->
                                    @endif

                                    @if($item->free())
                                    <!-- free modal -->
                                    <div class="modal fade" id="freeModal" tabindex="-1" aria-labelledby="freeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('market.item.buy', [$item->id, '3']) }}">
                                                    @csrf
                                                    <button type="submit" class="button button-block button-blue">
                                                        Buy Now
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end free modal -->
                                    @endif
                                @endif
                            @if($item->owner->id == auth()->user()->id)
                            <a href="{{ route('market.item.edit', $item->id) }}" class="button button-block button-blue item-buy-button">Edit Item</a>
                            @endif
                            @endauth
                            <div class="item-creator-title">Creator</div>
                            <a href="{{ route('user.profile', $item->owner->id) }}">
                                <div class="item-creator-avatar">
                                    <img class="item-creator-avatar-image" src="@if($item->owner->id == 1) {{ asset('img/branding/icon_text.png') }} @else {{ $item->owner->get_headshot() }} @endif">
                                </div>
                            </a>
                            <a href="{{ route('user.profile', $item->owner->id) }}" class="item-creator-username">{{ $item->owner->username }}</a>
                            <button><i class="icon icon-favorite item-favorite"></i></button>
                        </div>
                    </div>
                    <div class="push-25"></div>
                    <div class="text-center grid-x grid-margin-x">
                        <div class="cell small-6 medium-3">
                            <div class="item-stat-result">{{ Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}</div>
                            <div class="item-stat-name">Time Created</div>
                        </div>
                        <div class="cell small-6 medium-3">
                            <div class="item-stat-result">{{ $item->updated_real->diffForHumans() }}</div>
                            <div class="item-stat-name">Last Updated</div>
                            <div class="push-15 show-for-small-only"></div>
                        </div>
                        <div class="cell small-6 medium-3">
                            <div class="item-stat-result">{{ $item->get_short_price($item->sold()) }}</div>
                            <div class="item-stat-name">Owners</div>
                        </div>
                        <div class="cell small-6 medium-3">
                            <div class="item-stat-result">0</div>
                            <div class="item-stat-name">Favorites</div>
                        </div>
                    </div>
                </div>
                <!-- Item data end -->

                @if ($item->special && $item->stock() <= 0)
                    @auth
                        <div class="modal market-modal reveal" id="buy-collectible-modal" data-reveal>
                            <div class="modal-title" id="buy-collectible-modal-title"></div>
                            <div class="modal-content" id="buy-collectible-modal-body"></div>
                            <div class="modal-footer" id="buy-collectible-modal-footer"></div>
                        </div>
                        @if (auth()->user()->owns($item))
                            <div class="modal market-modal reveal" id="sell-collectible-modal" data-reveal>
                                <form action="{{ route('market.list', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-title">Sell Collectible</div>
                                    <div class="modal-content">
                                        <select class="form-input" name="serial">
                                            @foreach(auth()->user()->specials() as $special)
                                                @if(!$special->onsale() && $special->item_id == $item->id)
                                                    <option value="{{ $special->id }}">Serial #{{ $special->getSerialNumber() }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input class="form-input" type="number" name="price" placeholder="Price" required>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="modal-buttons">
                                            <button class="button button-blue" type="submit">SELL</button>
                                            <button class="button button-red" style="margin-left:10px;" data-close>CANCEL</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endauth
                        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                        <div class="item-header">Collectible Information</div>
                        <div class="container mb-25">
                            <div class="item-estimated-value-header">Estimated Value: <span class="text-cash">${{ number_format($item->avgResalePrice()) }} Cash</span></div>
                            <canvas id="valueovertime" style="height:250px;width:100%" class="chartjs-render-monitor"></canvas>
                                <script>
                                    var ctx = document.getElementById('valueovertime').getContext('2d');
                                    var myChart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels:  <?php echo json_encode($item->chartLabels()); ?>,
                                            datasets: [{
                                                label: "Cash Price",
                                                data:  <?php echo json_encode($item->chartData()); ?>,
                                                backgroundColor: [
                                                    'rgba(64, 164, 80, 0.5)'
                                                ],
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        min: 26
                                                    }
                                                }]
                                            },
                                            responsive: false,
                                            maintainAspectRatio: false,
                                        }
                                    });
                                </script>
                        </div>
                    @auth
                        <div class="grid-x grid-margin-x">
                            <div class="auto cell">
                                <div class="item-private-sellers-header">Private Sellers</div>
                            </div>
                            @if (auth()->user()->owns($item))
                                <div class="text-right shrink cell">
                                    <div class="button button-blue" style="padding:5px 25px;" data-toggle="sell-collectible-modal">Sell</div>
                                    <div class="push-10"></div>
                                </div>
                            @endif
                        </div>
                        <div class="container item-private-sellers-container mb-25">
                            @forelse ($markets as $reseller)
                                <div class="align-middle grid-x grid-margin-x reseller">
                                    <div class="text-center cell small-4 medium-2">
                                        <div class="item-private-seller-user-holder">
                                            <a href="{{ route('user.profile', $reseller->seller) }}">
                                                <div class="item-private-seller-avatar">
                                                    <img class="item-private-seller-avatar-image" src="{{ $reseller->seller->get_headshot() }}">
                                                </div>
                                            </a>
                                            <a href="{{ route('user.profile', $reseller->seller) }}" class="item-private-seller-username">{{ $reseller->seller->username }}</a>
                                        </div>
                                    </div>
                                    <div class="text-center cell small-4 medium-3 medium-offset-2">
                                        <code class="form-input">#{{ $reseller->inventory->getSerialNumber() }} / {{ $item->sold() }}</code>
                                    </div>
                                    <div class="text-right cell small-4 medium-4 medium-offset-1">
                                        @if (Auth::check() && $reseller->seller->id == Auth::user()->id)
                                            <form action="{{ route('market.unlist', $item->id) }}" method="POST">
                                                @csrf
                                                <input hidden name="listing" value="{{ $reseller->id }}" />
                                                <button class="button button-red item-buy-button" type="submit">Take Offsale</button>
                                            </form>
                                        @else
                                            <button class="button button-green item-buy-button" data-price="{{ $reseller->price }}" data-listing-id="{{ $reseller->id }}" data-serial-id="{{ $reseller->inventory->collection_number }}" data-toggle="buy-collectible-modal">Buy for {{ number_format($reseller->price) }} Cash</button>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            @empty
                                <p>There is currently nobody selling this item.</p>
                            @endforelse
                        </div>
                    @endauth
                @endif

                <div class="tabs">
                    <div class="tab" style="width:50%;">
                        <a class="tab-link active" id="comments_tab">Comments</a>
                    </div>
                    <div class="tab" style="width:50%;">
                        <a class="tab-link" id="suggested_tab">Suggested Items</a>
                    </div>
                </div>
                <div class="container" id="comments">
                    @auth
                        <form action="{{ route('market.item.comment', $item->id) }}" method="POST">
                            @csrf
                            <textarea class="form-input" name="body" placeholder="Write your comment here." rows="3"></textarea>
                            <button class="button button-blue" type="submit">Post</button>
                        </form>
                        <hr>
                    @endauth
                    <div class="item-comments">
                        @if($comments->count() > 0)
                            @include('components.load_item_comments')
                            <div class="container" style="border:none!important;">
                                {{ $comments->onEachSide(1)->links('vendor.pagination.default') }}
                            </div>
                        @else
                        <center class="mt-4">No comments :(</center>
                        @endif
                    </div>
                </div>
                <div class="container" id="suggested" style="display:none;">
                <div class="grid-x grid-margin-x">
                    @forelse ($suggestions as $suggestion)
                        <div class="cell small-6 medium-3">
                            <div class="suggested-item">
                                <a href="{{ route('market.item', $suggestion) }}" title="{{ $suggestion->name }}">
                                    <img class="market-item-thumbnail" src="{{ $suggestion->get_render() }}">
                                </a>
                                <a href="{{ route('market.item', $suggestion) }}" class="market-item-name" title="{{ $suggestion->name }}">{{ $suggestion->name }}</a>
                                <div class="market-item-creator">Creator: <a href="{{ route('user.profile', $suggestion->owner) }}">{{ $suggestion->owner->username }}</a></div>

                                    <div class="market-item-price">
                                        @if(!$suggestion->free())
                                            @if($suggestion->stock() > 0 || $suggestion->stock() == -1)
                                                @if($suggestion->cash > 0)
                                                <div class="market-item-price-cash">
                                                <i class="icon icon-cash"></i> {{ $suggestion->get_short_price($suggestion->cash) }}
                                                </div>
                                                @endif
                                                @if($suggestion->coins > 0)
                                                <div class="market-item-price-coins">
                                                <i class="icon icon-coins"></i> {{ $suggestion->get_short_price($suggestion->coins) }}
                                                </div>
                                                @endif
                                            @endif
                                        @else
                                        Free
                                        @endif

                                        @if($suggestion->cash == 0 && $suggestion->coins == 0)
                                        
                                        @endif
                                    
                                        @if($suggestion->stock() > 0 && $suggestion->special)
                                        <p style="color:#E71D36">{{ $suggestion->stock() }} Remaining</p>
                                        @elseif($suggestion->stock() <= 0 && $suggestion->special)
                                        <p style="color:#E71D36">Sold Out</p>
                                        @endif

                                        @if($suggestion->offsale_at != NULL && $suggestion->special == 0 && !$suggestion->offsale_at->isPast() && (($suggestion->cash > 0 && $suggestion->coins > 0) || ($suggestion->coins < 0 && $suggestion->cash < 0)))
                                        <p style="color:#E71D36">Offsale in {{ $suggestion->offsale_at->diffForHumans(null, true, true) }}</p>
                                        @endif
                                    </div>
                            </div>
                            <div class="push-15 show-for-small-only"></div>
                        </div>
                    @empty
                        <div class="cell auto">There are no suggested items.</div>
                    @endforelse
                </div>
            </div>
            </div>
        </body>

    <x-slot name="script">
        <script>
            var currentTab = 'comments';

            $(function() {
                $('.tab-link').click(function(tab) {
                    $(`#${currentTab}_tab`).removeClass('active');
                    $(`#${tab.target.id}`).addClass('active');

                    $(`#${currentTab}`).hide();

                    currentTab = tab.target.id.replace('_tab', '');

                    $(`#${currentTab}`).show();
                });
            });
        </script>
        @auth
            <script>
                var itemData;
                var itemId;
                var isCollectible;

                $(function() {
                    itemData = $('meta[name="item-data"]');
                    itemId = parseInt(itemData.attr('data-id'));
                    isCollectible = parseInt(itemData.attr('data-collectible'));

                    $('[data-listing-id]').click(function() {
                        var title;
                        var body;
                        var footer = '';
                        var price = parseInt($(this).attr('data-price'));
                        var cash = {{ auth()->user()->cash }};
                        var balanceAfter = cash - price;
                        var listingId = parseInt($(this).attr('data-listing-id'));
                        var serialId = $(this).attr('data-serial-id');

                        $('#buy-collectible-modal-title').empty();
                        $('#buy-collectible-modal-body').empty();
                        $('#buy-collectible-modal-footer').empty();

                        if (cash < price) {
                            title = 'Insufficient Cash';
                            body = `You do not have enough <div class="balance-after-cash">Cash</div> to purchase this item.`;
                        } else if (cash >= price) {
                            title = 'Purchase Serial #' + serialId;
                            body = `Are you sure you wish to purchase this item? You balance after this transaction will be <div class="balance-after-cash">${balanceAfter}</div> Cash.`;
                        } else {
                            title = 'Error';
                            body = 'An unexpected error has occurred';
                        }

                        if (cash >= price) {
                            footer = `
                            <form action="/market/item/${itemId}/listing/${listingId}/buy" method="POST">
                                @csrf
                                <input type="hidden" name="listing" value="${listingId}">
                                <button class="button button-green" type="submit">BUY NOW</button>
                            </form>`;
                        }

                        $('#buy-collectible-modal-title').text(title);
                        $('#buy-collectible-modal-body').html(body);
                        $('#buy-collectible-modal-footer').html('<div class="modal-buttons">' + footer + '<button class="button button-red" style="margin-left:10px;" data-close>CANCEL</button></div>');
                        $('#buy-collectible-modal').foundation('reveal', 'open');
                    });
                });

            </script>
        @endauth
    </x-slot>
</x-app-layout>