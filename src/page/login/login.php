<?php
@$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '" . $_SESSION["email"] . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$n = mysqli_num_rows($result);

if ($n >= 1) {
    header("Location: http://" . $host_name["ip_address"] . "/coinpay-admin/overview/");
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/login/login.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/js/login/login.js"></script>
    <title>Login</title>
</head>

<body>
    <div class="container">
        <img src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/coinpay-svg.svg" width="80" height="80" alt="Logo" class="coinpay-logo">
        <div class="modal">
            <div class="header">
                <div class="header-title">Log in to CoinPay</div>
                <hr>
            </div>
            <div class="login">
                <div class="error-message login">
                    <i class="fa-regular fa-circle-exclamation"></i>
                    <span class="error-message-text"></span>
                </div>
                <form class="login-form" method="post">
                    <div class="email">
                        <label for="email">Email Address</label>
                        <input type="text" name="email" id="email" class="input email" autofocus autocomplete="email">
                        <div class="error-message email">Email Address is required</div>
                    </div>
                    <div class="password">
                        <label for="password">Password</label>
                        <i class="fa-regular fa-eye-slash"></i>
                        <input type="password" name="password" id="password" class="input password">
                        <div class="error-message password">Password is required</div>
                    </div>
                    <div class="submit-button">
                        <button type="submit" name="submit" class="submit-login">Login </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="reset-password">
            <a href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/forgot-password">Forgot your password?</a>
        </div>
        <div class="signup-link">Don't have an account? <a href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/signup/">Signup</a> </div>
    </div>
</body>

</html>