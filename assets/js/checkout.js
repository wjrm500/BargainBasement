$(document).ready(function() {
    let localShoppingCart = window.localStorage.basketData;
    $.post(
        '/shop/postDetailedBasketData',
        {
            'localShoppingCart': localShoppingCart
        },
        function(parsedLocalShoppingCart) {
            localShoppingCart = parsedLocalShoppingCart;
            createTableFromJSON(localShoppingCart, $('#local-cart-option-container .shopping-cart'));
            let dbShoppingCartExists = $('#checkout').data('shoppingCartExists');
            if (dbShoppingCartExists) {
                $.get(
                    '/shop/getDetailedBasketData',
                    function(dbShoppingCart) {
                        $('#loading').addClass('d-none');
                        // Also check that dbShoppingCart is not empty - if empty just use local
                        if (!(shoppingCartsEqual(localShoppingCart, dbShoppingCart))) {
                            $('#carts-not-equal').removeClass('d-none');
                            createTableFromJSON(dbShoppingCart, $('#db-cart-option-container .shopping-cart'));
                        }
                    }
                );
            }
        }
    ); 
});

function createTableFromJSON(json, container) {
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