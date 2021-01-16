$(document).ready(
    function() {
        if (window.location.pathname === '/admin') {
            $('html').toggleClass('inversed');
            $('#admin').toggleClass('inversed');
        }
    }
)