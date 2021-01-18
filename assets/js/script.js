$(document).ready(
    function() {
        if (window.location.pathname.split('/')[1] === 'admin') {
            $('html').toggleClass('inversed');
            $('#admin').toggleClass('inversed');
            $('#secondary-navbar').css('top', '0px');
        }
    }
)