<?php
session_start();
require 'config.php';

$result = mysqli_query($open, "SELECT `order_id` FROM `cp_transactions` WHERE `user_id` = '".$_SESSION["user_id"]."' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$order_id = mysqli_fetch_array($result);

if (mysqli_num_rows($result) < 1) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "There is no payment to show"]]));
}

$result = mysqli_query($open, "SELECT DAY(`date`) as `day`, MONTHNAME(`date`) as `monthname`, `total_price` FROM `cp_orders` WHERE `status` = 2 AND `order_id` = '".$order_id["order_id"]."' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$array = [];

foreach ($result as $row) {
    $array[] = [
        "Date" => $row["monthname"] . " " . $row["day"],
        "Total-Price" => $row["total_price"]
    ];
}

echo json_encode($array);
