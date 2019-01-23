;

$(function () {


    function messageSend(name, email, phone, text) {

        $.ajax({
            type: "POST",
            url: "core/email.php",
            dataType: "json",
            data: {AjaxAction: "Message", AjaxName: name, AjaxEmail: email, AjaxPhone: phone, AjaxText: text}
        }).done(function (result) {

            if (result['send'] == true) {

                $('#message-user').fadeOut();
                $('.alert-error').hide();
                $('.alert-success').html("Спасибо. Ваше сообщение отправлено");
                $('.alert-success').delay(400).fadeIn();

            } else {
                $('.alert-error').html("Увы, произошла ошибка. Попробуйте отправить сообщение позже");
                $('.alert-error').fadeIn();
                console.log(result['error-msg']);
            }

        }).fail(function () {
            console.log('Что-то пошло не так. Повторите позже.');
        });
    }


    $('.button-form').on('click', function () {

        $('#message-user').on('submit', function () {

            event.preventDefault();

        });

        if ($("#message-user")[0].checkValidity()) {

            var name, email, phone, text;

            name = $('#user').val().trim();
            email = $('#email').val();
            phone = $('#phone').val();
            text = $('#message').val().trim();

            messageSend(name, email, phone, text);

        } else {
            // Обработка ошибок
            $('.alert-error').html("Пожалуйста, проверьте введенные данные.");
            $('.alert-error').fadeIn();
        }

    });


    $("#phone").mask("+375 (99) 9999999");


    $(window).resize(function () {
        if ($(window).width() <= 768) {
            $('.header-main-menu').hide();
        } else {
            $('.header-main-menu').show();
        }
    });

    $('#mobile-menu').on("click", function () {
        $('.header-main-menu').slideToggle();
        $('.fa-bars').toggleClass('rotate');
    });


});

