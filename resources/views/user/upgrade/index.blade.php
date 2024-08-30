<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Upgrades</x-slot>
    <body class="upgrade-page">
        <div class="grid-x grid-margin-x mb-25">
            <div class="cell small-12 medium-4">
                <div style="width:100%;">
                    <img src="{{ asset('img/avatar/BronzeTopperTest.png') }}" style="width:100%;" />
                </div>
                <div class="upgrade-title bronze-vip">
                    <div class="upgrade-title-price">3.99</div>
                    <div class="upgrade-title-duration">mo</div>
                </div>
                <div class="upgrade-benefits">
                    <div class="upgrade-benefit"><strong>15</strong> Daily Cash</div>
                    <div class="upgrade-benefit"><strong>30</strong> Daily Coins</div>
                    <div class="upgrade-benefit">Create/join up to <strong>15</strong> Groups</div>
                    <div class="upgrade-benefit"><strong>NO</strong> Paid Ads</div>
                    <div class="upgrade-benefit"><strong>1</strong> Special Items</div>
                    <div class="upgrade-benefit"><strong>20%</strong> Sales Tax</div>
                    <div class="upgrade-benefit"><strong>Badges</strong> On Discord/Site</div>
                    <div class="upgrade-button-holder">
                        <a href="{{ route('upgrade.plans', ['plan' => 'bronze-vip']) }}" class="upgrade-button bronze-vip">Go To Pricing</a>
                    </div>
                </div>
                <div class="push-15 show-for-small-only"></div>
            </div>

            <div class="cell small-12 medium-4">
                <div style="width:100%;">
                    <img src="{{ asset('img/avatar/GoldTopperTest5.png') }}" style="width:100%;" />
                </div>
                <div class="upgrade-title gold-vip">
                    <div class="upgrade-title-price">14.99</div>
                    <div class="upgrade-title-duration">mo</div>
                </div>
                <div class="upgrade-benefits">
                    <div class="upgrade-benefit"><strong>60</strong> Daily Cash</div>
                    <div class="upgrade-benefit"><strong>120</strong> Daily Coins</div>
                    <div class="upgrade-benefit">Create/join up to <strong>60</strong> Groups</div>
                    <div class="upgrade-benefit"><strong>NO</strong> Paid Ads</div>
                    <div class="upgrade-benefit"><strong>2</strong> Special Items</div>
                    <div class="upgrade-benefit"><strong>5%</strong> Sales Tax</div>
                    <div class="upgrade-benefit"><strong>Badges</strong> On Discord/Site</div>
                    <div class="upgrade-button-holder">
                        <a href="{{ route('upgrade.plans', ['plan' => 'gold-vip']) }}" class="upgrade-button gold-vip">Go To Pricing</a>
                    </div>
                </div>
                <div class="push-15 show-for-small-only"></div>
            </div>

            <div class="cell small-12 medium-4">
                <div style="width:100%;">
                    <img src="{{ asset('img/avatar/SilverTopperTest.png') }}" style="width:100%;" />
                </div>
                <div class="upgrade-title silver-vip">
                    <div class="upgrade-title-price">7.99</div>
                    <div class="upgrade-title-duration">mo</div>
                </div>
                <div class="upgrade-benefits">
                    <div class="upgrade-benefit"><strong>30</strong> Daily Cash</div>
                    <div class="upgrade-benefit"><strong>60</strong> Daily Coins</div>
                    <div class="upgrade-benefit">Create/join up to <strong>30</strong> Groups</div>
                    <div class="upgrade-benefit"><strong>NO</strong> Paid Ads</div>
                    <div class="upgrade-benefit"><strong>1</strong> Special Items</div>
                    <div class="upgrade-benefit"><strong>15%</strong> Sales Tax</div>
                    <div class="upgrade-benefit"><strong>Badges</strong> On Discord/Site</div>
                    <div class="upgrade-button-holder">
                        <a href="{{ route('upgrade.plans', ['plan' => 'silver-vip']) }}" class="upgrade-button silver-vip">Go To Pricing</a>
                    </div>
                </div>
                <div class="push-15 show-for-small-only"></div>
            </div>
        </div>
        <div class="push-25"></div>
        <div class="grid-x grid-margin-x">
            <div class="cell medium-6 hide-for-small-only">
                <img class="upgrade-cash-avatar" src="{{ asset('img/avatar/GuywMoney.png') }}">
            </div>
            <div class="cell medium-6">
                <div class="upgrade-cash-container">
                    <div class="upgrade-cash-title">Looking for Cash?</div>
                    <div class="upgrade-cash-description">With Cash, you can buy shiny items and more!</div>
                    <a href="{{ route('upgrade.plans', ['plan' => 'cash']) }}" class="upgrade-button cash">Check It Out</a>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>