$(document).ready(function () {

    var host_name = "";

    $.ajax({
        method: "POST",
        url: "src/backend/host-name.php",
        async: false,
        data: {
            data: "host_name"
        },
        dataType: "json",
        success: function (response) {
            host_name = response["ip_address"]
        }
    });

    $("div.sidebar > ul.nav-list > li > a > i.fa-regular.fa-wave-pulse").addClass("active");

    var data;

    $.ajax({
        type: "GET",
        url: "http://" + host_name + "/coinpay-admin/src/backend/payments-query.php",
        async: false,
        success: function (response) {
            if (response["error"]) {
                if (response["error"]["message"] == "There is no payment to show") {
                    $(".no-payment").css("display", "block");
                }
            }
            data = response;
        },
        dataType: "json"
    });

    for (var i = 0; i < data.length; i++) {
        var status_color;

        if (data[i]["Status"] == "InValid") {
            status_color = "#CF304A";
        } else if (data[i]["Status"] == "Valid") {
            status_color = "#03A66D";
        }

        var pwdChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        var pwdLen = 10;
        var randString = Array(pwdLen).fill(pwdChars).map(function (x) {
            return x[Math.floor(Math.random() * x.length)]
        }).join('');

        $(".payment_list").append('<div class="payment_row"><a href="http://' + host_name + '/coinpay-admin/payments/payment/' + data[i]["Order-ID"] + '" class="payment_row_link"><div class="payment_id" style="font-weight: bold; flex: 25%; text-align: left;">' + data[i]["Order-ID"] + '</div><div class="payment_date" style="flex: 25%; text-align: left;">' + data[i]["Date"] + '</div><div class="payment_status" style="color: ' + status_color + '; flex: 25%; text-align: left;">' + data[i]["Status"] + '</div><div class="payment_amount" style="flex: 25%; text-align: right;">' + data[i]["Total-Price"] + ' USD</div></a></div>');

    }

    $('a[data-filter="InValid"]').click(function () {

        $('a[data-filter="All"]').removeClass("active");
        $('a[data-filter="Valid"]').removeClass("active");
        $('a[data-filter="Unresolved"]').removeClass("active");
        $(this).addClass("active");

        $(".payment_list").empty();

        for (var i = 0; i < data.length; i++) {

            if (data[i]["Status"] == "InValid") {

                $(".payment_list").append('<div class="payment_row"><a href="http://' + host_name + '/coinpay-admin/payments/payment/' + data[i]["Order-ID"] + '" class="payment_row_link"><div class="payment_id" style="font-weight: bold; flex: 25%; text-align: left;">' + data[i]["Order-ID"] + '</div><div class="payment_date" style="flex: 25%; text-align: left;">' + data[i]["Date"] + '</div><div class="payment_status" style="color: #CF304A; flex: 25%; text-align: left;">' + data[i]["Status"] + '</div><div class="payment_amount" style="flex: 25%; text-align: right;">' + data[i]["Total-Price"] + ' USD</div></a></div>');

            } else {

                // $(".payment_list").append('<div class="no_payments"><p class="no_payment_title">No InValid Payments</p><div class="no_payment_img"><img width="120" height="120" src="../assets/img/no-payments.svg" alt="No Payments"></div><p class="no_payments_text"> Congratulations! You have no invalid payments. Any future underpayments or overpayments will be listed here for resolution. </p></div>');
                // break;

            }
        }
    });

    $('a[data-filter="Valid"]').click(function () {

        $('a[data-filter="All"]').removeClass("active");
        $('a[data-filter="InValid"]').removeClass("active");
        $('a[data-filter="Unresolved"]').removeClass("active");
        $(this).addClass("active");

        $(".payment_list").empty();

        for (var i = 0; i < data.length; i++) {

            if (data[i]["Status"] == "Valid") {

                $(".payment_list").append('<div class="payment_row"><a href="http://' + host_name + '/coinpay-admin/payments/payment/' + data[i]["Order-ID"] + '" class="payment_row_link"><div class="payment_id" style="font-weight: bold; flex: 25%; text-align: left;">' + data[i]["Order-ID"] + '</div><div class="payment_date" style="flex: 25%; text-align: left;">' + data[i]["Date"] + '</div><div class="payment_status" style="color: #03A66D; flex: 25%; text-align: left;">' + data[i]["Status"] + '</div><div class="payment_amount" style="flex: 25%; text-align: right;">' + data[i]["Total-Price"] + ' USD</div></a></div>');

            } else {

                // $(".payment_list").append('<div class="no_payments"><p class="no_payment_title">No Valid Payments</p><div class="no_payment_img"><img width="120" height="120" src="../assets/img/no-payments.svg" alt="No Payments"></div><p class="no_payments_text">You have no valid payments. Any future underpayments or overpayments will be listed here for resolution. </p></div>');
                // break;

            }
        }
    });

    $('a[data-filter="Unresolved"]').click(function () {

        $('a[data-filter="All"]').removeClass("active");
        $('a[data-filter="InValid"]').removeClass("active");
        $('a[data-filter="Valid"]').removeClass("active");
        $(this).addClass("active");

        $(".payment_list").empty();

        for (var i = 0; i < data.length; i++) {
            if (data[i]["Status"] == "Unresolved") {

                $(".payment_list").append('<div class="payment_row"><a href="#" class="payment_row_link"><div class="payment_id" style="font-weight: bold;">' + data[i]["Order-ID"] + '</div><div class="payment_date">' + data[i]["Date"] + '</div><div class="payment_status" style="color: #03A66D;">' + data[i]["Status"] + '</div><div class="payment_amount">' + data[i]["Total-Price"] + ' USD</div></a></div>');

            } else {

                // $(".payment_list").append('<div class="no_payments"><p class="no_payment_title">No Unresolved Payments</p><div class="no_payment_img"><img width="120" height="120" src="../assets/img/no-payments.svg" alt="No Payments"></div><p class="no_payments_text"> Congratulations! You have no unresolved payments. Any future underpayments or overpayments will be listed here for resolution. </p></div>');
                // break;
            }
        }
    });

});