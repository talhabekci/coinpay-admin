<?php
session_start();
require 'request.php';
require 'config.php';

if ($_POST["currency"] == "btc") {

    $result = mysqli_query($open, "SELECT `address` FROM `cp_addresses` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' AND `is_wallet` = 'yes' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $n = mysqli_affected_rows($open);

    $user_address = mysqli_fetch_array($result);

    if ($n >= 1) {

        echo json_encode(["result" => $user_address["address"], "error" => NULL]);

    }else {

        $getnewaddress = request("getnewaddress", []);

        $result = mysqli_query($open, "INSERT INTO `cp_addresses` (`user_id`, `order_id`, `currency`, `address`, `address_password`, `is_wallet`) VALUES ('".$_SESSION["user_id"]."', NULL, 'btc', '".$getnewaddress["result"]."', NULL, 'yes') ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while inserting data to database " . mysqli_error($open)]]));
        }

        echo json_encode(["result" => $getnewaddress["result"], "error" => NULL]);
    }



}elseif ($_POST["currency"] == "eth") {

    $result = mysqli_query($open, "SELECT `address` FROM `cp_addresses` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' AND `is_wallet` = 'yes' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $n = mysqli_affected_rows($open);

    $user_address = mysqli_fetch_array($result);

    if ($n >= 1) {

        echo json_encode(["result" => $user_address["address"], "error" => NULL]);

    }else {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr( str_shuffle( $chars ), 0, 10 );

        $personal_newAccount = eth_request("personal_newAccount", [$password], 1);

        $result = mysqli_query($open, "INSERT INTO `cp_addresses` (`user_id`, `order_id`, `currency`, `address`, `address_password`, `is_wallet`) VALUES ('".$_SESSION["user_id"]."', NULL, 'eth', '".$personal_newAccount["result"]."', '".$password."', 'yes') ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while inserting data to database " . mysqli_error($open)]]));
        }

        echo json_encode(["result" => $personal_newAccount["result"], "error" => NULL]);
    }

}elseif ($_POST["currency"] == "usdt") {

    $result = mysqli_query($open, "SELECT `address` FROM `cp_addresses` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' AND `is_wallet` = 'yes' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $n = mysqli_affected_rows($open);

    $user_address = mysqli_fetch_array($result);

    if ($n >= 1) {

        echo json_encode(["result" => $user_address["address"], "error" => NULL]);

    }else {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr( str_shuffle( $chars ), 0, 10 );

        $personal_newAccount = eth_request("personal_newAccount", [$password], 1);

        $result = mysqli_query($open, "INSERT INTO `cp_addresses` (`user_id`, `order_id`, `currency`, `address`, `address_password`, `is_wallet`) VALUES ('".$_SESSION["user_id"]."', NULL, 'eth', '".$personal_newAccount["result"]."', '".$password."', 'yes') ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while inserting data to database " . mysqli_error($open)]]));
        }

        echo json_encode(["result" => $personal_newAccount["result"], "error" => NULL]);
    }

}

?>
