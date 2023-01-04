$(document).ready(function () {

    var host_name = "";

    $.ajax({
        method: "POST",
        url: "src/backend/host-name.php",
        async: false,
        data: {
            data: "host_name",
        },
        dataType: "json",
        success: function (response) {
            host_name = response["ip_address"];
        },
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

    $("input[name='email']").keyup(function () {

        if (IsEmailValid($("input[name='email']").val()) == false) {
            $(".error-message.forgot-password").css("display", "flex");
            $(".error-message.forgot-password > .error-message-text").text("A valid email is required");
            $("input[name='email']").css("background-color", "#FAEAED");
            $("input[name='email']").css("border", "1px solid #CF304A");
            return false;
        } else {
            $(".error-message.forgot-password").css("display", "none");
            $("input[name='email']").css("background-color", "");
            $("input[name='email']").css("border", "");
        }

        if (!$("input[name='email']").val() == "") {
            $(".error-message.forgot-password").css("display", "none");
            $("input[name='email']").css("background-color", "");
            $("input[name='email']").css("border", "");
        } else {
            $(".error-message.forgot-password").css("display", "flex");
            $(".error-message.forgot-password >.error-message-text").text("Email is required");
            $("input[name='email']").css("background-color", "#FAEAED");
            $("input[name='email']").css("border", "1px solid #CF304A");
            return false;
        }
    });

    $(".button-next").click(function () {

        if (!$("input[name='email']").val() == "") {
            $(".error-message.forgot-password").css("display", "none");
            $("input[name='email']").css("background-color", "");
            $("input[name='email']").css("border", "");
        } else {
            $(".error-message.forgot-password").css("display", "flex");
            $(".error-message.forgot-password >.error-message-text").text("Email is required");
            $("input[name='email']").css("background-color", "#FAEAED");
            $("input[name='email']").css("border", "1px solid #CF304A");
            return false;
        }

        $.ajax({
            type: "POST",
            url: "http://" + host_name + "/coinpay-admin/src/backend/forgot-password.php",
            data: $("#forgot-password-form").serialize(),
            dataType: "json",
            success: function (response) {
                if (response["error"]) {
                    $(".error-message.forgot-password").css("display", "flex");
                    $(".error-message.forgot-password > .error-message-text").text(response["error"]);
                } else if (response["result"]) {
                    $(".error-message.forgot-password").css("display", "none");
                    $(".success-message.forgot-password").css("display", "flex");
                    $(".success-message.forgot-password > .success-message-text").text(response["result"]);
                }
            }
        });
    });
});