$(document).ready(function() {
    $('.admin-header-item').each(function() {
        while ($(this)[0].scrollWidth > $(this).innerWidth()) {
            let size = $(this).css('font-size').replace('px', '');
            $(this).css('font-size', size - 1 + 'px');
        }
    });

    // Highlight currently selected page in admin navbar

    let listChildren = $('#admin-nav-list').children();
    for (let listChild of listChildren) {
        let button = $($(listChild).children()[0]);
        if (button.attr('href') === '/' + window.location.pathname.split('/').slice(1, 3).join('/')) {
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

    $('#search-admin-product-input').keyup(function(e) {
        if (e.which === 13) {
            $('#search-admin-product-btn').click();
        }
    });

    $('#search-admin-product-btn').click(function() {
        let btn = $(this);
        let input = $('#search-admin-product-input').val();
        let btnHtml = btn.html();
        btn.html('<img src="/images/spinner-cropped.gif" height="25px">');
        $.post(
            window.location.href + '/search',
            {
                search_term: input
            },
            function(data) {
                let html = JSON.parse(data);
                $('#admin-table-pages').remove();
                document.getElementById('admin-table-header').insertAdjacentHTML('afterend', html);
                btn.html(btnHtml)
            }
        )
    });
});