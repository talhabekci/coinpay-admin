<?php

require 'src/backend/url_request.php';

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$n = mysqli_num_rows($result);

if ($n <= 0) {
    header("Location: http://localhost/coinpay-admin/login/");
}

$btc_current = request_to_url("http://localhost/coinpay/src/btcPrice/btc-to-usd?totalPrice=1");

$result = mysqli_query($open, "SELECT * FROM `cp_transactions` WHERE `email` = '".$_SESSION["email"]."' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://localhost/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/sidebar/style.css">
    <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/wallet/bitcoin-wallet.css">
    <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/wallet/withdraw-modal.css">
    <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/wallet/withdraw-summary.css">
    <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/wallet/withdraw-success.css">
    <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="http://localhost/coinpay-admin/assets/js/sidebar/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <script src="http://localhost/coinpay-admin/assets/js/wallet/bitcoin-wallet.js"></script>
    <script src="http://localhost/coinpay-admin/assets/js/wallet/number-format.js"></script>
    <script src="http://localhost/coinpay-admin/assets/js/wallet/wallet-address-validator.min.js"></script>
    <title>CoinPay Wallet</title>
</head>

<body>
    <?php include("src/page/sidebar/sidebar.php"); ?>
    <div class="page">
        <div class="page_title"><img src="http://localhost/coinpay-admin/assets/img/btc.png" width="32" height="32" alt="Bitcoin">Bitcoin Wallet</div>
        <div class="bitcoin-wallet">
            <table class="wallet-table">
                <thead class="wallet-table-head">
                    <tr class="wallet-table-row">
                        <th class="wallet-table-cell" scope="col" colspan="4">
                            <div class="wallet-table-cell-div">
                                <div class="total-balance-body">
                                    <p class="total-balance">$ <?=number_format(bcmul($btc_current["USD"], $_SESSION["btc_balance"], 2), 2, '.', ',')?></p>
                                    <p class="balance-text">Available Balance</p>
                                </div>
                                <div class="wallet-action-div">
                                    <input class="wallet-action-input deposit" type="submit" name="deposit" value="Deposit">
                                    <input class="wallet-action-input withdraw" type="submit" name="withdraw" value="Withdraw">
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="wallet-table-row">
                        <th class="table-row-titles" scope="col">Name</th>
                        <th class="table-row-titles" scope="col"></th>
                        <th class="table-row-titles" scope="col" style="text-align: right">Balance</th>
                        <th class="table-row-titles" scope="col" style="text-align: right">Action</th>
                    </tr>
                </thead>
                <tbody class="balances-table-body">
                    <tr class="balances-table-row">
                        <td class="balances-table-cell-body"></td>
                        <td class="balances-table-cell-body"></td>
                        <td class="balances-table-cell-body"></td>
                        <td class="balances-table-cell-body"></td>
                    </tr>
                    <tr class="balances-table-row">
                        <td class="balances-table-cell-body"></td>
                        <td class="balances-table-cell-body"></td>
                        <td class="balances-table-cell-body"></td>
                        <td class="balances-table-cell-body"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
