$(document).ready(function() {
    // Check what is in user's cart - any products that are in the cart should have the number of items in the product widget with relevant buttons
    
    var productData = $('#products').data('productData');
    var basketData = {}; // Load basket data asynchronously from DB
    var sendBasketData;

    const TYPE_ADD = 'add';
    const TYPE_REMOVE = 'remove';

    // Disable checkout button whilst page loads
    disableCheckout();

    // Get existing basket data for user and modify page accordingly
    $.get(
        window.location.href + '/ajax/basket-data',
        function(data) {
            debugger
            // If the user has no basket data stored in the database, use locally stored basket data
            basketData = JSON.parse(data);
            if (basketData.length === 0 && window.localStorage.getItem('basketData')) {
                basketData = JSON.parse(window.localStorage.basketData);
            }
            // basketData = toObject(basketData);
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

    function toObject(arr) {
        var rv = {};
        for (var i = 0; i < arr.length; ++i)
          rv[i] = arr[i];
        return rv;
      }

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
        if (productId in basketData && basketData[productId].quantity > 0) {
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
            basketData[productId].quantity += (type === TYPE_ADD ? 1 : -1);
            basketData[productId].totalPrice = convertCurrencyToFloat(basketData[productId].price) * basketData[productId].quantity;
            modifyBasketWidgetItemNumber(productId);
            enlargeBasketWidget(productId);
            if (basketData[productId].quantity < 1) {
                delete basketData[productId];
                removeBasketWidget(productId);
            }
        } else {
            basketData[productId] = {
                'image': productData[productId].image,
                'name': productData[productId].name,
                'price': productData[productId].price,
                'quantity': 1,
                'totalPrice': productData[productId].totalPrice
            }
            addBasketWidget(productId);
        }
        toggleNonZeroButtons(productId);
        modifyProductWidgetItemNumber(productId);
        updateTotalPrice();
        debugger;
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
        basketWidget.getElementsByClassName('basket-item-number')[0].innerHTML = basketData[productId].quantity;
        let itemPrice = convertCurrencyStringToFloat(basketData[productId].price);
        let totalPrice = (itemPrice * basketData[productId].quantity).toFixed(2);
        basketWidget.getElementsByClassName('basket-item-total-price')[0].innerHTML = `£${totalPrice}`;
    }

    function convertCurrencyStringToFloat(currencyString) {
        return Number(currencyString.replace(/[^0-9.-]+/g, ''));
    }

    function modifyProductWidgetItemNumber(productId) {
        debugger;
        let productWidget = getProductWidgetByProductId(productId);
        let itemNumberElem = productWidget.getElementsByClassName('product-widget-item-number')[0];
        let newItemNumber = basketData[productId].quantity ?? 0;
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
        basketWidget.innerHTML = getBasketItemHTML(basketData[productId]);
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

    function getBasketItemHTML(data) {
        let markup = `
            <div class="basket-widget-image-container">
                <img src="/images/${data.image}" class="basket-item-image">
            </div>
            <div class="basket-widget-main">
                <div class="basket-item-name">
                    ${data.name}
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
                        ${data.price}
                    </span>
                    <span class="basket-widget-detail">
                        =
                    </span>
                    <span class="basket-widget-detail basket-item-total-price">
                        ${data.totalPrice}
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
        let totalPrice = 0;
        for (let productId in basketData) {
            totalPrice += convertCurrencyStringToFloat(basketData[productId].totalPrice);
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