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

    $('#category-filter').change(function() {
        let categoryId = $(this).val();
        $('.product-widget-container').each(function() {
            if (categoryId === 'all') {
                $(this).removeClass('d-none');
                return;
            }
            let categories = productData[$(this).find('.product-widget').data('productId')].categories;
            for (let category of categories) {
                console.log($(this));
                console.log(category);
                console.log('');
                if (categoryId == category.id) {
                    $(this).removeClass('d-none');
                    return;
                }
            }
            $(this).addClass('d-none');
        });
    });
});