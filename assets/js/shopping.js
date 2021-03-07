$(document).ready(function() {
    // Check what is in user's cart - any products that are in the cart should have the number of items in the product widget with relevant buttons
    
    var basketData = {}; // Load basket data asynchronously from DB
    var sendBasketData;

    const TYPE_ADD = 'add';
    const TYPE_REMOVE = 'remove';

    const TYPE_PRICE = 'price';
    const TYPE_TOTAL_PRICE = 'total-price';

    // Disable checkout button whilst page loads
    disableCheckout();

    // Get existing basket data for user and modify page accordingly
    $.get(
        window.location.href + '/ajax/basket-data',
        function(data) {
            debugger;
            // If the user has no basket data stored in the database, use locally stored basket data
            basketData = JSON.parse(data);
            if (data.length === 0) {
                basketData = JSON.parse(window.localStorage.basketData) ?? {};
            }
            for (let productId in basketData) {
                toggleNonZeroButtons(productId);
                modifyProductWidgetItemNumber(productId);
                addBasketWidget(productId);
                modifyBasketWidgetItemNumber(productId);
            }
            updateTotalPrice();
            enableCheckout();
        }
    );

    // When user clicks add button on a product, add that number of products to cart and replace add button with - and +
    // Every time the user modifies their cart (and after a gap of maybe 5 seconds), make a post request to the back end to update their shopping cart in a table
    $('.product-widget .product-widget-add-button').click(function() {
        let productWidget = this.closest('.product-widget');
        handleWidgetClick(productWidget, TYPE_ADD);
    });

    $('.product-widget .product-widget-non-zero-remove-button').click(function() {
        let productWidget = this.closest('.product-widget');
        handleWidgetClick(productWidget, TYPE_REMOVE);
    });

    function toggleNonZeroButtons(productId) {
        if (productId in basketData && basketData[productId] > 0) {
            showNonZeroButtons(productId);
            return;
        }
        hideNonZeroButtons(productId);
    }

    function hideNonZeroButtons(productId) {
        productWidget = getProductWidgetByProductId(productId);
        let zeroButtons = productWidget.getElementsByClassName('product-widget-zero')[0];
        let nonZeroButtons = productWidget.getElementsByClassName('product-widget-non-zero')[0];
        zeroButtons.style.display = 'block';
        nonZeroButtons.style.display = 'none';
    }

    function showNonZeroButtons(productId) {
        productWidget = getProductWidgetByProductId(productId);
        let zeroButtons = productWidget.getElementsByClassName('product-widget-zero')[0];
        let nonZeroButtons = productWidget.getElementsByClassName('product-widget-non-zero')[0];
        zeroButtons.style.display = 'none';
        nonZeroButtons.style.setProperty('display', 'flex', 'important');
    }

    function handleWidgetClick(widget, type) {
        disableCheckout();
        clearTimeout(sendBasketData);
        let productId = widget.dataset.productId;
        if (productId in basketData) {
            basketData[productId] += (type === TYPE_ADD ? 1 : -1);
            modifyBasketWidgetItemNumber(productId);
            enlargeBasketWidget(productId);
            if (basketData[productId] < 1) {
                delete basketData[productId];
                removeBasketWidget(productId);
            }
        } else {
            basketData[productId] = 1;
            addBasketWidget(productId);
        }
        toggleNonZeroButtons(productId);
        modifyProductWidgetItemNumber(productId);
        updateTotalPrice();
        sendBasketData = setTimeout(
            function() {
                window.localStorage.setItem('basketData', JSON.stringify(basketData));
                $.post(
                    window.location.href + '/ajax/persist-basket',
                    {
                        'basketData': basketData
                    },
                    enableCheckout
                );
            },
            2000
        );
    }

    function enlargeBasketWidget(productId) {
        let basketWidget = getBasketWidgetByProductId(productId);
        basketWidget.classList.add('basket-widget-active');
        setTimeout(
            function() {
                basketWidget.classList.remove('basket-widget-active');
            },
            100
        )
    }

    function modifyBasketWidgetItemNumber(productId) {
        let basketWidget = getBasketWidgetByProductId(productId);
        basketWidget.getElementsByClassName('basket-item-number')[0].innerHTML = basketData[productId];
        let itemPrice = getNumericItemPriceFromBasketWidget(basketWidget, TYPE_PRICE);
        let totalPrice = (itemPrice * basketData[productId]).toFixed(2);
        basketWidget.getElementsByClassName('basket-item-total-price')[0].innerHTML = `£${totalPrice}`;
    }

    function getNumericItemPriceFromBasketWidget(basketWidget, type) {
        if (![TYPE_PRICE, TYPE_TOTAL_PRICE].includes(type)) {
            throw 'Type parameter must be either "price" or "total-price"';
        }
        let itemPrice = basketWidget.getElementsByClassName('basket-item-' + type)[0].innerHTML;
        return Number(itemPrice.replace(/[^0-9.-]+/g, ''));
    }

    function modifyProductWidgetItemNumber(productId) {
        let productWidget = getProductWidgetByProductId(productId);
        let itemNumberElem = productWidget.getElementsByClassName('product-widget-item-number')[0];
        let newItemNumber = basketData[productId] ?? 0;
        itemNumberElem.value = newItemNumber;
    }

    function getBasketWidgetByProductId(productId) {
        let basketWidgets = Array.from(document.getElementsByClassName('basket-widget'));
        return basketWidgets.find((x) => x.dataset.productId === productId);
    }

    function getProductWidgetByProductId(productId) {
        let productWidgets = Array.from(document.getElementsByClassName('product-widget'));
        return productWidgets.find((x) => x.dataset.productId === productId);
    }
    
    function addBasketWidget(productId) {
        let basketItems = document.getElementById('basket-items');
        let basketWidget = document.createElement('div');
        basketWidget.classList.add('basket-widget');
        basketWidget.dataset.productId = productId;
        let productWidget = getProductWidgetByProductId(productId);
        basketWidget.innerHTML = getBasketItemHTML(productWidget);
        let addButton = basketWidget.getElementsByClassName('basket-widget-add-button')[0];
        let removeButton = basketWidget.getElementsByClassName('basket-widget-remove-button')[0];
        addButton.onclick = function() {
            handleWidgetClick(basketWidget, TYPE_ADD);
        };
        removeButton.onclick = function() {
            handleWidgetClick(basketWidget, TYPE_REMOVE);
        };
        basketItems.append(basketWidget);
        if (overlapBetweenBasketItemsAndFooter()) {
            stickFooterToBasketBottom();
        }
    }

    function removeBasketWidget(productId) {
        getBasketWidgetByProductId(productId).remove();
    }

    function getBasketItemHTML(productWidget) {
        let markup = `
            <div class="basket-widget-image-container">
                <img src="${getImageFromWidget(productWidget)}" class="basket-item-image">
            </div>
            <div class="basket-widget-main">
                <div class="basket-item-name">
                    ${getNameFromWidget(productWidget)}
                </div>
                <hr>
                <div class="basket-widget-details">
                    <span class="basket-widget-detail">
                        x
                    </span>
                    <span class="basket-widget-detail basket-item-number">
                        1
                    </span>
                    <span class="basket-widget-detail">
                        @
                    </span>
                    <span class="basket-widget-detail basket-item-price">
                        ${getPriceFromWidget(productWidget)}
                    </span>
                    <span class="basket-widget-detail">
                        =
                    </span>
                    <span class="basket-widget-detail basket-item-total-price">
                        ${getPriceFromWidget(productWidget)}
                    </span>
                </div>
            </div>
            <div class="basket-widget-modify">
                <button class="basket-widget-add-button">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="basket-widget-remove-button">
                    <i class="fas fa-minus"></i>
                </button>
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

    function updateTotalPrice() {
        let basketWidgets = document.getElementsByClassName('basket-widget');
        let totalPrice = 0;
        for (let basketWidget of basketWidgets) {
            totalPrice += getNumericItemPriceFromBasketWidget(basketWidget, TYPE_TOTAL_PRICE);
        }
        document.getElementById('basket-price-value').innerHTML = `£${totalPrice.toFixed(2)}`;
    }

    function disableCheckout() {
        let checkoutButton = document.getElementById('basket-checkout');
        checkoutButton.style.pointerEvents = 'none';
        checkoutButton.style.cursor = 'default';
        checkoutButton.innerHTML = '<img src="images/spinner-unscreen.gif" height="100%">';
    }

    function enableCheckout() {
        let checkoutButton = document.getElementById('basket-checkout');
        checkoutButton.style.pointerEvents = 'auto';
        checkoutButton.style.cursor = 'pointer';
        checkoutButton.innerHTML = 'Checkout';
    }
})