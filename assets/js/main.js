$(document).ready(
    function() {
        // Add glowing border to current page in secondary navbar
        let beforeSlash = window.location.pathname.split('/')[1];
        let navItem = document.getElementById('nav-item-' + beforeSlash);
        $(navItem).css({
            'background-color': 'rgb(0, 0, 0, 0.2)',
            'border': '2px solid black',
            'border-radius': '5px'
        });
        // navItem.style.border = '2px solid';
        // navItem.style.borderColor = 'dodgerblue';
        // navItem.style.borderRadius = '5px';
        // navItem.style.boxShadow = "0px 0px 5px 3px dodgerblue";
    }
)

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

function convertArrayToObject(arr) {
    let obj = {};
    for (let i = 0; i < arr.length; ++i)
      obj[i] = arr[i];
    return obj;
}

function convertObjectToArray(obj) {
    let arr = [];
    for (let i in obj)
      arr.push(obj[i]);
    return arr;
}

function camelCaseToWords(string) {
    let result = string.replace(/([A-Z])/g, " $1");
    return result.charAt(0).toUpperCase() + result.slice(1);
}