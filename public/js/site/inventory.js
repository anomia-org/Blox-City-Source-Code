

var userId;
var currentCategory;
var currentPage;

$(function() {
    getInventory('hats', 1);

    $('.profile-inventory-category').click(function() {
        getInventory($(this).attr('data-category'), 1);
    });
});

function getInventory(category, page)
{
    userId = $('meta[name="user-info"]').attr('data-id');
    currentCategory = category;

    $.get('/users/avatar/inventory', {
        id: userId,
        category: category,
        page: page
    }).done(function(data) {
        $('#inventory').html('');

        if (typeof data.success !== 'undefined' && !data.success) {
            $('#inventory').removeClass('grid-x grid-margin-x').html(`
            <div class="push-50"></div>
            <div class="text-center">
                <h1><i class="icon icon-sad"></i></h1>
                <h5>${data.message}</h5>
            </div>`);
        } else {
            $('#inventory').addClass('grid-x grid-margin-x');

            $.each(data.items, function() {
                $('#inventory').append(`
                <div class="cell small-6 medium-3">
                    <a href="/market/item/${this.id}" title="${this.name}">
                        <img class="profile-inventory-item-thumbnail" src="${this.thumbnail_url}">
                    </a>
                    <a href="/market/item/${this.id}" class="profile-inventory-item-name" title="${this.name}">${this.name}</a>
                    <div class="push-25"></div>
                </div>`);
            });

            if (data.total_pages == 1) {
                $('#inventoryButtons').html('');
            } else {
                var previousDisabled = (data.current_page == 1) ? 'disabled' : '';
                var nextDisabled = (data.current_page == data.total_pages) ? 'disabled' : '';
                var previousPage = data.current_page - 1;
                var nextPage = data.current_page + 1;

                $('#inventoryButtons').html(`
                <div class="cell small-12 text-center">
                    <button onclick="getInventory('${currentCategory}', ${previousPage})" class="button button-red" ${previousDisabled}>Previous</button>
                    <span style="margin-left:5px;margin-right:5px;">${data.current_page} of ${data.total_pages}</span>
                    <button onclick="getInventory('${currentCategory}', ${nextPage})" class="button button-green" ${nextDisabled}>Next</button>
                </div>`);
            }
        }
    }).fail(function() {
        $('#inventory').removeClass('grid-x grid-margin-x').text('Unable to retrieve items. Please refresh.');
    });
}
