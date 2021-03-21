$(document).ready(function() {
    let localShoppingCart = [];
    if (window.localStorage.basketData) {
        localShoppingCart = JSON.parse(window.localStorage.basketData);
    }
    $('#confirm-checkout').submit(function() {
        delete window.localStorage.basketData;
    });
    $.post(
        '/shop/ajax/basket-data',
        {
            'localShoppingCart': localShoppingCart
        },
        function(parsedLocalShoppingCart) {
            localShoppingCart = parsedLocalShoppingCart;
            let dbShoppingCartExists = $('#checkout').data('shoppingCartExists');
            if (dbShoppingCartExists) {
                $.get(
                    '/shop/ajax/basket-data',
                    function(dbShoppingCart) {
                        $('#loading').addClass('d-none');
                        if (!(shoppingCartsEqual(localShoppingCart, dbShoppingCart))) {
                            $('#carts-not-equal').removeClass('d-none');
                            $('#cart-save-local').click(function() {
                                $('#carts-not-equal').remove();
                                $('#cart-okay').removeClass('d-none');
                                replaceDbBasketWithLocalBasket();
                                createTableFromJSON(localShoppingCart, $('#shopping-cart'));
                            });
                            $('#cart-save-db').click(function() {
                                $('#carts-not-equal').remove();
                                $('#cart-okay').removeClass('d-none');
                                delete window.localStorage.basketData;
                                createTableFromJSON(dbShoppingCart, $('#shopping-cart'));
                            });
                            createTableFromJSON(localShoppingCart, $('#not-exists-local-shopping-cart'));
                            createTableFromJSON(dbShoppingCart, $('#not-exists-db-shopping-cart'));
                        } else {
                            $('#loading').addClass('d-none');
                            $('#cart-okay').removeClass('d-none');
                            createTableFromJSON(localShoppingCart, $('#shopping-cart'));
                        }
                    }
                );
            } else {
                // Create new shopping cart for user from local storage - post request
                $.post(
                    '/shop',
                    {
                        'basketData':  JSON.parse(window.localStorage.basketData)
                    }
                );
                $('#loading').addClass('d-none');
                $('#cart-okay').removeClass('d-none');
                createTableFromJSON(localShoppingCart, $('#shopping-cart'));
            }
        }
    );
});

function replaceDbBasketWithLocalBasket() {
    var url = window.location.href.split('/');
    url.pop();
    $.post(
        url.join('/') + '/ajax/persist-basket',
        {
            'basketData': JSON.parse(window.localStorage.basketData)
        }
    );
}

function createTableFromJSON(json, container) {
    try {
        json = JSON.parse(json);
    } catch {
        return false;
    }
    let jsonArr = convertObjectToArray(json);
    let col = [];
    for (let i = 0; i < jsonArr.length; i++) {
        for (let key in jsonArr[i]) {
            if (key === 'image') continue;
            if (col.indexOf(key) === -1) {
                col.push(key);
            }
        }
    }
    let table = document.createElement("table");
    ['table', 'shopping-cart-table'].forEach((x) => table.classList.add(x));
    let tr = table.insertRow(-1);
    for (let i = 0; i < col.length; i++) {
        let th = document.createElement("th");
        th.innerHTML = camelCaseToWords(col[i]);
        tr.appendChild(th);
    }
    for (let i = 0; i < jsonArr.length; i++) {
        tr = table.insertRow(-1);
        for (let j = 0; j < col.length; j++) {
            let tabCell = tr.insertCell(-1);
            tabCell.innerHTML = jsonArr[i][col[j]];
        }
    }
    tr = table.insertRow(-1);
    let overallPriceCell = tr.insertCell(-1);
    overallPriceCell.colSpan = 4;
    overallPriceCell.style.textAlign = 'center';
    let span1 = document.createElement('span');
    span1.innerHTML = 'Final price: ';
    span1.style.fontWeight = 'normal';
    let span2 = document.createElement('span');
    span2.innerHTML = getTotalPrice(json);
    overallPriceCell.append(span1);
    overallPriceCell.append(span2);
    container.append(table);
}

function shoppingCartsEqual(a, b) {
    try {
        [a, b] = [a, b].map((x) => JSON.parse(x));
    } catch {
        return false;
    }
    if (Object.keys(a).length !== Object.keys(b).length) {
        return false;
    }
    for (let key in a) {
        if (!(key in b)) {
            return false;
        }
        if (a[key].quantity != b[key].quantity) {
            return false;
        }
    }
    return true;
}

// Separate file for this
function camelCaseToWords(string) {
    let result = string.replace(/([A-Z])/g, " $1");
    return result.charAt(0).toUpperCase() + result.slice(1);
}

// Separate file for this
function convertObjectToArray(obj) {
    let arr = [];
    for (let i in obj)
      arr.push(obj[i]);
    return arr;
  }

// Duplicate of function inside shopping.js
function convertCurrencyStringToFloat(currencyString) {
    return Number(currencyString.replace(/[^0-9.-]+/g, ''));
}

// Duplicate of function inside shopping.js
function convertFloatToCurrencyString(float) {
    return `£${float.toFixed(2)}`;
}

function getTotalPrice(basket) {
    return convertFloatToCurrencyString(convertObjectToArray(basket).reduce((carry, x) => carry + convertCurrencyStringToFloat(x.totalPrice), 0));
}