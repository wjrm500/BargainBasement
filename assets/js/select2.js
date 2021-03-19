$(document).ready(function() {
    $('select').each(function() {
        $(this).select2();
        var value = $(this).data('value');
        var data;
        if (typeof value === 'number') {
            data = value;
        } else {
            data = $(this).data('value').split(', ');
        }
        $(this).val(data).trigger('change');
    });
});