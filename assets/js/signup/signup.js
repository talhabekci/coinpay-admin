$("document").ready(function () {

    var host_name = "";

    $.ajax({
        method: "POST",
        url: "../src/backend/host-name.php",
        async: false,
        data: {
            data: "host_name"
        },
        dataType: "json",
        success: function (response) {
            host_name = response["ip_address"]
        }
    });

    function IsEmailValid(email) {
        var regex =
            /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        } else {
            return true;
        }
    }

    $(".fa-eye-slash").click(function () {

        if ($("input[name='password']").attr("type") == "password") {
            $("input[name='password']").attr("type", "text");
            $(".fa-eye-slash").attr("class", "fas fa-eye");
        } else {
            $("input[name='password']").attr("type", "password");
            $(".fas.fa-eye").attr("class", "fas fa-eye-slash")
        }

    });

    $(".input.fname").keyup(function () {
        if (!$(".input.fname").val() == "") {
            $(".error-message.fname").css("display", "none");
            $(".input.fname").css("background-color", "");
            $(".input.fname").css("border", "");
        } else {
            $(".error-message.fname").css("display", "block");
            $(".input.fname").css("background-color", "#FAEAED");
            $(".input.fname").css("border", "1px solid #E9A2AE");
            return false;
        }
    });

    $(".input.lname").keyup(function () {
        if (!$(".input.lname").val() == "") {
            $(".error-message.lname").css("display", "none");
            $(".input.lname").css("background-color", "");
            $(".input.lname").css("border", "");
        } else {
            $(".error-message.lname").css("display", "block");
            $(".input.lname").css("background-color", "#FAEAED");
            $(".input.lname").css("border", "1px solid #E9A2AE");
            return false;
        }
    });

    $(".input.email").keyup(function () {

        if (IsEmailValid($(".input.email").val()) == false) {
            $(".error-message.email").css("display", "block");
            $(".error-message.email").text("A valid email is required");
            $(".input.email").css("background-color", "#FAEAED");
            $(".input.email").css("border", "1px solid #E9A2AE");
            return false;
        } else {
            $(".error-message.email").css("display", "none");
            $(".input.email").css("background-color", "");
            $(".input.email").css("border", "");
        }

        if (!$(".input.email").val() == "") {
            $(".error-message.email").css("display", "none");
            $(".input.email").css("background-color", "");
            $(".input.email").css("border", "");
        } else {
            $(".error-message.email").css("display", "block");
            $(".input.email").css("background-color", "#FAEAED");
            $(".input.email").css("border", "1px solid #E9A2AE");
            return false;
        }
    });

    $(".input.password").keyup(function () {
        if ($(".input.password").val() == "" || $(".input.password").val().length < 8) {
            $(".error-message.password").css("display", "block");
            $(".input.password").css("background-color", "#FAEAED");
            $(".input.password").css("border", "1px solid #E9A2AE");
            return false;
        } else {
            $(".error-message.password").css("display", "none");
            $(".input.password").css("background-color", "");
            $(".input.password").css("border", "");
        }
    });

    $(".input.terms").click(function () {
        if ($(".input.terms").prop("checked") == true) {
            $(".error-message.terms-privacy").css("display", "none");
        } else {
            $(".error-message.terms-privacy").css("display", "block");
        }
    });

    $(".submit-signup").click(function (e) {

        if ($(".error-message.signup").css("display") == "flex") {
            $(".error-message.signup").css("display", "none");
        }

        if ($(".input.fname").val() == "") {
            $(".error-message.fname").css("display", "block");
            $(".input.fname").css("background-color", "#FAEAED");
            $(".input.fname").css("border", "1px solid #E9A2AE");
            return false;
        }

        if ($(".input.lname").val() == "") {
            $(".error-message.lname").css("display", "block");
            $(".input.lname").css("background-color", "#FAEAED");
            $(".input.lname").css("border", "1px solid #E9A2AE");
            return false;
        }

        if ($(".input.email").val() == "") {
            $(".error-message.email").css("display", "block");
            $(".input.email").css("background-color", "#FAEAED");
            $(".input.email").css("border", "1px solid #E9A2AE");
            return false;
        }

        if (IsEmailValid($(".input.email").val()) == false) {
            $(".error-message.email").css("display", "block");
            $(".error-message.email").text("A valid email is required");
            $(".input.email").css("background-color", "#FAEAED");
            $(".input.email").css("border", "1px solid #E9A2AE");
            return false;
        }

        if ($(".input.password").val() == "") {
            $(".error-message.password").css("display", "block");
            $(".input.password").css("background-color", "#FAEAED");
            $(".input.password").css("border", "1px solid #E9A2AE");
            return false;
        }

        if ($(".input.terms").prop("checked") == false) {
            $(".error-message.terms-privacy").css("display", "block");
            return false;
        }

        $.ajax({
            type: 'POST',
            url: 'http://' + host_name + '/coinpay-admin/src/backend/register.php',
            data: {
                fname: $(".input.fname").val(),
                lname: $(".input.lname").val(),
                email: $(".input.email").val(),
                password: $(".input.password").val(),
                submit: $(".submit-signup").attr("name")
            },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    $(".error-message.signup").css("display", "flex");
                    $(".error-message.signup > .error-message-text").text(response.error.message);
                } else if (response.result) {
                    $(".success-message.signup").css("display", "flex");
                    $(".success-message.signup > .success-message-text").text(response.result);
                    $(".signup-form").trigger("reset");
                }
            }
        });
        e.preventDefault();
    });

});