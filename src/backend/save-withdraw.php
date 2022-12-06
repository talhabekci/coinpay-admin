<?php

session_start();

if (!isset($_POST["withdraw_id"], $_POST["amount"], $_POST["address"], $_POST["currency"])) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred. Missing value"]]));
}

require 'config.php';

$withdraw_id = $_POST["withdraw_id"];
$amount = $_POST["amount"];
$address = $_POST["address"];
$currency = $_POST["currency"];
$btc_balance = $_SESSION["btc_balance"];
$eth_balance = $_SESSION["eth_balance"];
$usdt_balance = $_SESSION["usdt_balance"];

if ($currency == "btc") {

    $fee = $_POST["fee"];

    if ($amount > $btc_balance) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "Insufficent Balance"]]));
    }

    $net_amount = $amount + $fee;

    $result = mysqli_query($open, "INSERT INTO `cp_withdraws` (`withdraw_id`, `user_id`, `address`, `amount`, `fee`, `net_amount`, `currency`, `status`) VALUES ('".$withdraw_id."', '".$_SESSION["user_id"]."', '".$address."', '".$amount."', '".$fee."', '".$net_amount."', '".$currency."', 0) ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error ocurred while inserting data to database " . mysqli_error($open)]]));
    }

    $new_balance = $btc_balance - $amount;

    $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
    }

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
    }

    $new_btc_balance = mysqli_fetch_array($result);

    $_SESSION["btc_balance"] = $new_btc_balance["amount"];

    exit(json_encode(["result" => "Save successfull", "error" => null]));
} elseif ($currency == "eth") {

    $fee = $_POST["fee"];

    if ($amount > $eth_balance) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "Insufficent Balance"]]));
    }

    $net_amount = $amount + $fee;

    $result = mysqli_query($open, "INSERT INTO `cp_withdraws` (`withdraw_id`, `user_id`, `address`, `amount`, `fee`, `net_amount`, `currency`, `status`) VALUES ('".$withdraw_id."', '".$_SESSION["user_id"]."', '".$address."', '".$amount."', '".$fee."', '".$net_amount."', '".$currency."', 0) ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error ocurred while inserting data to database " . mysqli_error($open)]]));
    }

    $new_balance = $eth_balance - $amount;

    $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
    }

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
    }

    $new_eth_balance = mysqli_fetch_array($result);

    $_SESSION["eth_balance"] = $new_eth_balance["amount"];

    exit(json_encode(["result" => "Save successfull", "error" => null]));
} elseif ($currency == "usdt") {

    $eth_fee = $_POST["eth_fee"];
    $coinpay_fee = $_POST["coinpay_fee"];

    if ($amount > $usdt_balance) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "Insufficent Balance"]]));
    }

    $net_amount = $amount + $coinpay_fee;

    $result = mysqli_query($open, "INSERT INTO `cp_withdraws` (`withdraw_id`, `user_id`, `address`, `amount`, `fee`, `net_amount`, `currency`, `status`) VALUES ('".$withdraw_id."', '".$_SESSION["user_id"]."', '".$address."', '".$amount."', '".$coinpay_fee."', '".$net_amount."', '".$currency."', 0) ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error ocurred while inserting data to database " . mysqli_error($open)]]));
    }

    $new_balance = $usdt_balance - $amount;

    $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
    }

    $new_eth_balance = $eth_balance - $eth_fee;

    $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_eth_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
    }

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $new_usdt_balance = mysqli_fetch_array($result);

    $_SESSION["usdt_balance"] = $new_usdt_balance["amount"];

    $result = mysqli_query($open, "SELECT * FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $new_eth_balance_session = mysqli_fetch_array($result);

    $_SESSION["eth_balance"] = $new_eth_balance_session["amount"];

    exit(json_encode(["result" => "Save successfull", "error" => null]));
}
