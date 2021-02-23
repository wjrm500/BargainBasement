$(document).ready(function() {
    // Highlight currently selected page in admin navbar

    let listChildren = $('#admin-nav-list').children();
    for (let listChild of listChildren) {
        let button = $($(listChild).children()[0]);
        if (button.attr('href') === window.location.pathname) {
            applyCurrentlySelectedGlow(button);
        }
    }

    function applyCurrentlySelectedGlow(button) {
        button.css({
            'color': 'white'
        });
        let buttonParent = $(button.parent());
        buttonParent.css({
            'background': 'linear-gradient(90deg, rgb(0,150,0), rgb(150,225,150), white)',
        })
    }
});
$(document).ready(function() {
    let firstPageButton = document.querySelector('button[data-page-num="0"]');
    highlightButton(firstPageButton);

    $('#pagination-direct').keypress(function(e) {
        if (e.which === 13) {
            let inputValue = $('#pagination-direct').val();
            if (isNaN(inputValue)) {
                return alert('Please enter a number');
            }
            goToPage(inputValue - 1);
        }
    });

    $('#pagination-back-all').click(function() {
        goToPage(0);
    });
    
    $('#pagination-back-one').click(function() {
        let currentPageNum = getCurrentPageNum();
        if (currentPageNum !== 0) {
            goToPage(currentPageNum - 1);
        }
    });

    $('#pagination-forward-one').click(function() {
        let currentPageNum = getCurrentPageNum();
        if (currentPageNum !== getMaxPageNum()) {
            goToPage(currentPageNum + 1);
        }
    });
    
    $('#pagination-forward-all').click(function() {
        let maxPageNum = getMaxPageNum();
        goToPage(maxPageNum);
    });

    $('#admin-table-page-buttons').children().each(function() {
        $(this).click(function() {
            let pageNum = $(this).data('page-num');
            goToPage(pageNum);
        });
    });
});

function goToPage(pageNum) {
    let button = getButtonByPageNum(pageNum);
    highlightButton(button);
    let table = getTableByPageNum(pageNum);
    displayTable(table);
}


function highlightButton(button) {
    if (!(button instanceof jQuery)) {
        button = $(button);
    }
    button.attr('data-selected', 'true');
    button.siblings().each(function() {
        $(this).attr('data-selected', 'false')
    });
    button.removeClass('btn-light');
    button.addClass('btn-success');
    button.siblings().each(function() {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-light');
    });
}

function displayTable(table) {
    if (!(table instanceof jQuery)) {
        table = $(table);
    }
    table.attr('data-selected', 'true');
    table.siblings().each(function() {
        $(this).attr('data-selected', 'false')
    });
    table.removeClass('d-none');
    table.siblings().addClass('d-none');
}


function getTableByPageNum(pageNum) {
    let tables = document.querySelectorAll('.admin-table-page');
    for (let table of tables) {
        if (table.dataset.pageNum == pageNum) {
            return table;
        }
    }
}

function getButtonByPageNum(pageNum) {
    let buttons = document.querySelectorAll('.admin-table-page-button');
    for (let button of buttons) {
        if (button.dataset.pageNum == pageNum) {
            return button;
        }
    }
}

function getMaxPageNum() {
    let buttons = document.querySelectorAll('.admin-table-page-button');
    let lastButton = buttons[buttons.length - 1];
    return parseInt(lastButton.dataset.pageNum);
}

function getCurrentPageNum() {
    let buttons = document.querySelectorAll('.admin-table-page-button');
    for (let button of buttons) {
        if (button.dataset.selected == 'true') {
            return parseInt(button.dataset.pageNum);
        }
    }
}
$(document).ready(
    function() {
        // Add glowing border to current page in secondary navbar
        let beforeSlash = window.location.pathname.split('/')[1];
        let navItem = document.getElementById('nav-item-' + beforeSlash);
        navItem.style.border = '2px solid';
        navItem.style.borderColor = 'dodgerblue';
        navItem.style.borderRadius = '5px';
        navItem.style.boxShadow = "0px 0px 5px 3px dodgerblue";
    }
)


$(document).ready(function() {
    // Check what is in user's cart - any products that are in the cart should have the number of items in the product widget with relevant buttons
    
    var basketData = {}; // Load basket data asynchronously from DB
    var sendBasketData;

    const TYPE_ADD = 'add';
    const TYPE_REMOVE = 'remove';

    const TYPE_PRICE = 'price';
    const TYPE_TOTAL_PRICE = 'total-price';

    // Get existing basket data for user and modify page accordingly
    $.get(
        window.location.href + '/getBasketData',
        function(data) {
            basketData = JSON.parse(data);
            for (let productId in basketData) {
                toggleNonZeroButtons(productId);
                modifyProductWidgetItemNumber(productId);
                addBasketWidget(productId);
                modifyBasketWidgetItemNumber(productId);
                updateTotalPrice();
                addCheckoutHandler();
            }
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
                $.post(
                    window.location.href,
                    basketData
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
        let basketWidgets = document.getElementsByClassName('basket-widget');
        // Use Array.find()
        for (let basketWidget of basketWidgets) {
            if (basketWidget.dataset.productId === productId) {
                return basketWidget;
            }
        }
    }

    function getProductWidgetByProductId(productId) {
        let productWidgets = document.getElementsByClassName('product-widget');
        // Use Array.find()
        for (let productWidget of productWidgets) {
            if (productWidget.dataset.productId === productId) {
                return productWidget;
            }
        }
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

    function addCheckoutHandler() {
        let checkoutButton = document.getElementById('basket-checkout');
        $(checkoutButton).click(function() {
            debugger;
            let hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'basket-data');
            hiddenInput.setAttribute('value', JSON.stringify(basketData));
            document.getElementById('basket-checkout-form').append(hiddenInput);
            return true;
        });
    }
})