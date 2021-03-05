$(document).ready(function() {
    let dbShoppingCartExists = $('#checkout').data('shoppingCartExists');
    let localShoppingCart = window.localStorage.basketData; 
    if (dbShoppingCartExists) {
        $.get(
            '/shop/getBasketData',
            function(dbShoppingCart) {
                if (localShoppingCart !== dbShoppingCart) {
                    loadShoppingCart(localShoppingCart, document.getElementById('local-shopping-cart'));
                    loadShoppingCart(dbShoppingCart, document.getElementById('db-shopping-cart'));
                }
            }
        );
    }
});

function loadShoppingCart(shoppingCart, div) {
    for (let shoppingCartItem of shoppingCart) {
        debugger;
    }
}

function loadDbShoppingCart() {
}