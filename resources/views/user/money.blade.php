<x-app-layout>
    <x-slot name="title">Money</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="money-page">

        <h4>Pending Transactions</h4>
        <div class="grid-x grid-margin-x mb-25">
            <div class="cell medium-6">
                <div class="container text-center">
                    <div class="currency-amount text-cash">{{ number_format(auth()->user()->getTotalFutureCash()) }}</div>
                    <div class="currency-title">cash</div>
                </div>
                <div class="push-25 show-for-small-only"></div>
            </div>
            <div class="cell medium-6">
                <div class="container text-center">
                    <div class="currency-amount text-coins">{{ number_format(auth()->user()->getTotalFutureCoins()) }}</div>
                    <div class="currency-title">coins</div>
                </div>
                <div class="push-25 show-for-small-only"></div>
            </div>
        </div>
        <div class="push-25"></div>
        
        <h4>Balance</h4>
        <div class="grid-x grid-margin-x mb-25">
            <div class="cell medium-6">
                <div class="container text-center">
                    <div class="currency-amount text-cash">{{ number_format(auth()->user()->cash) }}</div>
                    <div class="currency-title">cash</div>
                </div>
                <div class="push-25 show-for-small-only"></div>
            </div>
            <div class="cell medium-6">
                <div class="container text-center">
                    <div class="currency-amount text-coins">{{ number_format(auth()->user()->coins) }}</div>
                    <div class="currency-title">coins</div>
                </div>
                <div class="push-25 show-for-small-only"></div>
            </div>
        </div>
        <div class="text-center mb-25">
            <button class="money-button" data-toggle="convert-modal">Convert Currencies</button>
        </div>
        <div class="push-25"></div>

        <div class="container">
            <div class="grid-x grid-margin-x text-center">
                <div class="cell medium-2 small-3">
                    Date
                </div>
                <div class="cell medium-3 small-3">
                    Asset
                </div>
                <div class="cell medium-2 hide-for-small-only">
                    Member
                </div>
                <div class="cell medium-3 small-3">
                    Type
                </div>
                <div class="cell medium-2 small-3">
                    Amount
                </div>
            </div>
            <span id="trans-data">
                @if($transactions->count() > 0)
                    @include('components.load_user_transactions')
                @else
                    <div class="text-center" style="margin-top:30px;">No transactions :(</div>
                @endif
            </span>
            <div style="margin-top:20px;">
                {{ $transactions->onEachSide(1)->links('vendor.pagination.default') }}
            </div>
        </div>
        <div class="modal reveal" id="convert-modal" data-reveal>
            <form action="{{ route('user.trade.currency') }}" method="POST">
                @csrf
                <div class="modal-title">Convert</div>
                <div class="modal-content">
                    <p>Current Rate: <span class="text-cash">1 Cash</span> = <span class="text-coins">10 Coins</span></p>
                    <input class="form-input" type="number" name="amount" placeholder="Amount" required>
                    <select class="form-input" name="currency">
                        <option value="cash" selected>Cash to Coins</option>
                        <option value="coins">Coins to Cash</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <div class="modal-buttons">
                        <button class="modal-button" type="submit">CONVERT</button>
                        <button class="modal-button" type="button" data-close>CANCEL</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</x-app-layout>