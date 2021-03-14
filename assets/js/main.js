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