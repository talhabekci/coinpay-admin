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

    $(".button-next").click(function () {
        $.ajax({
            type: "POST",
            url: "http://" + host_name + "/coinpay-admin/src/backend/verify-email.php",
            data: $("#verification-form").serialize(),
            dataType: "json",
            success: function (response) {
                console.log(response);

                if (response["error"]) {
                    $(".error-message.verification").css("display", "flex");
                    $(".error-message.verification > .error-message-text").text(response["error"]);
                } else if (response["result"]) {
                    $(".error-message.verification").css("display", "none");
                    $(".success-message.verification").css("display", "flex");
                    $(".success-message.verification > .success-message-text").text(response["result"]);
                }
            }
        });
    });
});