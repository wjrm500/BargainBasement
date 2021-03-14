var productData;
$(document).ready(function() {
    // Check what is in user's cart - any products that are in the cart should have the number of items in the product widget with relevant buttons
    
    productData = $('#products').data('productData');
    var basketData = {}; // Load basket data asynchronously from DB
    var sendBasketData;
    var basketMaximised = false;

    const TYPE_ADD = 'add';
    const TYPE_REMOVE = 'remove';

    $(window).resize(reformatProductGrid);
    
    String.prototype.interpolate = function(params) {
        const names = Object.keys(params);
        const vals = Object.values(params);
        return new Function(...names, `return \`${this}\`;`)(...vals);
    }
    
    var template;
    
    $.ajax({
        url: '/shop/ajax/get-basket-widget-html',
        type: 'get',
        async: false,
        success: function(html) {
            template = html;
            $('#products-loading').addClass('d-none');
            $('#products-grid').removeClass('d-none');
            reformatProductGrid();
        }
    });

    // Disable checkout button whilst page loads

    disableCheckout();
    
    // Get existing basket data for user and modify page accordingly
    $.get(
        window.location.href + '/ajax/basket-data',
        function(data) {
            // If the user has no basket data stored in the database, use locally stored basket data
            basketData = JSON.parse(data);
            if (basketData.length === 0 && window.localStorage.getItem('basketData')) {
                basketData = JSON.parse(window.localStorage.basketData);
            }
            if (Array.isArray(basketData)) {
                basketData = convertArrayToObject(basketData);
            }
            window.localStorage.setItem('basketData', JSON.stringify(basketData));
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

    $('#basket-minimise').click(minimiseBasket);
    $('#basket-maximise').click(maximiseBasket);

    function minimiseBasket() {
        if (!basketMaximised) {
            return;
        }
        $('#shop-small-col').animate({width: '5%'});
        $('#basket-header, #basket-items, #basket-footer').toggle();
        $('#basket-footer').toggleClass('d-flex');
        $('#basket').css({'overflow-x': 'hidden', 'overflow-y': 'hidden'});
        $('#shop-large-col').animate({width: '95%'}).promise().done(() => reformatProductGrid());
        $('#basket-maximise').fadeToggle(400);
        basketMaximised = false;
    }
    
    function maximiseBasket() {
        if (basketMaximised) {
            return;
        }
        $('#shop-small-col').animate({width: '25%'});
        $('#basket-header, #basket-items, #basket-footer').toggle();
        $('#basket-footer').toggleClass('d-flex');
        $('#basket').css({'overflow-x': 'scroll', 'overflow-y': 'scroll'});
        $('#basket-maximise').toggle();
        $('#shop-large-col').animate({width: '75%'}).promise().done(() => reformatProductGrid());;
        stickFooterToBasketBottom();
        basketMaximised = true;
    }

    function convertArrayToObject(arr) {
        let obj = {};
        for (let i = 0; i < arr.length; ++i)
          obj[i] = arr[i];
        return obj;
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
            basketData[productId].totalPrice = multiplyCurrencyString(basketData[productId].price, basketData[productId].quantity);
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
        basketWidget.getElementsByClassName('basket-item-total-price')[0].innerHTML = multiplyCurrencyString(basketData[productId].price, basketData[productId].quantity);
    }

    function convertCurrencyStringToFloat(currencyString) {
        return Number(currencyString.replace(/[^0-9.-]+/g, ''));
    }

    function convertFloatToCurrencyString(float) {
        return `£${float.toFixed(2)}`;
    }

    function multiplyCurrencyString(currencyString, multiplier) {
        let float = convertCurrencyStringToFloat(currencyString);
        result = float * multiplier;
        return convertFloatToCurrencyString(result);
    }

    function modifyProductWidgetItemNumber(productId) {
        let productWidget = getProductWidgetByProductId(productId);
        let itemNumberElem = productWidget.getElementsByClassName('product-widget-item-number')[0];
        let newItemNumber = basketData.hasOwnProperty(productId) ? basketData[productId].quantity : 0;
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
        if ($('#basket-items').find('.basket-widget').length === 0) {
            maximiseBasket();
        }
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
        stickFooterToBasketBottom();
    }

    function removeBasketWidget(productId) {
        if ($('#basket-items').find('.basket-widget').length === 1) {
            minimiseBasket();
        }
        getBasketWidgetByProductId(productId).remove();
    }

    function getBasketItemHTML(data) {
        let markup = template.interpolate({
            image: data.image,
            name: data.name.replace('-', ' '),
            price: data.price,
            totalPrice: data.totalPrice,
        });
        return markup.trim().slice(1, -1);
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
        $('#basket-footer').css({'bottom': '0px', 'position': 'absolute'});
        setTimeout(
            function () {
                if (overlapBetweenBasketItemsAndFooter()) {
                    $('#basket-footer').css('position', 'sticky');
                }
                
            }, 400
        );
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
        $(checkoutButton).click(function() {
            $(this).html('<img src="images/spinner-unscreen.gif" height="100%">');
        });
    }
})

function reformatProductGrid() {
    let gridWidth = $('#products-grid').width();
    let productWidgetContainers = $('.product-widget-container')
    if (gridWidth > 1000) {
        productWidgetContainers.addClass('col-2');
        productWidgetContainers.removeClass('col-3 col-4 col-6 col-12');
    } else if (gridWidth > 800) {
        productWidgetContainers.addClass('col-3');
        productWidgetContainers.removeClass('col-2 col-4 col-6 col-12');
    } else if (gridWidth > 600) {
        productWidgetContainers.addClass('col-4');
        productWidgetContainers.removeClass('col-2 col-3 col-6 col-12');
    } else if (gridWidth > 400) {
        productWidgetContainers.addClass('col-6');
        productWidgetContainers.removeClass('col-2 col-3 col-4 col-12');
    } else {
        productWidgetContainers.addClass('col-12');
        productWidgetContainers.removeClass('col-2 col-3 col-4 col-6');
    }
}