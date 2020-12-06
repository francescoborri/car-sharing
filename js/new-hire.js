$('body').on('input', () => {
    if ($('input[name="data_inizio"]').val() != "" && $('input[name="data_fine"]').val() != "") {
        let start = new Date($('input[name="data_inizio"]').val());
        let end = new Date($('input[name="data_fine"]').val())

        if (start > end || $('input[name="targa"]').val() == "" || $('input[name="codice_fiscale"]').val() == "") {
            $('input[name="data_inizio"]').addClass('is-invalid');
            $('input[name="data_fine"]').addClass('is-invalid');
            $('#submit-btn').prop('disabled', true);
        } else {
            $('input[name="data_inizio"]').removeClass('is-invalid');
            $('input[name="data_fine"]').removeClass('is-invalid');
            $('#submit-btn').prop('disabled', false);
        }
    }
});