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
                    <div class="no-payment">
                        <svg class="no-payment-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96" fill="none" class="mirror css-1tx71v4">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M64 8H26v80h58V28H64V8zM36 37h38v4H36v-4zm0 9h38v4H36v-4zm38 9H36v4h38v-4zm-8 12l4 4-4 4-4-4 4-4zM50 18l-3 3 3 3 3-3-3-3z" fill="url(#not-found-data_svg__paint0_linear_22059_32288)"></path>
                            <path opacity="0.3" d="M86 50l3-3 3 3-3 3-3-3zM47 21l3-3 3 3-3 3-3-3zM84 28H64V8l20 20z" fill="#F99A23"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.172 73.171l14.5-14.5 5.656 5.658-14.5 14.5-5.656-5.657z" fill="url(#not-found-data_svg__paint1_linear_22059_32288)"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M51 48c0-8.837-7.163-16-16-16s-16 7.163-16 16 7.163 16 16 16 16-7.163 16-16zm4 0c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20z"
                                fill="url(#not-found-data_svg__paint2_linear_22059_32288)"></path>
                            <defs>
                                <linearGradient id="not-found-data_svg__paint0_linear_22059_32288" x1="55" y1="8" x2="55" y2="88" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#F99A23" stop-opacity="0.1"></stop>
                                    <stop offset="1" stop-color="#F99A23" stop-opacity="0.25"></stop>
                                </linearGradient>
                                <linearGradient id="not-found-data_svg__paint1_linear_22059_32288" x1="4.172" y1="68.75" x2="24.328" y2="68.75" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#F99A23"></stop>
                                    <stop offset="1" stop-color="#F99A23"></stop>
                                </linearGradient>
                                <linearGradient id="not-found-data_svg__paint2_linear_22059_32288" x1="15" y1="48" x2="55" y2="48" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#F99A23"></stop>
                                    <stop offset="1" stop-color="#F99A23"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                        <p class="no-payment-msg">No Payment Found</p>
                    </div>
                    <div class="payment_list"></div>
                </div>
            </div>
        </div>
    </body>
</html>
