$("#show-hide-password-a").on('click', (event) => {
    a = $("#show-hide-password-a");
    i = $("#show-hide-password-icon");
    input = $('input[name="password"]');

    event.preventDefault();
    if (input.attr("type") == "text") {
        input.attr('type', 'password');
        i.addClass("fa-eye-slash").removeClass("fa-eye");
    } else if (input.attr("type") == "password") {
        input.attr('type', 'text');
        i.removeClass("fa-eye-slash").addClass("fa-eye");
    }
});


$('input[name="password"]').on('input', () => {
    passwordInput = $('input[name="password"]');
    regex = new RegExp(passwordInput.prop('pattern'));

    if (regex.test(passwordInput.val())) {
        passwordInput.removeClass('is-invalid').addClass('is-valid');

        if ($('#password-feedback').length) {
            $('#password-feedback').removeClass('invalid-feedback').addClass('valid-feedback').text('Password OK!');
        } else {
            $('#password-container').append('<div id="password-feedback" class="password-feedback">Password OK!</div>');
        }
    } else {
        passwordInput.removeClass('is-valid').addClass('is-invalid');

        if ($('#password-feedback').length) {
            $('#password-feedback').removeClass('valid-feedback').addClass('invalid-feedback').text('La password è troppo debole...');
        } else {
            $('#password-container').append('<div id="password-feedback" class="invalid-feedback">La password è troppo debole...</div>');
        }
    }
});

$('input[name="password_confirm"]').on('input', () => {
    passwordConfirmInput = $('input[name="password_confirm"]');

    if (passwordConfirmInput.val() === $('input[name="password"]').val()) {
        passwordConfirmInput.removeClass('is-invalid').addClass('is-valid');

        if ($('#password-confirm-feedback').length) {
            $('#password-confirm-feedback').removeClass('invalid-feedback').addClass('valid-feedback').text('Le password coincidono!');
        } else {
            $('#password-confirm-container').append('<div id="password-confirm-feedback" class="password-feedback">Le password coincidono!</div>');
        }
    } else {
        passwordConfirmInput.removeClass('is-valid').addClass('is-invalid');

        if ($('#password-confirm-feedback').length) {
            $('#password-confirm-feedback').removeClass('valid-feedback').addClass('invalid-feedback').text('La password non coincidono...');
        } else {
            $('#password-confirm-container').append('<div id="password-confirm-feedback" class="invalid-feedback">La password non coincidono...</div>');
        }
    }
});

$('body').on('input', () => {
    if ($('.is-valid').length == 2 && $('input[name="username"]').val() != '')
        $('#submit-btn').prop('disabled', false);
    else
        $('#submit-btn').prop('disabled', true);
});