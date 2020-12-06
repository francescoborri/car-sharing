$('body').on('input', () => {
    if ($('input[name="start"]').val() != "" && $('input[name="end"]').val() != "") {
        let start = new Date($('input[name="start"]').val());
        let end = new Date($('input[name="end"]').val())

        if (start > end) {
            $('input[name="start"]').addClass('is-invalid');
            $('input[name="end"]').addClass('is-invalid');
            $('#submit-btn').prop('disabled', true);
        } else {
            $('input[name="start"]').removeClass('is-invalid');
            $('input[name="end"]').removeClass('is-invalid');
            $('#submit-btn').prop('disabled', false);
        }
    }
});