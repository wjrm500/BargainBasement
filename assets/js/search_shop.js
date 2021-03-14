$(document).ready(function() {
    $('#search-shop').keyup(function() {
        let input = $(this).val();
        $('.product-widget-container').each(function() {
            let productName = productData[$(this).find('.product-widget').data('productId')].name;
            if (productName.toLowerCase().startsWith(input.toLowerCase().trim())) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        });
    });
});