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