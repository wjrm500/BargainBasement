$(document).ready(function() {
    // Check what is in user's cart - any products that are in the cart should have the number of items in the product widget with relevant buttons
    
    var basketData = {1: 1}; // Load basket data asynchronously from DB

    // Loop over items in shopping window, any items in basket need non-zero buttons displayed
    let productWidgets = document.getElementsByClassName('product-widget');
    for (let productWidget of productWidgets) {
        let zeroButtons = productWidget.getElementsByClassName('product-widget-zero')[0];
        let nonZeroButtons = productWidget.getElementsByClassName('product-widget-non-zero')[0];
        if (productWidget.dataset.productId in basketData) {
            zeroButtons.style.display = 'none';
            nonZeroButtons.style.display = 'flex !important';
        }
    }

    

    // When user clicks add button on a product, add that number of products to cart and replace add button with - and +
    // Every time the user modifies their cart (and after a gap of maybe 5 seconds), make a post request to the back end to update their shopping cart in a table
    $('.product-widget .product-widget-add-button').click(function() {
        let productWidget = this.closest('.product-widget');
        handleClick(productWidget);
    });

    function handleClick(productWidget) {
        let productId = productWidget.dataset.productId;
        if (productId in basketData) {
            basketData[productId] ++;
        } else {
            basketData[productId] = 1;
            addBasketWidget(productWidget);
        }
    }

    function addBasketWidget(productWidget) {
        let basketItems = document.getElementById('basket-items');
        let basketItem = document.createElement('div');
        basketItem.innerHTML = getBasketItemHTML(productWidget);
        basketItems.append(basketItem);
        if (overlapBetweenBasketItemsAndFooter()) {
            stickFooterToBasketBottom();
        }
    }

    function getBasketItemHTML(productWidget) {
        let markup = `
            <div class="basket-item" data-product-id="${productWidget.dataset.productId}">
                <div class="basket-item-image-container">
                    <img src="${getImageFromWidget(productWidget)}" class="basket-item-image">
                </div>
                <div class="basket-item-main">
                    <div class="basket-item-name">
                        ${getNameFromWidget(productWidget)}
                    </div>
                    <hr>
                    <div class="basket-item-details">
                        ${getWeightFromWidget(productWidget)} x 1 @ ${getPriceFromWidget(productWidget)} = £1.50
                    </div>
                </div>
                <div class="basket-item-modify">
                    <button class="basket-item-add">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="basket-item-remove">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        `;
        return markup.trim();
    }

    function getImageFromWidget(productWidget) {
        return productWidget.getElementsByClassName('product-widget-image')[0].src;
    }

    function getNameFromWidget(productWidget) {
        return productWidget.getElementsByClassName('product-widget-name')[0].textContent.trim();
    }

    function getPriceFromWidget(productWidget) {
        return productWidget.getElementsByClassName('product-widget-price')[0].textContent.trim();
    }

    function getWeightFromWidget(productWidget) {
        return productWidget.getElementsByClassName('product-widget-weight')[0].textContent.trim();
    }

    function overlapBetweenBasketItemsAndFooter() {
        let lastBasketItem = document.getElementById('basket-items').lastElementChild;
        let itemsBottom = lastBasketItem.getBoundingClientRect().bottom;
        let basketFooter = document.getElementById('basket-footer');
        let footerTop = basketFooter.getBoundingClientRect().top;
        if (footerTop - itemsBottom < 50) {
            return true;
        }
    }

    function stickFooterToBasketBottom() {
        let basketFooter = document.getElementById('basket-footer');
        basketFooter.style.position = 'sticky';
    }
})