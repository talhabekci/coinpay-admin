$("document").ready(function() {

    var host_name = "";

    $.ajax({
        method: "POST",
        url: "../src/backend/host-name.php",
        async: false,
        data: {
            data: "host_name"
        },
        dataType: "json",
        success: function(response) {
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

    $(".fa-eye-slash").click(function() {
        if ($("input[name='password']").attr("type") == "password") {
            $("input[name='password']").attr("type", "text");
            $(".fa-eye-slash").attr("class", "fas fa-eye");
        } else {
            $("input[name='password']").attr("type", "password");
            $(".fas.fa-eye").attr("class", "fas fa-eye-slash")
        }
    });

    $(".input.email").keyup(function() {

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

    $(".input.password").keyup(function() {
        if (!$(".input.password").val() == "") {
            $(".error-message.password").css("display", "none");
            $(".input.password").css("background-color", "");
            $(".input.password").css("border", "");
        } else {
            $(".error-message.password").css("display", "block");
            $(".input.password").css("background-color", "#FAEAED");
            $(".input.password").css("border", "1px solid #E9A2AE");
            return false;
        }
    });

    $(".submit-login").click(function(e) {

        if ($(".error-message.login").css("display") == "flex") {
            $(".error-message.login").css("display", "none");
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

        $.ajax({
            type: 'POST',
            url: 'http://' + host_name + '/coinpay-admin/src/backend/login.php',
            data: {
                email: $(".input.email").val(),
                password: $(".input.password").val(),
                submit: $(".submit-login").attr("name")
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response["error"]) {
                    $(".error-message.login").css("display", "flex");
                    $(".error-message.login > .error-message-text").text(response["error"]);
                } else if (response["result"] == "Login Successfull") {
                    location.href = "http://" + host_name + "/coinpay-admin/overview/"
                }
            }
        });
        e.preventDefault();
    });

});