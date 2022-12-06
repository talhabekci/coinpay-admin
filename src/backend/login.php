<?php

session_start();

require 'config.php';

if (!empty($_POST["submit"] && $_POST["email"] && $_POST["password"])) {
    $email = mysqli_real_escape_string($open, $_POST["email"]);
    $password = mysqli_real_escape_string($open, $_POST["password"]);

    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$email."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $n = mysqli_num_rows($result);

    if ($n <= 0) {
        exit(json_encode(["result" => null, "error" => "Could not log in. Please review your information and try again."]));
    }

    $user_credentials = mysqli_fetch_array($result);

    if (!password_verify($password, $user_credentials["password"])) {
        exit(json_encode(["result" => null, "error" => "Could not log in. Please review your information and try again."]));
    }

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '".$user_credentials["id"]."' AND `currency` = 'btc' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $user_btc_balance = mysqli_fetch_array($result);

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '".$user_credentials["id"]."' AND `currency` = 'eth' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $user_eth_balance = mysqli_fetch_array($result);

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '".$user_credentials["id"]."' AND `currency` = 'usdt' ");
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
