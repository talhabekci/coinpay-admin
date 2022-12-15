$(document).ready(function() {

    $(".page > .page_title > i").click(function() {
        location.href = "http://localhost/coinpay-admin/wallet";
    });

    //Tether Deposit Modal
    $(".wallet-action-input.deposit").click(function() {
        var address = "";

        $.ajax({
            type: "POST",
            async: false,
            data: {
                currency: "usdt"
            },
            url: "http://localhost/coinpay-admin/src/backend/withdraw-address-create.php",
            dataType: "json",
            success: function(response) {
                address = response["result"];
            }
        });

        $("body").append('<div class="container"><div class="modal"><div class="header"><h1>CoinPAY - Deposit Ethereum</h1><button type="button" class="fa-regular fa-xmark"></button><div class="clear"></div></div><div class="main"><div class="img-qr"><img class="qr" width="300" height="300" src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' + address + '" alt=""></div><div class="text"><label id="label" for="">Address</label><div class="aaddress">' + address + ' <i class="fa-regular fa-clipboard"></i></div></div></div></div></div>');

        $("button.fa-regular.fa-xmark").click(function() {

            $(".container").remove();

        });

    });

});