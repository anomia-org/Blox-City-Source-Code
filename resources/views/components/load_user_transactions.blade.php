@foreach($transactions as $transaction)
    <div class="grid-x grid-margin-x">
        <div class="transaction-contain cell medium-2 small-3">
            {{ $transaction->created_at }}
        </div>
        <div class="transaction-contain cell medium-3 small-3">
            @if($transaction->source_type == 4)
                <img src="{{ ('/img/branding/icon_text.png') }}" class="p-2 img-fluid" width="60" />
                <div class="hide-for-small-only" style="margin-left:6px;">System</div>
            @else
                <a href="{{ $transaction->url() }}"><img src="{{ $transaction->image() }}" class="img-fluid" width="70" /></a>
                <a href="{{ $transaction->url() }}">
                    <div class="hide-for-small-only">{{ $transaction->source->name }}</div>
                </a>
            @endif
        </div>
        <div class="transaction-contain cell medium-2 hide-for-small-only">
            <a href="{{ route('user.profile', $transaction->get_member->id) }}"><img src="{{ $transaction->get_member->get_headshot() }}" style="border-radius:100%;" width="60" /></a>
            <a href="{{ route('user.profile', $transaction->get_member->id) }}" class="truncate" style="margin-left:10px;">{{ $transaction->get_member->username }}</a>
        </div>
        <div class="transaction-contain cell medium-3 small-3">
            {{ $transaction->get_type() }}
        </div>
        <div class="transaction-contain cell medium-2 small-3">
            @if($transaction->cash > 0)
                <span class="text-success">
                    <i class="currency currency-cash currency-md currency-align"></i>&nbsp;{{ number_format($transaction->cash) }}&nbsp;&nbsp;
                </span>
            @endif
            @if($transaction->coins > 0)
                <span class="text-warning">
                    <i class="currency currency-coin currency-lg currency-align"></i>&nbsp;{{ number_format($transaction->coins) }}
                </span>
            @endif
            @if($transaction->cash <= 0 && $transaction->coins <= 0)
                <span style="color:#2196F3;font-weight:bold;">
                    Free
                </span>
            @endif
        </div>
    </div>
@endforeach