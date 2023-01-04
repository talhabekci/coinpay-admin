<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/forgot-password/forgot-password.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/js/forgot-password/forgot-password.js"></script>
    <title>CoinPay Forgot Password</title>
</head>

<body>

    <div class="container">
        <div class="modal">
            <div class="header">
                <h1>CoinPAY - Forgot Password</h1>
            </div>
            <div class="error-message forgot-password">
                <i class="fa-regular fa-circle-exclamation"></i>
                <span class="error-message-text"></span>
            </div>
            <div class="success-message forgot-password">
                <i class="fa-regular fa-circle-check"></i>
                <span class="success-message-text"></span>
            </div>
            <div class="forgot-password-form">
                <form id="forgot-password-form" method="post">
                    <div class="email">
                        <div class="email-label"><label for="email">EMAIL ADDRESS</label></div>
                        <div class="email-inputs"><input type="text" name="email" placeholder="Enter Your Email Address"></div>
                        <input type="hidden" name="key" value="<?= $key ?>">
                    </div>
                </form>
            </div>
            <div class="next-button">
                <button type="submit" name="button" class="button-next">Reset Password</button>
            </div>
        </div>
    </div>

</body>

</html>