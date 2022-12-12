<?php

session_start();

header('Access-Control-Allow-Origin: *');
//header("Content-Type: application/json");
require 'src/backend/config.php';

$route = explode("/", $_GET['route']);//URL'i array yapıyor.

if (empty($route)) {
    $route[0] = "";
}

if ($route[0] == "") {
    header("Location: http://localhost/coinpay-admin/login/");
} elseif ($route[0] == "overview") {
    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $n = mysqli_num_rows($result);

    if ($n > 0) {
        include 'src/page/overview/overview.php';
    } else {
        header("Location: http://localhost/coinpay-admin/login/");
    }
} elseif ($route[0] == "wallet") {
    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $n = mysqli_num_rows($result);

    if ($n > 0) {

        if (isset($route[1])) {
            if ($route[1] == "bitcoin-wallet") {
                include 'src/page/wallet/bitcoin-wallet.php';
                exit;
            }elseif ($route[1] == "ethereum-wallet") {
                include 'src/page/wallet/ethereum-wallet.php';
                exit;
            }elseif ($route[1] == "tether-wallet") {
                include 'src/page/wallet/tether-wallet.php';
                exit;
            }
        }

        include 'src/page/wallet/wallet.php';
    } else {
        header("Location: http://localhost/coinpay-admin/login/");
    }
} elseif ($route[0] == "payments") {
    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $n = mysqli_num_rows($result);
    if ($n > 0) {

        if (isset($route[1])) {
            if ($route[1] == "payment") {
                include 'src/page/payments/payment.php';
                exit;
            }
        }

        include 'src/page/payments/payments.php';

    } else {
        header("Location: http://localhost/coinpay-admin/login/");
    }
} elseif ($route[0] == "payment-tools") {
    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $n = mysqli_num_rows($result);

    if ($n > 0) {
        include 'src/page/payment-tools/payment-tools.php';
    } else {
        header("Location: http://localhost/coinpay-admin/login/");
    }
} elseif ($route[0] == "settings") {
    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $n = mysqli_num_rows($result);

    if ($n > 0) {
        include 'src/page/settings/settings.php';
    } else {
        header("Location: http://localhost/coinpay-admin/login/");
    }
} elseif ($route[0] == "signup") {
    include 'src/page/signup/signup.php';
} elseif ($route[0] == "login") {
    include 'src/page/login/login.php';
} elseif ($route[0] == "logout") {
    include 'src/backend/logout.php';
}else {
    header("Location: http://localhost/coinpay-admin/overview/");
}
