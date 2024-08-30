<x-app-layout>
    <x-slot name="title">Manage ad for "{{ $ad->item->name }}"</x-slot>
    <x-slot name="navigation"></x-slot>
    <h4>Manage "{{ $ad->item->name }}" Ad</h4>
    <div class="card card-body">
        <div class="row">
            <div class="col-md-7 mb-4 mb-md-0">
                <img src="{{ $ad->image_path }}" class="img-fluid rounded mb-1" />
                <div class="text-sm text-muted mb-2">Your ad looks like this</div>
                <form method="post" action="{{ route('ad.manage.bid', $ad->id) }}">
                    @csrf
                    <div class="input-parent has-icon">
                        <i class="text-success bi bi-cash-stack"></i>
                        <input type="text" name="bid" class="form-control" placeholder="Bid in Cash" />
                    </div>
                    <div class="mb-2"></div>
                    <button type="submit" class="btn btn-success w-100 d-block text-center">
                        Bid more
                    </button>
                </form>
            </div>
            <div class="col-md-5">
                <div class="text-3xl fw-bold">{{ $ad->getReviewStatus() }}</div>
                <hr />
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-uppercase fw-bold text-sm text-muted">
                        Status
                    </div>
                    <div class="fw-bold">{{ ($ad->isRunning()) ? "Running" : "Not Running" }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-uppercase fw-bold text-sm text-muted">
                        Current Bid
                    </div>
                    <div class="fw-bold text-success"><i class="currency currency-cash currency-md currency-align text-lg me-1"></i> {{ number_format($ad->bid) }}</div>
                </div>
                <hr />
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-uppercase fw-bold text-sm text-muted">
                        Total Bid
                    </div>
                    <div class="fw-bold text-success"><i class="currency currency-cash currency-md currency-align text-lg me-1"></i> {{ number_format($ad->total_bids) }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-uppercase fw-bold text-sm text-muted">
                        Total Clicks
                    </div>
                    <div class="fw-bold">{{ number_format($ad->total_clicks) }}</div>
                </div>
            </div>
        </div>
    </div>    
</x-app-layout>