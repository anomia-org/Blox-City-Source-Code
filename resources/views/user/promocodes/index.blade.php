<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Redeem Promotional Codes</x-slot>
    <body class="item-page">
    <div class="alert" id="alert" style="display:none;"></div>
        <div class="container mb-25">
            <div class="grid-x grid-margin-x">
                <div class="cell medium-6">
                    <div><strong>What is a promotional code?</strong></div>
                    <p>A promotional code is a special string of characters that can be used to redeem items and/or currency which cannot be obtained any other way.</p>
                </div>
                <div class="cell medium-6">
                    <div class="grid-x grid-margin-x">
                        <div class="auto cell">
                            <input class="form-input" type="text" name="code" placeholder="Code">
                        </div>
                        <div class="shrink cell">
                            <button class="button button-block button-green" id="redeemCodeButton">Redeem</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h5>Code Items</h5>
        <div class="container">
            <div class="grid-x grid-margin-x">
                @forelse ($codeItems as $item)
                    <div class="suggested-item cell medium-2">
                        <a href="{{ route('market.item', ['item' => $item->id]) }}">
                            <img class="market-item-thumbnail" src="{{ $item->get_render() }}">
                        </a>
                        <a href="{{ route('market.item', ['item' => $item->id]) }}" class="market-item-name">{{ $item->name }}</a>
                    </div>
                @empty
                    <div class="auto cell">There are currently no code items available.</div>
                @endforelse
            </div>
        </div>
    </body>
    <x-slot name="script">
        <script>
            var lastUsedCode = '';

            $(function() {
                $('#redeemCodeButton').click(function() {
                    $('#alert').removeClass('alert-error').removeClass('alert-success').html('').hide();

                    lastUsedCode = $('input[name="code"]').val();

                    $.post('/promocodes-redeem', {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        code: lastUsedCode
                    }).done(function(data) {
                        $('#alert').show();

                        if (!data.success) {
                            $('#alert').addClass('alert-error');
                        } else {
                            $('#alert').addClass('alert-success');
                        }

                        $('#alert').text(data.message);
                    }).fail(function() {
                        $('#alert').addClass('alert-error').text('Something went wrong. Try again.');
                    });
                });
            });
        </script>
    </x-slot>
</x-app-layout>