$(document).ready(
    function() {
        // Add glowing border to current page in secondary navbar
        let beforeSlash = window.location.pathname.split('/')[1];
        let navItem = document.getElementById('nav-item-' + beforeSlash);
        navItem.style.border = '2px solid';
        navItem.style.borderColor = 'dodgerblue';
        navItem.style.borderRadius = '5px';
        navItem.style.boxShadow = "0px 0px 5px 3px dodgerblue";
        
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
    }
)