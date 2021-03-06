$(document).ready(function() {
    let dbShoppingCartExists = $('#checkout').data('shoppingCartExists');
    let localShoppingCart = window.localStorage.basketData; 
    if (dbShoppingCartExists) {
        $.get(
            '/shop/getDetailedBasketData',
            function(dbShoppingCart) {
                if (localShoppingCart !== dbShoppingCart) {
                    // loadShoppingCart(, document.getElementById('local-shopping-cart'));
                    loadShoppingCart(dbShoppingCart, document.getElementById('db-shopping-cart'));
                }
            }
        );
    }
});

function loadShoppingCart(shoppingCart, div) {
    widgets = [];
    shoppingCart = JSON.parse(shoppingCart);
    for (let key in shoppingCart) {
        let cartItem = shoppingCart[key];
        let widget = $('<div>');
        widget.addClass('basket-widget');
        widget.html(cartItem.productName + ' - ' +  cartItem.quantity + ' thereof!');
        widget.css({
            'backgroundColor': 'white',
            'border': '2px solid darkgrey',
            'borderRadius': '5px',
            'height': '25px',
            'margin': '15px'
        });
        widgets.push(widget);
    }
    for (let widget of widgets) {
        $(div).append(widget);
    }
}

// background-color: white;
// border: 2px solid $darkgrey;
// border-radius: 5px;
// display: flex;
// height: 75px;
// margin: 15px;