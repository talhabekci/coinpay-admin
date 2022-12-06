<?php

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$n = mysqli_num_rows($result);

if ($n <= 0) {
    header("Location: http://localhost/coinpay-admin/login/");
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/x-icon" href="http://localhost/coinpay-admin/assets/img/cp-favicon.png">
        <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/sidebar/style.css">
        <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/payments/payments.css">
        <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/fontawesome.com/css/all.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="http://localhost/coinpay-admin/assets/js/sidebar/index.js"></script>
        <script src="http://localhost/coinpay-admin/assets/js/payments/payments.js"></script>
        <title>CoinPay Payments</title>
    </head>
    <body>
        <?php include("src/page/sidebar/sidebar.php"); ?>
        <div class="page">
            <div class="page_title">Payments</div>
            <div class="payments">
                <div class="payment_header">
                    <div class="header_links">
                        <a href="payments" class="header_link active" data-filter="All">All</a>
                        <a href="#" class="header_link" data-filter="Valid">Paid</a>
                        <a href="#" class="header_link" data-filter="Unresolved">Unresolved</a>
                        <a href="#" class="header_link" data-filter="InValid">Invalid</a>
                    </div>
                </div>
                <div class="payment_body">
                    <div class="payment_filter">
                        <select class="time_filter" name="time_filter">
                            <option value="past_30_days">Past 30 Days</option>
                            <option value="past_year">Past Year</option>
                        </select>
                    </div>
                    <div class="payment_list"></div>
                </div>
            </div>
        </div>
    </body>
</html>
