<?php

session_start();

require 'config.php';

$result = mysqli_query($open, 'SELECT ROUND(SUM(`net_amount`), 8) AS `btc_balance` FROM `cp_orders` WHERE `status` = 2 AND `currency` = "btc" ');
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$btc_balance = mysqli_fetch_array($result);

echo "BTC: " . $btc_balance["btc_balance"] . "<br>";

$result = mysqli_query($open, 'SELECT ROUND(SUM(`net_amount`), 8) AS `eth_balance` FROM `cp_orders` WHERE `status` = 2 AND `currency` = "eth" ');
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$eth_balance = mysqli_fetch_array($result);
echo "ETH" . $eth_balance["eth_balance"] . "<br>";

$result = mysqli_query($open, 'SELECT ROUND(SUM(`net_amount`), 18) AS `usdt_balance` FROM `cp_orders` WHERE `status` = 2 AND `currency` = "usdt" ');
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$usdt_balance = mysqli_fetch_array($result);
echo "USDT" .  $usdt_balance["usdt_balance"] . "<br>";

$result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$btc_balance["btc_balance"]."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
}

$result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$eth_balance["eth_balance"]."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
}

$result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$usdt_balance["usdt_balance"]."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
}
