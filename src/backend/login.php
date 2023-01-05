<?php

session_start();

require 'config.php';

function getIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

if (!empty($_POST["submit"] && $_POST["email"] && $_POST["password"])) {

    $ip_address = getIP();
    $email = mysqli_real_escape_string($open, $_POST["email"]);
    $password = mysqli_real_escape_string($open, $_POST["password"]);

    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '" . $email . "' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    if (mysqli_num_rows($result) == 0) {
        exit(json_encode(["result" => null, "error" => "Could not log in. Please review your information and try again."]));
    }

    $user_credentials = mysqli_fetch_array($result);

    if ($user_credentials["status"] != 2) {
        exit(json_encode(["result" => null, "error" => "Please verify your email address first."]));
    }

    $time = time() - 90; //90 seconds.
    $result = mysqli_query($open, "SELECT COUNT(*) as `total_attempts` FROM `attempts_count` WHERE `time_count` > '" . $time . "' AND `ip_address` = '" . $ip_address . "' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $total_attempts = mysqli_fetch_array($result);

    if ($total_attempts["total_attempts"] > 2) {
        exit(json_encode(["result" => null, "error" => "Too many wrong attempts please wait 90 seconds."]));
    }

    if (!password_verify($password, $user_credentials["password"])) {
        $time_count = time();

        $result = mysqli_query($open, "INSERT INTO `attempts_count` (`ip_address`, `time_count`) VALUES ('" . $ip_address . "', '" . $time_count . "') ");
        if ($result == false) {
            exit(json_encode(["result" => null, "error" => "An error occurred while inserting data into database. " . mysqli_error($open)]));
        }

        exit(json_encode(["result" => null, "error" => "Could not log in. Please review your information and try again."]));
    }

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '" . $user_credentials["id"] . "' AND `currency` = 'btc' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $user_btc_balance = mysqli_fetch_array($result);

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '" . $user_credentials["id"] . "' AND `currency` = 'eth' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $user_eth_balance = mysqli_fetch_array($result);

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '" . $user_credentials["id"] . "' AND `currency` = 'usdt' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $user_usdt_balance = mysqli_fetch_array($result);

    $_SESSION["btc_balance"] = $user_btc_balance["amount"];
    $_SESSION["eth_balance"] = $user_eth_balance["amount"];
    $_SESSION["usdt_balance"] = $user_usdt_balance["amount"];
    $_SESSION["user_id"] = $user_credentials["id"];
    $_SESSION["name"] = $user_credentials["name"];
    $_SESSION["surname"] = $user_credentials["surname"];
    $_SESSION["email"] = $email;
    $_SESSION["avatar"] = $user_credentials["user_avatar"];

    exit(json_encode(["result" => "Login Successfull", "error" => null]));
}
