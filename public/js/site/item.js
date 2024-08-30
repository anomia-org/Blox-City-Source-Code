

var itemData;
var itemId;
var owns;
var isOnsale;
var isCollectible;
var stockRemaining;
var priceCoins;
var priceCash;
var balanceAfterCoins;
var balanceAfterCash;

$(function() {
    itemData = $('meta[name="item-data"]');
    itemId = parseInt(itemData.attr('data-id'));
    owns = parseInt(itemData.attr('data-owns'));
    isOnsale = parseInt(itemData.attr('data-onsale'));
    isCollectible = parseInt(itemData.attr('data-collectible'));
    stockRemaining = parseInt(itemData.attr('data-stock-remaining'));
    priceCoins = parseInt(itemData.attr('data-price-coins'));
    priceCash = parseInt(itemData.attr('data-price-cash'));
    balanceAfterCoins = parseInt(itemData.attr('data-balance-after-coins'));
    balanceAfterCash = parseInt(itemData.attr('data-balance-after-cash'));

    $('[data-toggle="buy-modal"]').click(function() {
        var paymentMethod = $(this).attr('data-currency');
        var title;
        var body;
        var footer = '';

        $('#buy-modal-title').empty();
        $('#buy-modal-body').empty();
        $('#buy-modal-footer').empty();

        if (!userData.authenticated) {
            title = 'Error';
            body = 'You must login to purchase an item first.';
            footer = `<button onclick="window.location = '/login'" class="modal-button">LOGIN</button>`;
        } else if (paymentMethod != 'coins' && paymentMethod != 'cash') {
            title = 'Invalid Payment Option';
            body = `The provided currency is invalid. You can only pay with <div class="balance-after-coins">Coins</div> and <div class="balance-after-cash">Cash</div>.`;
        } else if (!isOnsale) {
            title = 'Error';
            body = 'This item is not for sale.';
        } else if (owns) {
            title = 'Error';
            body = 'You already own this item.';
        } else if (isCollectible && stockRemaining <= 0) {
            title = 'Error';
            body = 'This item is out of stock.';
        } else if (paymentMethod == 'coins' && priceCoins <= 0) {
            title = 'Error';
            body = `This item can not be purchased with <div class="balance-after-coins">Coins</div>.`;
        } else if (paymentMethod == 'cash' && priceCash <= 0) {
            title = 'Error';
            body = `This item can not be purchased with <div class="balance-after-cash">Cash</div>.`;
        } else if (paymentMethod == 'coins' && userData.coins < priceCoins) {
            title = 'Insufficient Coins';
            body = `You do not have enough <div class="balance-after-coins">Coins</div> to purchase this item.`;
        } else if (paymentMethod == 'cash' && userData.cash < priceCash) {
            title = 'Insufficient Cash';
            body = `You do not have enough <div class="balance-after-cash">Cash</div> to purchase this item.`;
        } else if (paymentMethod == 'coins') {
            title = 'Purchase Item';
            body = `Are you sure you wish to purchase this item? You balance after this transaction will be <div class="balance-after-coins">${balanceAfterCoins}</div> Coins.`;
        } else if (paymentMethod == 'cash') {
            title = 'Purchase Item';
            body = `Are you sure you wish to purchase this item? You balance after this transaction will be <div class="balance-after-cash">${balanceAfterCash}</div> Cash.`;
        } else {
            title = 'Error';
            body = 'An unexpected error has occurred';
        }

        if (!userData.authenticated) {
            footer = `<a href="/login" class="modal-button">LOGIN</a>`;
        } else if (!owns && ((paymentMethod == 'coins' && priceCoins > 0 && userData.coins >= priceCoins) || (paymentMethod == 'cash' && priceCash > 0) && userData.cash >= priceCash)) {
            footer = `
            <form action="/market/buy" method="POST">
                <input type="hidden" name="_token" value="${userData.csrf}">
                <input type="hidden" name="id" value="${itemId}">
                <input type="hidden" name="currency" value="${paymentMethod}">
                <button class="modal-button" type="submit">BUY NOW</button>
            </form>`;
        }

        $('#buy-modal-title').text(title);
        $('#buy-modal-body').html(body);
        $('#buy-modal-footer').html('<div class="modal-buttons">' + footer + '<button class="modal-button" data-close>CANCEL</button></div>');
        $('#buy-modal').foundation('reveal', 'open');
    });

    $('[data-reseller_id]').click(function() {
        var title;
        var body;
        var footer = '';
        var price = parseInt($(this).attr('data-price'));
        var balanceAfter = userData.cash - price;
        var resellerID = parseInt($(this).attr('data-reseller_id'));

        $('#buy-collectible-modal-title').empty();
        $('#buy-collectible-modal-body').empty();
        $('#buy-collectible-modal-footer').empty();

        if (!userData.authenticated) {
            title = 'Error';
            body = 'You must login to purchase an item first.';
            footer = `<button onclick="window.location = '/login'" class="modal-button">LOGIN</button>`;
        } else if (!isCollectible) {
            title = 'Error';
            body = 'This item is not a Collectible.';
        } else if (isCollectible && stockRemaining > 0) {
            title = 'Error';
            body = 'This item is not out of stock.';
        } else if (userData.cash < price) {
            title = 'Insufficient Cash';
            body = `You do not have enough <div class="balance-after-cash">Cash</div> to purchase this item.`;
        } else if (userData.cash >= price) {
            title = 'Purchase Item';
            body = `Are you sure you wish to purchase this item? You balance after this transaction will be <div class="balance-after-cash">${balanceAfter}</div> Cash.`;
        } else {
            title = 'Error';
            body = 'An unexpected error has occurred';
        }

        if (!userData.authenticated) {
            footer = `<a href="/login" class="modal-button">LOGIN</a>`;
        } else if (userData.cash >= price) {
            footer = `
            <form action="/market/buy" method="POST">
                <input type="hidden" name="_token" value="${userData.csrf}">
                <input type="hidden" name="id" value="${itemId}">
                <input type="hidden" name="reseller_id" value="${resellerID}">
                <input type="hidden" name="currency" value="cash">
                <button class="modal-button" type="submit">BUY NOW</button>
            </form>`;
        }

        $('#buy-collectible-modal-title').text(title);
        $('#buy-collectible-modal-body').html(body);
        $('#buy-collectible-modal-footer').html('<div class="modal-buttons">' + footer + '<button class="modal-button" data-close>CANCEL</button></div>');
        $('#buy-collectible-modal').foundation('reveal', 'open');
    });
});
