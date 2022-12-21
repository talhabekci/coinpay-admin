<?php
require 'src/backend/config.php';
require 'src/backend/url_request.php';

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$n = mysqli_num_rows($result);

if ($n <= 0) {
    header("Location: http://".$host_name["ip_address"]."/coinpay-admin/login/");
}

$btc_current = request_to_url("http://".$host_name["ip_address"]."/coinpay/src/btcPrice/btc-to-usd?totalPrice=1");
$eth_current = request_to_url("http://".$host_name["ip_address"]."/coinpay/src/ethPrice/eth-to-usd?totalPrice=1");
$usdt_current = request_to_url("http://".$host_name["ip_address"]."/coinpay/src/usdtPrice/usdt-to-usd?totalPrice=1");

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/sidebar/style.css">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/wallet/wallet.css">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/wallet/withdraw-modal.css">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/wallet/withdraw-summary.css">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/wallet/withdraw-success.css">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/sidebar/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/wallet/wallet.js"></script>
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/wallet/number-format.js"></script>
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/wallet/wallet-address-validator.min.js"></script>
    <title>CoinPay Wallet</title>
</head>

<body>
    <?php include("src/page/sidebar/sidebar.php"); ?>
    <div class="page">
        <div class="page_title">
        <i class="fa-regular fa-wallet"></i>
        <span>Wallet</span>
        </div>
        <div class="balances">
            <table class="balances-table">
                <thead class="balances-table-head">
                    <tr class="balances-table-row">
                        <th class="balances-table-cell" scope="col" colspan="4">
                            <div class="balances-table-cell-div">
                                <div class="total-balance-body">
                                    <p class="total-balance">$0.00</p>
                                    <p class="balance-text">Available Balance</p>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="balances-table-row">
                        <th class="table-row-titles" scope="col">Name</th>
                        <th class="table-row-titles" scope="col"></th>
                        <th class="table-row-titles" scope="col" style="text-align: right">Balance</th>
                        <th class="table-row-titles" scope="col" style="text-align: right">Action</th>
                    </tr>
                </thead>
                <tbody class="balances-table-body">
                    <tr class="balances-table-row">
                        <td class="balances-table-cell-body btc">
                            <div class="cell-body-div-name">
                                <div class="cell-body-img">
                                    <img src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/btc.png" width="32" height="32" alt="Bitcoin">
                                </div>
                                <span role="presentation" aria-hidden="true" style="flex-grow: 0; flex-shrink: 0; width: 8px;"></span>
                                <p class="cell-body-text">Bitcoin</p>
                            </div>
                        </td>
                        <td class="balances-table-cell-body btc"></td>
                        <td class="balances-table-cell-body btc">
                            <div class="cell-body-div-balance">
                                <div class="cell-body-div-balance-text">
                                    <p class="balance-amount btc" data-btc-balance="<?=number_format($_SESSION["btc_balance"], 8, '.', ',')?>"><?=number_format($_SESSION["btc_balance"], 8, '.', ',')?> BTC</p>
                                    <p class="balance-price btc" data-btc-price="<?=bcmul($btc_current["USD"], $_SESSION["btc_balance"], 2)?>">$ <?=bcmul($btc_current["USD"], $_SESSION["btc_balance"], 2)?></p>
                                </div>
                            </div>
                        </td>
                        <td class="balances-table-cell-body">
                            <div class="cell-body-div-action">
                                <div class="cell-body-div-action-text">
                                    <p class="action-name deposit-btc">Deposit</p>
                                    <p class="action-name withdraw-btc">Withdraw</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="balances-table-row">
                        <td class="balances-table-cell-body eth">
                            <div class="cell-body-div-name">
                                <div class="cell-body-img">
                                    <img src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/eth.svg" width="32" height="32" alt="Bitcoin">
                                </div>
                                <span role="presentation" aria-hidden="true" style="flex-grow: 0; flex-shrink: 0; width: 8px;"></span>
                                <p class="cell-body-text">Ethereum</p>
                            </div>
                        </td>
                        <td class="balances-table-cell-body eth"></td>
                        <td class="balances-table-cell-body eth">
                            <div class="cell-body-div-balance">
                                <div class="cell-body-div-balance-text">
                                    <p class="balance-amount eth" data-eth-balance="<?=number_format($_SESSION["eth_balance"], 8, '.', ',')?>"><?=number_format($_SESSION["eth_balance"], 8, '.', ',');?> ETH</p>
                                    <p class="balance-price eth" data-eth-price="<?=bcmul($eth_current["USD"], $_SESSION["eth_balance"], 2)?>">$ <?=bcmul($eth_current["USD"], $_SESSION["eth_balance"], 2)?></p>
                                </div>
                            </div>
                        </td>
                        <td class="balances-table-cell-body">
                            <div class="cell-body-div-action">
                                <div class="cell-body-div-action-text">
                                    <p class="action-name deposit-eth">Deposit</p>
                                    <p class="action-name withdraw-eth">Withdraw</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="balances-table-row">
                        <td class="balances-table-cell-body usdt">
                            <div class="cell-body-div-name">
                                <div class="cell-body-img">
                                    <img src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/usdt.svg" width="32" height="32" alt="Bitcoin">
                                </div>
                                <span role="presentation" aria-hidden="true" style="flex-grow: 0; flex-shrink: 0; width: 8px;"></span>
                                <p class="cell-body-text">Tether</p>
                            </div>
                        </td>
                        <td class="balances-table-cell-body usdt"></td>
                        <td class="balances-table-cell-body usdt">
                            <div class="cell-body-div-balance">
                                <div class="cell-body-div-balance-text">
                                    <p class="balance-amount usdt" data-usdt-balance="<?=number_format($_SESSION["usdt_balance"], 8, '.', ',')?>"><?=number_format($_SESSION["usdt_balance"], 2, '.', ',')?> USDT</p>
                                    <p class="balance-price usdt" data-usdt-price="<?=bcmul($usdt_current["USD"], $_SESSION["usdt_balance"], 2)?>">$ <?=bcmul($usdt_current["USD"], $_SESSION["usdt_balance"], 2)?></p>
                                </div>
                            </div>
                        </td>
                        <td class="balances-table-cell-body">
                            <div class="cell-body-div-action">
                                <div class="cell-body-div-action-text">
                                    <p class="action-name deposit-usdt">Deposit</p>
                                    <p class="action-name withdraw-usdt">Withdraw</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
