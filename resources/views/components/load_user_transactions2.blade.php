@foreach($transactions as $transaction)
<div class="section">
    <div class="row align-items-center">
        <div class="col-md-2">
            <div class="d-block d-md-none text-xs fw-bold text-muted text-uppercase mb-2">
                Date
            </div>
            <div class="d-flex gap-2 align-items-center mb-2 mb-md-0">
                {{ $transaction->created_at }}
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-block d-md-none text-xs fw-bold text-muted text-uppercase mb-2">
                Asset
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if($transaction->source_type == 4)
                <img src="{{ ('/img/branding/icon_text.png') }}" class="img-fluid p-2" width="60" />
                <div class="fw-semibold text-light" style="margin-left:6px;">System</div>
                @else
                <img src="{{ $transaction->image() }}" class="img-fluid" width="70" />
                <a href="{{ $transaction->url() }}">
                    <div class="fw-semibold text-light">{{ $transaction->source->name }}</div>
                </a>
                @endif
            </div>
        </div>
        <div class="col-md-2 col-6 d-none d-md-block text-center">
            <img src="{{ $transaction->get_member->get_headshot() }}" class="d-block img-fluid rounded-circle headshot mx-auto" width="60" />
            <a href="{{ route('user.profile', $transaction->get_member->id) }}" class="d-block truncate mt-1 fw-semibold text-sm">{{ $transaction->get_member->username }}</a>
        </div>
        <div class="col-md-3 col-6">
            <div class="d-block d-md-none text-xs fw-bold text-muted text-uppercase mt-3 mb-1">
                TYPE
            </div>
            {{ $transaction->get_type() }}
        </div>
        <div class="col-md-2">
            <div class="d-block d-md-none text-xs fw-bold text-muted text-uppercase mt-3 mb-1">
                Amount
            </div>
            <div>
                @if($transaction->cash > 0)
                <span class="d-block text-success fw-semibold mb-2">
                    <i class="currency currency-cash currency-md currency-align text-xl lh-1 me-1 me-md-2"></i>{{ auth()->user()->get_short_num($transaction->cash) }}
                </span>
                @endif
                @if($transaction->coins > 0)
                <span class="d-block text-warning fw-semibold">
                    <i class="currency currency-coin currency-lg currency-align text-xl lh-1 me-1 me-md-2"></i>{{ auth()->user()->get_short_num($transaction->coins) }}
                </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach