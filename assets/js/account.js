$(document).ready(function() {
    $('#orders-table tr').click(function() {
        let orderProducts = $(this).data('products');
        if (Object.entries(orderProducts).length) {
            createTableFromJSON(orderProducts, document.getElementById('order-products'));
        }
    });
});

function createTableFromJSON(json, container) {
    let jsonArr = convertObjectToArray(json);
    let col = [];
    for (let i = 0; i < jsonArr.length; i++) {
        for (let key in jsonArr[i]) {
            if (col.indexOf(key) === -1) {
                col.push(key);
            }
        }
    }
    let table = document.createElement("table");
    ['table', 'table-striped', 'text-center', 'mb-0'].forEach((x) => table.classList.add(x));
    table.style.tableLayout = 'fixed';
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
            tabCell.innerHTML = jsonArr[i][col[j]].toString().replace(/_/g, ' ');
        }
    }
    container.innerHTML = '';
    container.append(table);
}