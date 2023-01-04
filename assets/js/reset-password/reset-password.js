$(document).ready(function () {

    var host_name = "";

    $.ajax({
        method: "POST",
        url: "../src/backend/host-name.php",
        async: false,
        data: {
            data: "host_name",
        },
        dataType: "json",
        success: function (response) {
            host_name = response["ip_address"];
        },
    });

    $(".fa-eye-slash").click(function () {
        if ($("input[name='new-password'], input[name='retype-password']").attr("type") == "password") {
            $("input[name='new-password'], input[name='retype-password']").attr("type", "text");
            $(".fa-eye-slash").attr("class", "fas fa-eye");
        } else {
            $("input[name='new-password'], input[name='retype-password']").attr("type", "password");
            $(".fas.fa-eye").attr("class", "fas fa-eye-slash")
        }
    });

    $("input[name='verification-code']").keyup(function () {
        if ($("input[name='verification-code']").val().length > 6) {
            $(".error-message.verification").css("display", "flex");
            $(".error-message.verification > .error-message-text").text("Verification code cannot be longer then 6 digits.");
            $("input[name='verification-code']").css("background-color", "#FAEAED");
            $("input[name='verification-code']").css("border", "1px solid #CF304A");
            return false;
        } else {
            $(".error-message.verification").css("display", "none");
            $("input[name='new-password']").css("background-color", "");
            $("input[name='new-password']").css("border", "");
        }
    });

    $("input[name='new-password']").keyup(function () {
        if ($("input[name='new-password']").val() == "" || $("input[name='new-password']").val().length < 8) {
            $(".error-message.verification").css("display", "flex");
            $(".error-message.verification > .error-message-text").text("Your password must be longer than 8 character.");
            $("input[name='new-password']").css("background-color", "#FAEAED");
            $("input[name='new-password']").css("border", "1px solid #CF304A");
            return false;
        } else {
            $(".error-message.verification").css("display", "none");
            $("input[name='new-password']").css("background-color", "");
            $("input[name='new-password']").css("border", "");
        }
    });

    $(".button-next").click(function () {
        $.ajax({
            type: "POST",
            url: "http://" + host_name + "/coinpay-admin/src/backend/reset-password.php",
            data: $("#reset-password-form").serialize(),
            dataType: "json",
            success: function (response) {
                console.log(response);

                if (response["error"]) {
                    $(".error-message.verification").css("display", "flex");
                    $(".error-message.verification > .error-message-text").text(response["error"]);
                } else if (response["result"]) {
                    $(".error-message.verification").css("display", "none");
                    $(".success-message.verification").css("display", "flex");
                    $(".success-message.verification > .success-message-text").text(response["result"] + " Please wait redirecting...");
                    setTimeout(function () {
                        window.location.href = "http://" + host_name + "/coinpay-admin/login";
                    }, 3000);
                }
            }
        });
    });
});