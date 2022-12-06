$(document).ready(function() {

    $("div.sidebar > ul.nav-list > li > a > i.fa-regular.fa-gear").addClass("active");

    function IsEmailValid(email) {
        var regex =
            /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        } else {
            return true;
        }
    }

    $(".input.fname").keyup(function() {

        if (!$(".input.fname").val() == "") {
            $(".error-message.fname").css("display", "none");
            $(".error-message.fname").text("");
            $(".input.fname").css("background-color", "");
            $(".input.fname").css("border", "");
        }

        if (!$(".input.fname").val() == name) {
            $(".error-message.fname").css("display", "none");
            $(".error-message.fname").text("");
            $(".input.fname").css("background-color", "");
            $(".input.fname").css("border", "");
        }

    });

    $(".input.lname").keyup(function() {

        if (!$(".input.lname").val() == "") {
            $(".error-message.lname").css("display", "none");
            $(".error-message.lname").text("");
            $(".input.lname").css("background-color", "");
            $(".input.lname").css("border", "");
        }

        if (!$(".input.lname").val() == surname) {
            $(".error-message.lname").css("display", "none");
            $(".error-message.lname").text("");
            $(".input.lname").css("background-color", "");
            $(".input.lname").css("border", "");
        }

    });

    $(".input.email").keyup(function() {

        if (IsEmailValid($(".input.email").val()) == false) {
            $(".error-message.email").css("display", "block");
            $(".error-message.email").text("Email is not valid");
            $(".input.email").css("background-color", "#FAEAED");
            $(".input.email").css("border", "1px solid #E9A2AE");
            return false;
        } else {
            $(".error-message.email").css("display", "none");
            $(".input.email").css("background-color", "");
            $(".input.email").css("border", "");
        }
    });

    $(".input.password").keyup(function() {

        if (!$(".input.password").val() == "") {
            $(".error-message.password").css("display", "none");
            $(".error-message.password").text("");
            $(".input.password").css("background-color", "");
            $(".input.password").css("border", "");
        }

    });

    var avatar_input = $(".input.avatar");
    var default_avatar = new File(["avatar"], avatar_input.attr("value"), {
        type: 'image/x-icon',
        lastModified: new Date()
    });
    var dataTransfer = new DataTransfer();
    dataTransfer.items.add(default_avatar);
    avatar_input.files = dataTransfer.files;

    $(".settings-form").on('submit', function(e) {

        var avatar = $("input.avatar").attr("value");
        var name = $("input.fname").attr("value");
        var surname = $("input.lname").attr("value");
        var email = $("input.email").attr("value");


        if ($(".input.password").val() == "") {
            $(".error-message.password").css("display", "block");
            $(".error-message.password").text("Please enter your password for save the changes");
            $(".input.password").css("background-color", "#FAEAED");
            $(".input.password").css("border", "1px solid #E9A2AE");
            return false;
        }

        if ($(".input.fname").val() == "") {
            $(".error-message.fname").css("display", "block");
            $(".error-message.fname").text("Display name is required");
            $(".input.fname").css("background-color", "#FAEAED");
            $(".input.fname").css("border", "1px solid #E9A2AE");
            return false;
        }

        if ($(".input.lname").val() == "") {
            $(".error-message.lname").css("display", "block");
            $(".error-message.lname").text("Display surname is required");
            $(".input.lname").css("background-color", "#FAEAED");
            $(".input.lname").css("border", "1px solid #E9A2AE");
            return false;
        }

        if ($(".input.email").val() == "") {
            $(".error-message.email").css("display", "block");
            $(".error-message.email").text("Email is required");
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

        if (name != $(".input.fname").val() || surname != $(".input.lname").val() || email != $(".input.email").val() || avatar != $(".input.avatar").val()) {

            var form_data = new FormData();
            var img = $(".input.avatar")[0].files;

            form_data.append('avatar', img[0]);
            form_data.append('fname', $(".input.fname").val());
            form_data.append('lname', $(".input.lname").val());
            form_data.append('email', $(".input.email").val());
            form_data.append('password', $(".input.password").val());
            form_data.append('submit', $(".submit-signup").attr("name"));

            $.ajax({
                type: 'POST',
                url: 'http://localhost/coinpay-admin/src/backend/profile-update.php',
                data: form_data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    if (response["error"]) {
                        $(".error-message-box").css("display", "flex");
                        $(".error-message-box").text(response["error"]);
                        window.setTimeout(function() {
                            $(".error-message-box").addClass('removed');
                            window.addEventListener("transitionend", () => {
                                $(".error-message-box").css("display", "none");
                                $(".error-message-box").removeClass("removed");
                            })
                        }, 3000);
                    } else if (response["result"]) {
                        $(".success-message-box").css("display", "flex");
                        $(".success-message-box").html(response["result"]);
                        window.setTimeout(function() {
                            $(".success-message-box").addClass('removed');
                            window.addEventListener("transitionend", () => {
                                $(".success-message-box").css("display", "none");
                                $(".success-message-box").removeClass("removed");
                            })
                        }, 3000);
                    }
                }
            });

        } else {

            $(".error-message-box").css("display", "flex");
            $(".error-message-box").text("You did not change anything");
            window.setTimeout(function() {
                $(".error-message-box").addClass('removed');
                window.addEventListener("transitionend", () => {
                    $(".error-message-box").css("display", "none");
                    $(".error-message-box").removeClass("removed");
                })
            }, 3000);

        }

        e.preventDefault();
    });

});