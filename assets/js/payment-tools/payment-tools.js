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

    $("div.sidebar > ul.nav-list > li > a > i.fa-regular.fa-box-dollar").addClass("active");

    /* $("#default-price").keyup(function (event) {
        $("code.language-html").html('&lt;form id="form" action="http://' + host_name + '/coinpay/select-currency/" method="post"&gt;&lt;input type="hidden" name="data" value="' + $("#default-price").val() + '"&gt;&lt;input type="image" src="http://' + host_name + ' /coinpay/assets/img/coinpay-logo.png" name="submit" style="width: 180px; height: 50px;" alt="CoinPay, the easy way to pay with bitcoins."&gt;&lt;/form&gt;');
    }); */

});
