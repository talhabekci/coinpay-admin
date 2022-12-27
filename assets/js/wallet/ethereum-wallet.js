$(document).ready(function () {

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

    $(".page > .page_title > i").click(function () {
        location.href = "http://" + host_name + "/coinpay-admin/wallet";
    });

    var web3 = new Web3(new Web3.providers.WebsocketProvider("ws://192.168.1.90:8546"));

    var min_withdraw_amount = 50.00;
    var coinpay_fee_percentage = 3;
    var coinpay_fee = 0;

    function fee_calc(percentage, total_value) {
        var total_fee = (percentage * total_value) / 100;
        return total_fee;
    }

    var eth_current = 0;

    $.ajax({
        type: 'GET',
        url: 'http://' + host_name + '/coinpay/eth-to-usd/1',
        dataType: 'json',
        success: function (response) {
            eth_current = response["USD"];
        }
    });

    //Ethereum Withdraw Modal
    $(".wallet-action-input.withdraw").click(function () {

        var total_fee = 0;

        web3.eth.getGasPrice().then((result) => {
            var gasPrice = web3.utils.fromWei(result, 'ether');
            var ethFee = gasPrice * 42000;

            $(".network-fee > .fee-amount").text(ethFee.toFixed(8) + " ETH");
            $(".network-fee > .fee-amount").attr("data-fee-amount", ethFee.toFixed(18));
            $(".network-fee > .fee-amount").attr("title", ethFee.toFixed(18) + " ETH");

            $(".total > .fee-amount").text(ethFee.toFixed(8) + " ETH");
            $(".total > .fee-amount").attr("data-fee-amount", parseFloat(ethFee.toFixed(8)));
        });

        var pwdChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        var pwdLen = 10;
        var randString = Array(pwdLen).fill(pwdChars).map(function (x) {
            return x[Math.floor(Math.random() * x.length)]
        }).join('');


        $("body").append(
            '<div class="container"><div class="modal"><div class="header"><h1>CoinPAY - Withdraw Ethereum</h1><button type="button" class="fa-regular fa-xmark"></button><div class="clear"></div></div><div class="total-balance eth"><p class="balance-text">Total Balance</p><p class="balance-amount">' + $(".total-amount").attr("data-total-amount") + ' ETH</p></div><div class="withdraw-form"><form id="withdraw-form" method="post"><div class="amount"><div class="amount-label"><label for="withdraw-amount">AMOUNT TO WITHDRAW</label></div><div class="amount-inputs"><input type="text" pattern="[0-9]*" name="withdraw-amount" placeholder="0 ETH"><!----><i class="fa-regular fa-arrow-right-arrow-left"></i><!----><input type="text" name="withdraw-price" disabled placeholder="0.00 USD"></div><div class="amount-notices"><div class="balance-notice">Invalid or Insufficent Balance</div><div class="price-notice">Min withdraw amount is $50</div></div></div><div class="address"><div class="address-label"><label for="withdraw-address">ADDRESS</label></div><div class="address-inputs"><input type="text" name="withdraw-address" placeholder="Enter ETH Address"></div><div class="address-notices">Please ensure this address is a valid Ethereum (ETH) address</div></div></form></div><div class="fees"><div class="coinpay-fee"><p class="fee-text"> CoinPAY Fee</p><p class="fee-amount" data-fee-amount="0">0.00000000 ETH</p></div><div class="network-fee"><p class="fee-text"> Network Fee</p><p class="fee-amount" data-fee-amount="0" title="0.000000000000000000 ETH">0.00000000 ETH</p></div><div class="total"><p class="fee-text">Total</p><p class="fee-amount" data-fee-amount="0">0.00000000 ETH</p></div></div><div class="next-button"><button type="submit" name="button" class="button-next">Next</button></div></div></div>'
        );

        $("button.fa-regular.fa-xmark").click(function () {

            $(".container").remove();

        });

        if ($("input[name='withdraw-amount'], input[name='withdraw-address']").val() == "") {
            $("button.button-next").css("opacity", "0.5");
            $("button.button-next").css("pointer-events", "none");
        } else {
            $("button.button-next").css("opacity", "1");
            $("button.button-next").css("pointer-events", "all");
        }

        $("input[name='withdraw-amount']").keyup(function (event) {

            coinpay_fee = fee_calc(coinpay_fee_percentage, $("input[name='withdraw-amount']").val());
            $(".coinpay-fee > .fee-amount").text(coinpay_fee.toFixed(8) + ' ETH');
            $(".coinpay-fee > .fee-amount").attr("data-fee-amount", coinpay_fee.toFixed(8));
            total_fee = coinpay_fee + parseFloat($(".network-fee > .fee-amount").attr("data-fee-amount")) + parseFloat($("input[name='withdraw-amount']").val());
            $(".total > .fee-amount").text(total_fee.toFixed(8) + ' ETH');
            $(".total > .fee-amount").attr("data-fee-amount", total_fee.toFixed(8));

            var eth_current_parse = parseFloat(eth_current);
            var input_val_parse = parseFloat($("input[name='withdraw-amount']").val());
            if (total_fee > $(".total-amount").attr("data-total-amount") || !$.isNumeric($("input[name='withdraw-amount']").val())) {
                $("button.button-next").css("opacity", "0.5");
                $("button.button-next").css("pointer-events", "none");
                $("input[name='withdraw-amount']").css("outline-color", "#CF304A");
                $("input[name='withdraw-amount']").css("color", "#CF304A");
                $("div.balance-notice").css("display", "block");
                $(".total > .fee-amount").text("Calculating...");
                return false;
            } else {
                $("button.button-next").css("opacity", "1");
                $("button.button-next").css("pointer-events", "all");
                $("input[name='withdraw-amount']").css("outline-color", "#F99A23");
                $("input[name='withdraw-amount']").css("color", "#000");
                $("div.balance-notice").css("display", "none");
                $("input[name='withdraw-price']").val($.number(eth_current_parse * input_val_parse, 2) + " USD");
            }

            if ($.number(eth_current_parse * input_val_parse, 2) < min_withdraw_amount) {
                $("button.button-next").css("opacity", "0.5");
                $("button.button-next").css("pointer-events", "none");
                $("input[name='withdraw-price']").css("outline-color", "#CF304A");
                $("input[name='withdraw-price']").css("color", "#CF304A");
                $("div.price-notice").css("display", "block");
                $(".total > .fee-amount").text("Calculating...");
                return false;
            } else {
                $("button.button-next").css("opacity", "1");
                $("button.button-next").css("pointer-events", "all");
                $("input[name='withdraw-price']").css("outline-color", "#F99A23");
                $("input[name='withdraw-price']").css("color", "#000");
                $("div.price-notice").css("display", "none");
                $("input[name='withdraw-price']").val($.number(eth_current_parse * input_val_parse, 2) + " USD");
            }

        });

        $("input[name='withdraw-address']").keyup(function () {

            var valid = WAValidator.validate($("input[name='withdraw-address']").val(), 'ethereum');

            if (valid) {
                $("button.button-next").css("opacity", "1");
                $("button.button-next").css("pointer-events", "all");
                $("input[name='withdraw-address']").css("outline-color", "#F99A23");
                $("input[name='withdraw-address']").css("color", "#000");
                $("div.address-notices").text("Please ensure this address is a valid Ethereum (ETH) address");
                $("div.address-notices").css("color", "#0009");
                return false;
            } else {
                $("button.button-next").css("opacity", "0.5");
                $("button.button-next").css("pointer-events", "none");
                $("input[name='withdraw-address']").css("outline-color", "#CF304A");
                $("input[name='withdraw-address']").css("color", "#CF304A");
                $("div.address-notices").css("color", "#CF304A");
                $("div.address-notices").text("Invalid Ethereum Address");
            }

            if ($("input[name='withdraw-address']").val() == "") {
                $("button.button-next").css("opacity", "0.5");
                $("button.button-next").css("pointer-events", "none");
            } else {
                $("button.button-next").css("opacity", "1");
                $("button.button-next").css("pointer-events", "all");
            }

        });

        $("button.button-next").click(function () {

            if ($("input[name='withdraw-amount']").val() == "" || $("input[name='withdraw-amount']").val() == 0) {
                $("button.button-next").css("opacity", "0.5");
                $("button.button-next").css("pointer-events", "none");
                $("input[name='withdraw-amount']").css("outline-color", "#CF304A");
                $("input[name='withdraw-amount']").css("color", "#CF304A");
                $("div.balance-notice").css("color", "#CF304A");
                $("div.balance-notice").css("display", "block");
                return false;
            } else {
                $("button.button-next").css("opacity", "1");
                $("button.button-next").css("pointer-events", "all");
                $("input[name='withdraw-amount']").css("color", "");
                $("div.balance-notice").css("color", "");
                $("div.balance-notice").css("display", "none");
            }

            if ($("input[name='withdraw-address']").val() == "") {
                $("button.button-next").css("opacity", "0.5");
                $("button.button-next").css("pointer-events", "none");
                $("input[name='withdraw-address']").css("outline-color", "#CF304A");
                $("input[name='withdraw-address']").css("color", "#CF304A");
                $("div.address-notices").css("color", "#CF304A");
                $("div.address-notices").text("Ethereum Address is Required");
                return false;
            } else {
                $("button.button-next").css("opacity", "1");
                $("button.button-next").css("pointer-events", "all");
                $("input[name='withdraw-address']").css("color", "");
                $("div.address-notices").css("color", "");
                $("div.address-notices").text("Please ensure this address is a valid Ethereum (ETH) address");
            }

            var eth_network_fee = $(".network-fee > .fee-amount").attr("data-fee-amount");

            var amount = $("input[name='withdraw-amount']").val();
            var address = $("input[name='withdraw-address']").val();

            $(".container > .modal").html(
                '<div class="header"><h1>CoinPAY - Withdraw Ethereum</h1><button type="button" class="fa-regular fa-xmark"></button><div class="clear"></div></div><div class="summary-title"><p class="summary-text">You are about the withdraw: </p></div><div class="withdraw-summary"><div class="summary"><div class="summary-amount">' + total_fee.toFixed(8) + ' ETH</div><div class="summary-price">' + $.number(eth_current * total_fee, 2) + ' USD</div></div></div><div class="summary-addresses"><div class="from-address"><p class="from-text">From</p><p class="from-address">Coinpay ethereum account</p></div><div class="to-address"><p class="to-text">To</p><p class="to-address">' + $("input[name='withdraw-address']").val() + '</p></div></div><div class="fees"><div class="coinpay-fee"><p class="fee-text"> CoinPAY Fee</p><p class="fee-amount" data-fee-amount="' + coinpay_fee.toFixed(8) + '">' + coinpay_fee.toFixed(8) + ' ETH</p></div><div class="network-fee"><p class="fee-text"> Network Fee</p><p class="fee-amount" data-fee-amount="' + eth_network_fee + '">' + eth_network_fee + ' ETH</p></div><div class="total"><p class="fee-text">Total</p><p class="fee-amount" data-fee-amount="' + total_fee.toFixed(8) + '">' + total_fee.toFixed(8) + ' ETH</p></div></div><div class="button-confirm-withdrawal"><button type="submit" name="button" class="button-confirm-withdrawal">Confirm Withdrawal</button></div>'
            );

            $("button.fa-regular.fa-xmark").click(function () {
                $(".container").remove();
            });

            $("button.button-confirm-withdrawal").click(function () {

                fee = parseFloat(eth_network_fee) + parseFloat(coinpay_fee);

                $.ajax({
                    type: 'POST',
                    url: 'http://' + host_name + '/coinpay-admin/src/backend/save-withdraw.php',
                    dataType: 'json',
                    data: {
                        withdraw_id: randString,
                        net_amount: total_fee,
                        fee: fee,
                        address: address,
                        currency: 'eth'
                    },
                    success: function (response) {
                        if (response["result"] == "Save successfull") {
                            $(".container > .modal").html(
                                '<div class="header"><h1>CoinPAY - Withdraw Ethereum</h1><button type="button" class="fa-regular fa-xmark"></button><div class="clear"></div></div><div class="withdraw-success"><div class="success-img"><img width="200" height="200" src="http://' + host_name + '/coinpay-admin/assets/img/success.jpg" alt="Success"></div><div class="succes-text"><div class="text-title">Your transaction is on the way</div><div class="text-content"><p>You sent <span class="success-amount">' + amount + '</span> BTC <span class="success-price">(' + $.number(eth_current * amount, 2) + ' USD)</span> to <span class="success-address">' + address + '</span> </p></div></div></div><div class="button-back-to-balances"><button type="submit" name="button" class="back-to-balances">Go back to wallet</button></div>'
                            );

                            $("button.fa-regular.fa-xmark").click(function () {
                                location.reload();
                            });

                            $("button.back-to-balances").click(function () {
                                location.reload();
                            });
                        }
                    }

                });
            });

        });

    });

    //Ethereum Deposit Modal
    $(".wallet-action-input.deposit").click(function () {
        var address = "";

        $.ajax({
            type: "POST",
            async: false,
            data: {
                currency: "eth"
            },
            url: "http://" + host_name + "/coinpay-admin/src/backend/withdraw-address-create.php",
            dataType: "json",
            success: function (response) {
                address = response["result"];
            }
        });

        $("body").append('<div class="container"><div class="modal"><div class="header"><h1>CoinPAY - Deposit Ethereum</h1><button type="button" class="fa-regular fa-xmark"></button><div class="clear"></div></div><div class="main"><div class="img-qr"><img class="qr" width="300" height="300" src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' + address + '" alt=""></div><div class="text"><label id="label" for="">Address</label><div class="aaddress">' + address + ' <i class="fa-regular fa-clipboard"></i></div></div></div></div></div>');

        $("button.fa-regular.fa-xmark").click(function () {

            $(".container").remove();

        });

    });

});