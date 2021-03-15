$(document).ready(function() {
    // Showing modal on product widget click
    $('.product-widget').click(function(e) {
        // Don't show modal if user is trying to add items to or remove items from basket
        if (e.target.tagName === 'I' || e.target.classList.contains('product-widget-button')) {
            return;
        }
        let modal = document.createElement('div');
        modal.innerHTML = getProductModalHtml(productData[$(this).data('productId')]);
        $('body').append($(modal));
        $('#modal-box').hide();
        $('#modal-box').fadeIn();
        $('#modal-close i').hover(
            function() {
                $(this).removeClass('far');
                $(this).addClass('fas');
            },
            function() {
                $(this).removeClass('fas');
                $(this).addClass('far');
            }
        );
        $('#modal-close i').mousedown(function(e) {
            if (e.which === 1) { // Left click only
                $.when(
                    $(this).closest('#modal').fadeOut()
                ).then(
                    function() {
                        $(this).closest('#modal').remove();
                    }
                );
            }
        });
        $('#modal').mousedown(function(e) {
            if (e.target.id !== 'modal-box' &&
                !$(e.target).parents('#modal-box').length) {
                if (e.which === 1) {
                    $.when(
                        $(this).fadeOut()
                    ).then(
                        function() {
                            $(this).remove();
                        }
                    );
                }
            }
        });
        let marginModalTop = -$('#modal-box').height() / 2;
        $('#modal-box').css('margin-top', marginModalTop);
        colourNutritionLabels();
    });
});

function getProductModalHtml(data) {
    let energyPct = data.nutrition.energy / 2000 * 100;
    let fatPct = data.nutrition.fat / 70 * 100;
    let saturatesPct = data.nutrition.saturates / 20 * 100;
    let sugarsPct = data.nutrition.sugars / 90 * 100;
    let saltPct = data.nutrition.salt / 6 * 100;
    let markup = productModalTemplate.interpolate({
        name: data.name.replace(/_/g, ' '),
        price: data.price,
        weight: data.weight,
        pricePerKg: multiplyCurrencyString(data.price, 1 / (data.weight / 1000)),
        description: data.description.replace(/_/g, ' '),
        ingredients: data.ingredients,
        energy: data.nutrition.energy,
        energyPct: Number(energyPct).toFixed(1),
        fat: Number(data.nutrition.fat).toFixed(1),
        fatPct: Number(fatPct).toFixed(1),
        saturates: Number(data.nutrition.saturates).toFixed(1),
        saturatesPct: Number(saturatesPct).toFixed(1),
        sugars: Number(data.nutrition.sugars).toFixed(1),
        sugarsPct: Number(sugarsPct).toFixed(1),
        salt: Number(data.nutrition.salt).toFixed(1),
        saltPct: Number(saltPct).toFixed(1)
    });
    return markup.trim();
}