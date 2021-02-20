$(document).ready(function() {
    // Check what is in user's cart - any products that are in the cart should have the number of items in the product widget with relevant buttons
    // When user clicks add button on a product, add that number of products to cart and replace add button with - and +
    // Every time the user modifies their cart (and after a gap of maybe 5 seconds), make a post request to the back end to update their shopping cart in a table
    $('.product-widget button').click(function() {
        let productWidget = this.closest('.product-widget');
        addToCart(productWidget);
    });

    function addToCart(productWidget) {
        let basketItems = document.getElementById('basket-items');
        basketItems.append(
            getBasketItem(productWidget);
        );
    }

    function getBasketItem(productWidget) {
        return "
            <div>
        ";
    }

    function getProductImageFromProductWidget(productWidget) {
        return productWidget.getElementsByClassName('product-widget-image').src;
    }

    function getProductNameFromProductWidget(productWidget) {
        return productWidget.getElementsByClassName('product-widget-name').textContent.trim();
    }

    function getProductPriceFromProductWidget(productWidget) {
        return productWidget.getElementsByClassName('product-widget-price').textContent.trim();
    }

    function getProductWeightFromProductWidget(productWidget) {
        return productWidget.getElementsByClassName('product-widget-weight').textContent.trim();
    }
})