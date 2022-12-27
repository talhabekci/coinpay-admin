<?php
$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '" . $_SESSION["email"] . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$n = mysqli_num_rows($result);

if ($n <= 0) {
    header("Location: http://" . $host_name["ip_address"] . "/coinpay-admin/login/");
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/sidebar/style.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/payment-tools/payment-tools.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/js/sidebar/index.js"></script>
    <script src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/js/payment-tools/payment-tools.js"></script>
    <title>CoinPay Payment Tools</title>
</head>

<body>
    <?php include("src/page/sidebar/sidebar.php"); ?>
    <div class="page">
        <div class="page_title">Payment Tools</div>
        <div class="payment-tools">
            <div class="payment-tools-cards">
                <div class="payment-tools-card" id="payment-buttons">
                    <a href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/payment-tools/payment-buttons">
                        <div class="card-body">
                            <div class="card-title">Payment Buttons</div>
                            <div class="card-content">
                                <div class="content-text">Simple invoicing and billing to allow your clients to pay you using cryptocurrency.</div>
                                <img src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/payment-buttons.svg" alt="Client Billing" class="content-img">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="payment-tools-card">
                    <div class="card-body">
                        <div class="card-title">Email Billing (Soon)</div>
                        <div class="card-content">
                            <div class="content-text">Simple invoicing and billing to allow your clients to pay you using cryptocurrency.</div>
                            <img src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/client-billing.svg" alt="Client Billing" class="content-img">
                        </div>
                    </div>
                </div>
                <div class="payment-tools-card">
                    <div class="card-body">
                        <div class="card-title">Hosted Catalog (Soon)</div>
                        <div class="card-content">
                            <div class="content-text">Simple invoicing and billing to allow your clients to pay you using cryptocurrency.</div>
                            <img src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/hosted-catalog.svg" alt="Client Billing" class="content-img">
                        </div>
                    </div>
                </div>
                <div class="payment-tools-card">
                    <div class="card-body">
                        <div class="card-title">Point of Sale App (Soon)</div>
                        <div class="card-content">
                            <div class="content-text">Simple invoicing and billing to allow your clients to pay you using cryptocurrency.</div>
                            <img src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/pos.svg" alt="Client Billing" class="content-img">
                        </div>
                    </div>
                </div>
                <div class="payment-tools-card">
                    <div class="card-body">
                        <div class="card-title">Quick Checkout for Web (Soon)</div>
                        <div class="card-content">
                            <div class="content-text">Simple invoicing and billing to allow your clients to pay you using cryptocurrency.</div>
                            <img src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/qr-code.svg" alt="Client Billing" class="content-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>