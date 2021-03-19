$(document).ready(function() {
    $('select').select2();
    var value = $('select').data('value');
    var data;
    if (typeof value === 'number') {
        data = value;
    } else {
        data = $('select').data('value').split(', ');
    }
    $('select').val(data).trigger('change');
});