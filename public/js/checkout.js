$(document).ready(function() {
    let dbShoppingCartExists = $('#basket-items').html().trim();
    let localShoppingCart = window.localStorage.basketData; 
    if (dbShoppingCartExists) {
        $.get(
            '/shop/getBasketData',
            function(dbShoppingCart) {
                if (localShoppingCart !== dbShoppingCart) {
                    whichBasketWouldYouLike();
                }
            }
        );
    }
});

function whichBasketWouldYouLike()
{
    $('#basket-items').html(
        '<div>Your local and database baskets are different - which basket would you like to keep?</div>' +
        '<select>' +
        '<option value="local">Local</option>' +
        '<option value="database">Database</option>' +
        '</select>' +
        '</div>'
    );
}