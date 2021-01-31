$(document).ready(function() {
    // Highlight currently selected page in admin navbar

    let listChildren = $('#admin-nav-list').children();
    for (let listChild of listChildren) {
        let button = $($(listChild).children()[0]);
        if (button.attr('href') === window.location.pathname) {
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
});