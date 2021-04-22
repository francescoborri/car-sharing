$('input[name="targa"]').on('input', () => {
    targaInput = $('input[name="targa"]');
    regex = new RegExp(targaInput.prop('pattern'));

    if (regex.test(targaInput.val())) {
        targaInput.removeClass('is-invalid').addClass('is-valid');

        if ($('#targa-feedback').length) {
            $('#targa-feedback').removeClass('invalid-feedback').addClass('valid-feedback').text('Targa valida!');
        } else {
            $('#targa-container').append('<div id="targa-feedback" class="valid-feedback">Targa valida!</div>');
        }
    } else {
        targaInput.removeClass('is-valid').addClass('is-invalid');

        if ($('#targa-feedback').length) {
            $('#targa-feedback').removeClass('valid-feedback').addClass('invalid-feedback').text('Targa invalida...');
        } else {
            $('#targa-container').append('<div id="targa-feedback" class="invalid-feedback">Targa invalida...</div>');
        }
    }
});

$('body').on('input', () => {
    if ($('.is-valid').length == 1 && $('input[name="marca"]').val() != '' && $('input[name="modello"]').val() != '' && $('input[name="costo_giornaliero"]').val() != '')
        $('#submit-btn').prop('disabled', false);
    else
        $('#submit-btn').prop('disabled', true);
});