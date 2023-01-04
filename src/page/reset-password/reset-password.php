<?php

$route = explode("/", $_GET['route']); //URL'i array yapıyor.

if (!isset($route[1])) {
    header("Location: http://" . $host_name["ip_address"] . "/coinpay-admin/login ");
}

$key = $route[1];

$result = mysqli_query($open, "SELECT * FROM `cp_reset_password` WHERE `key` = '" . $key . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

if (mysqli_num_rows($result) == 0) {
    header("Location: http://" . $host_name["ip_address"] . "/coinpay-admin/login ");
}

$user = mysqli_fetch_array($result);

if ($user["status"] == 2) {
    header("Location: http://" . $host_name["ip_address"] . "/coinpay-admin/login ");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/reset-password/reset-password.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/js/reset-password/reset-password.js"></script>
    <title>CoinPay Reset Password</title>
</head>

<body>

    <div class="container">
        <div class="modal">
            <div class="header">
                <h1>CoinPAY - Reset Password</h1>
            </div>
            <div class="error-message verification">
                <i class="fa-regular fa-circle-exclamation"></i>
                <span class="error-message-text"></span>
            </div>
            <div class="success-message verification">
                <i class="fa-regular fa-circle-check"></i>
                <span class="success-message-text"></span>
                <i class="fa-regular fa-circle-notch fa-spin" style="--fa-animation-duration: 1s;"></i>
            </div>
            <div class="reset-password-form">
                <form id="reset-password-form" method="post">
                    <div class="code">
                        <div class="code-label">
                            <label for="verification-code">VERIFICATION CODE</label>
                        </div>
                        <div class="code-inputs">
                            <input type="number" name="verification-code" placeholder="Enter 6 Digits Verification Code" onKeyPress="if(this.value.length==6) return false;">
                        </div>
                        <div class="new-password-label">
                            <label for="new-password">ENTER NEW PASSWORD</label>
                        </div>
                        <div class="new-password-inputs">
                            <input type="password" name="new-password" placeholder="Enter your new password">
                            <i class="fa regular fa-eye-slash new-password"></i>
                        </div>
                        <div class="retype-password-label">
                            <label for="retype-password">RETYPE NEW PASSWORD</label>
                        </div>
                        <div class="retype-password-inputs">
                            <input type="password" name="retype-password" placeholder="Enter your new password again">
                            <i class="fa regular fa-eye-slash retype-password"></i>
                        </div>
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