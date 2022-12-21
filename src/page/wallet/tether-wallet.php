<?php

require 'src/backend/url_request.php';

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$n = mysqli_num_rows($result);

if ($n <= 0) {
    header("Location: http://".$host_name["ip_address"]."/coinpay-admin/login/");
}

$usdt_current = request_to_url("http://".$host_name["ip_address"]."/coinpay/src/usdtPrice/usdt-to-usd?totalPrice=1");

$tx_result = mysqli_query($open, "SELECT DAY(`date`) as `day`, MONTHNAME(`date`) as `monthname`, DATE_FORMAT(`date`,'%H:%i') as `date_formated`, `type`, `txid`, `status` FROM `cp_transactions` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt' AND `is_wallet` = 'yes'  ORDER BY `date` DESC ");
if ($tx_result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$tx_number = mysqli_num_rows($tx_result);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/sidebar/style.css">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/wallet/tether-wallet.css">
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
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/wallet/tether-wallet.js"></script>
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/wallet/number-format.js"></script>
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/wallet/wallet-address-validator.min.js"></script>
    <title>CoinPay Tether Wallet</title>
</head>

<body>
    <?php include("src/page/sidebar/sidebar.php"); ?>
    <div class="page">
        <div class="page_title">
            <img src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/usdt.svg" width="32" height="32" alt="Bitcoin">
            <span>Tether Wallet</span>
            <i class="fa-regular fa-chevron-left"></i>
        </div>
        <div class="tether-wallet">
            <?php
            if ($tx_number <= 0) {
                ?>
                <div class="no-transaction">
                    <svg class="no-tx-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M64 8H26v80h58V28H64V8zM36 37h38v4H36v-4zm0 9h38v4H36v-4zm38 9H36v4h38v-4zm-8 12l4 4-4 4-4-4 4-4zM50 18l-3 3 3 3 3-3-3-3z" fill="url(#not-found-data_svg__paint0_linear_22059_32288)"></path>
                        <path opacity="0.3" d="M86 50l3-3 3 3-3 3-3-3zM47 21l3-3 3 3-3 3-3-3zM84 28H64V8l20 20z" fill="#F99A23"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.172 73.171l14.5-14.5 5.656 5.658-14.5 14.5-5.656-5.657z" fill="url(#not-found-data_svg__paint1_linear_22059_32288)"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M51 48c0-8.837-7.163-16-16-16s-16 7.163-16 16 7.163 16 16 16 16-7.163 16-16zm4 0c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20z"
                            fill="url(#not-found-data_svg__paint2_linear_22059_32288)"></path>
                        <defs>
                            <linearGradient id="not-found-data_svg__paint0_linear_22059_32288" x1="55" y1="8" x2="55" y2="88" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stop-color="#F99A23" stop-opacity="0.1"></stop>
                                <stop offset="1" stop-color="#F99A23" stop-opacity="0.25"></stop>
                            </linearGradient>
                            <linearGradient id="not-found-data_svg__paint1_linear_22059_32288" x1="4.172" y1="68.75" x2="24.328" y2="68.75" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stop-color="#F99A23"></stop>
                                <stop offset="1" stop-color="#F99A23"></stop>
                            </linearGradient>
                            <linearGradient id="not-found-data_svg__paint2_linear_22059_32288" x1="15" y1="48" x2="55" y2="48" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stop-color="#F99A23"></stop>
                                <stop offset="1" stop-color="#F99A23"></stop>
                            </linearGradient>
                        </defs>
                    </svg>
                    <p class="no-tx-msg">No Transaction Found</p>
                </div>
                <?php
            }
            ?>
            <table class="wallet-table">
                <thead class="wallet-table-head">
                    <tr class="wallet-table-row">
                        <th class="wallet-table-cell" scope="col" colspan="4">
                            <div class="wallet-table-cell-div">
                                <div class="total-balance-body">
                                    <p class="total-amount" data-total-amount="<?=$_SESSION["usdt_balance"]?>"><?=$_SESSION["usdt_balance"]?> USDT</p>
                                    <p class="total-balance"> ≈ $<?=number_format(bcmul($usdt_current["USD"], $_SESSION["usdt_balance"], 2), 2, '.', ',')?></p>
                                </div>
                                <div class="wallet-action-div">
                                    <input class="wallet-action-input deposit" type="submit" name="deposit" value="Deposit">
                                    <input class="wallet-action-input withdraw" type="submit" name="withdraw" value="Withdraw">
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="wallet-table-row">
                        <th class="table-row-titles" scope="col">Date</th>
                        <th class="table-row-titles" scope="col">Type</th>
                        <th class="table-row-titles" scope="col" style="text-align: center;">Transaction ID</th>
                        <th class="table-row-titles" scope="col" style="text-align: right;">Status</th>
                    </tr>
                </thead>
                <tbody class="wallet-table-body">
                    <?php
                    foreach ($tx_result as $transactions) {
                        ?>
                        <tr class="wallet-table-row">
                            <td class="wallet-table-cell-body">
                                <div class="cell-body-div-date">
                                    <div class="cell-body-div-date-text">
                                        <p class="date"><?=$transactions["monthname"] . " " . $transactions["day"] . ", " . $transactions["date_formated"]?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="wallet-table-cell-body">
                                <div class="cell-body-div-type">
                                    <div class="cell-body-div-type-text">
                                        <?php
                                        if ($transactions["type"] == "1") {
                                            ?>
                                            <p class="type-name">Withdraw</p>
                                            <?php
                                        }else{
                                            ?>
                                            <p class="type-name">Deposit</p>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </td>
                            <td class="wallet-table-cell-body" style="text-align: center;">
                                <div class="cell-body-div-txid">
                                    <div class="cell-body-div-txid-text">
                                        <p class="txid">
                                            <a href="https://www.blockchain.com/btc-testnet/tx/<?=$transactions["txid"]?>" target="_blank"><?=$transactions["txid"]?></a>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="wallet-table-cell-body">
                                <div class="cell-body-div-status">
                                    <div class="cell-body-div-status-text">
                                        <?php
                                        if ($transactions["status"] == "0") {
                                            ?>
                                            <p class="status" style="color: #FBB35B;">
                                                <span>Processing</span>
                                                <i class="fa-spin fa-regular fa-circle-notch"></i>
                                            </p>
                                            <?php
                                        }elseif ($transactions["status"] == "1") {
                                            ?>
                                            <p class="status" style="color: #CF304A;">
                                                <span>Cancelled</span>
                                                <i class="fa-regular fa-xmark"></i>
                                            </p>
                                            <?php
                                        }elseif ($transactions["status"] == "2") {
                                            ?>
                                            <p class="status" style="color: rgb(3, 166, 109);">
                                                <span>Complated</span>
                                                <i class="fa-regular fa-check"></i>
                                            </p>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
