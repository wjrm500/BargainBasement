$(document).ready(function() {
    let localShoppingCart = JSON.parse(window.localStorage.basketData);
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
                                createTableFromJSON(localShoppingCart, $('#shopping-cart'));
                            });
                            $('#cart-save-db').click(function() {
                                $('#carts-not-equal').remove();
                                $('#cart-okay').removeClass('d-none');
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

function createTableFromJSON(json, container) {
    // Add overall price on bottom row
    json = JSON.parse(json);
    let jsonArr = [];
    for (let obj in json) {
        jsonArr.push(json[obj]);
    }
    let col = [];
    for (let i = 0; i < jsonArr.length; i++) {
        for (let key in jsonArr[i]) {
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
        th.innerHTML = col[i];
        tr.appendChild(th);
    }
    for (let i = 0; i < jsonArr.length; i++) {
        tr = table.insertRow(-1);
        for (let j = 0; j < col.length; j++) {
            let tabCell = tr.insertCell(-1);
            tabCell.innerHTML = jsonArr[i][col[j]];
        }
    }
    container.append(table);
}

function shoppingCartsEqual(a, b) {
    [a, b] = [a, b].map((x) => JSON.parse(x));
    if (Object.keys(a).length !== Object.keys(b).length) {
        return false;
    }
    for (let key in a) {
        if (!(key in b)) {
            return false;
        }
        if (a[key].quantity !== b[key].quantity) {
            return false;
        }
    }
    return true;
}