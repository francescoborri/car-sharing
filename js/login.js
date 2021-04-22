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