$('.select2').select2()

$('#tableUnit').DataTable({
    language: __languages.table,
    scrollX: '100%',
    scrollY: '100%'
});

$(document).on('click', '.btn-delete', function() {
    let id = $(this).attr('id');
    let name = $(this).parent().parent().children('td').first().text();

    $('#modalRequestDel').modal();
    $('#nameDel').text(name);
    $('#idDel').val(id.split('-')[1]);
});

$('.btn-import').on('click', function() {
    $('#modalImport').modal();
    $('#importFile').val('');
    $('.input-text').text(__input.file);
    $('.error-file').hide();
    $('.btn-save-file').prop('disabled', false);
    $('#product_id').val('');

});

$('#importFile').on('change', function() {
    check_file = false;
    let val = $(this).val();
    let name = val.split('\\').pop();
    let typeFile = name.split('.').pop().toLowerCase();
    $('.input-text').text(name);
    $('.error-file').hide();

    if (typeFile != 'xlsx' && typeFile != 'xls' && typeFile != 'txt') {
        $('.error-file').show();
        $('.btn-save-file').prop('disabled', true);
    } else {
        $('.btn-save-file').prop('disabled', false);
        check_file = true;
    }
});

$('.btn-save-file').on('click', function() {
    $('.error-file').hide();

    if (check_file) {
        $('.btn-submit-file').click();
    } else {
        $('.error-file').show();
    }
});