$('input[name="codice_fiscale"]').on('input', () => {
    codiceFiscaleInput = $('input[name="codice_fiscale"]');
    regex = new RegExp(codiceFiscaleInput.prop('pattern'));

    if (regex.test(codiceFiscaleInput.val())) {
        codiceFiscaleInput.removeClass('is-invalid').addClass('is-valid');

        if ($('#codice-fiscale-feedback').length) {
            $('#codice-fiscale-feedback').removeClass('invalid-feedback').addClass('valid-feedback').text('Codice fiscale valido!');
        } else {
            $('#codice-fiscale-container').append('<div id="codice-fiscale-feedback" class="valid-feedback">Codice fiscale valido</div>');
        }
    } else {
        codiceFiscaleInput.removeClass('is-valid').addClass('is-invalid');

        if ($('#codice-fiscale-feedback').length) {
            $('#codice-fiscale-feedback').removeClass('valid-feedback').addClass('invalid-feedback').text('Codice fiscale invalido...');
        } else {
            $('#codice-fiscale-container').append('<div id="codice-fiscale-feedback" class="invalid-feedback">Codice fiscale invalido</div>');
        }
    }
});

$('input[name="telefono"]').on('input', () => {
    telefonoInput = $('input[name="telefono"]');
    regex = new RegExp(telefonoInput.prop('pattern'));

    if (regex.test(telefonoInput.val())) {
        telefonoInput.removeClass('is-invalid').addClass('is-valid');

        if ($('#telefono-feedback').length) {
            $('#telefono-feedback').removeClass('invalid-feedback').addClass('valid-feedback').text('Numero di telefono valido!');
        } else {
            $('#telefono-container').append('<div id="telefono-feedback" class="valid-feedback">Numero di telefono valido!</div>');
        }
    } else {
        telefonoInput.removeClass('is-valid').addClass('is-invalid');

        if ($('#telefono-feedback').length) {
            $('#telefono-feedback').removeClass('valid-feedback').addClass('invalid-feedback').text('Numero di telefono invalido...');
        } else {
            $('#telefono-container').append('<div id="telefono-feedback" class="invalid-feedback">Numero di telefono invalido...</div>');
        }
    }
});

$('body').on('input', () => {
    if ($('.is-valid').length == 2 && $('input[name="nome"]').val() != '' && $('input[name="cognome"]').val() != '' && $('input[name="indirizzo"]').val() != '')
        $('#submit-btn').prop('disabled', false);
    else
        $('#submit-btn').prop('disabled', true);
});