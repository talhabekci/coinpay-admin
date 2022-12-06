<?php
$route = explode("/", $_GET['route']);//URL'i array yapıyor.

$payment_id = $route[2];

$result = mysqli_query($open, "SELECT * FROM `cp_orders` WHERE `order_id` = '".$payment_id."' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

$order_details = mysqli_fetch_array($result);

$result = mysqli_query($open, "SELECT * FROM `cp_transactions` WHERE `order_id` = '".$payment_id."' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

$transaction_details = mysqli_fetch_array($result);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/x-icon" href="http://localhost/coinpay-admin/assets/img/cp-favicon.png">
        <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/sidebar/style.css">
        <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/payments/payment.css">
        <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/fontawesome.com/css/all.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="http://localhost/coinpay-admin/assets/js/sidebar/index.js"></script>
        <script src="http://localhost/coinpay-admin/assets/js/payments/payment.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js"></script>
        <title>CoinPay Payment Details</title>
    </head>
    <body>
        <?php include("src/page/sidebar/sidebar.php"); ?>
        <div class="page">
            <div class="page_title">
                <span>Payment Details</span>
                <i class="fa-regular fa-chevron-left"></i>
            </div>
            <div class="payment-details">
                <div class="payment-details-card">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="details-order-id">Order: #<?=$payment_id?></div>
                        </div>
                        <div class="list-group-item">
                            <div class="payment-summary">
                                <div class="payment-summary-item">
                                    <div class="payment-summary-item-value"><?=$order_details["net_price"];?></div>
                                    <div class="payment-summary-item-label">TOTAL (USD)</div>
                                </div>
                                <div class="payment-summary-item">
                                    <div class="payment-summary-item-value"><?=number_format($order_details["net_amount"], 8, '.', ',');?></div>
                                    <div class="payment-summary-item-label">TOTAL (<?=$order_details["currency"];?>)</div>
                                </div>
                                <div class="payment-summary-item">
                                    <div class="payment-summary-item-value green"><?=number_format($transaction_details["amount"], 8, '.', ',');?></div>
                                    <div class="payment-summary-item-label">PAID (<?=$order_details["currency"];?>)</div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="details-panel">
                                <div class="details-section">
                                    <div class="details-section-label">PAYMENT DETAILS</div>
                                    <div class="details-panel-item">
                                        <div class="details-panel-item-label">TXID</div>
                                        <div class="details-panel-item-field txid" title="<?=$transaction_details["txid"];?>"><?=$transaction_details["txid"];?></div>
                                        <div class="copy-btn" data-clipboard-target=".txid">
                                            <i class="fa-regular fa-clipboard"></i>
                                        </div>
                                    </div>
                                    <div class="details-panel-item">
                                        <div class="details-panel-item-label">Status</div>
                                        <?php
                                        if ($transaction_details["status"] == 2) {
                                            ?><div class="details-panel-item-field green">Complated</div><?php
                                        }else {
                                            ?><div class="details-panel-item-field red">Not Complated</div><?php
                                        }
                                        ?>
                                    </div>
                                    <div class="details-panel-item">
                                        <div class="details-panel-item-label">Confirmations</div>
                                        <div class="details-panel-item-field"><?=$transaction_details["confirmation"];?></div>
                                    </div>
                                    <div class="details-panel-item">
                                        <div class="details-panel-item-label">Currency</div>
                                        <div class="details-panel-item-field" style="text-transform: uppercase;"><?=$order_details["currency"];?></div>
                                    </div>
                                    <div class="details-panel-item">
                                        <div class="details-panel-item-label">Exchange Rate</div>
                                        <div class="details-panel-item-field" style="text-transform: uppercase;">1 <?=$order_details["currency"];?> = <?=$order_details["exrate"];?> USD</div>
                                    </div>
                                    <div class="details-panel-item">
                                        <div class="details-panel-item-label">Created At</div>
                                        <div class="details-panel-item-field"><?=$order_details["date"];?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            new ClipboardJS('.copy-btn');
        </script>
    </body>
</html>
