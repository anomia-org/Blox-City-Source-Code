<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Upgrade</x-slot>
    <body class="upgrade-page">
        <h5>Purchase {{ $title }}</h5>
        <br>
        <div class="grid-x grid-margin-x mb-10">
            @forelse ($plans as $upgradePlan)
                <div class="cell small-6 medium-4">
                    <div class="upgrade-header {{ $plan }}">{{ $upgradePlan['name'] }}</div>
                    <div class="upgrade-title {{ $plan }}">
                        <div class="upgrade-title-price">{{ $upgradePlan['price'] }}</div>
                    </div>
                    <div class="upgrade-benefits">
                        <div class="upgrade-button-holder">
                                <a href="{{ route('checkout', ['product' => $upgradePlan['billing_product_id']]) }}"><button class="upgrade-button {{ $plan }}" style="margin-top:0;">Buy Now</button></a>
                        </div>
                    </div>
                    <div class="push-15"></div>
                </div>
            @empty
                <div class="cell auto">There are currently no {{ $title }} plans available. Check again later.</div>
            @endforelse
        </div>
        <a href="{{ route('upgrade.index') }}">Return to Upgrades</a>
    </body>
</x-app-layout>