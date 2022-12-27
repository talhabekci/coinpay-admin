<?php
session_start();
require 'config.php';

$result = mysqli_query($open, "SELECT `order_id` FROM `cp_transactions` WHERE `user_id` = '" . $_SESSION["user_id"] . "' AND (`status` = '1' OR `status` = '2') AND `is_wallet` = 'no' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

if (mysqli_num_rows($result) < 1) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "There is no payment to show"]]));
}

$array = [];

foreach ($result as $order_id) {

    $result = mysqli_query($open, 'SELECT DAY(`date`) as `day`, MONTHNAME(`date`) as `monthname`, DATE_FORMAT(`date`,"%H:%i") as `date_formated`, `order_id`, `net_price`, `status` FROM `cp_orders` WHERE `order_id` = "' . $order_id['order_id'] . '" AND (`status` = "1" OR `status` = "2") ORDER BY `date` DESC');
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
    }

    $order_details = mysqli_fetch_array($result);

    if ($order_details["status"] == 1) {
        $status = "InValid";
    }
    if ($order_details["status"] == 2) {
        $status = "Valid";
    }

    $array[] = [
        "Date" => $order_details["monthname"] . " " . $order_details["day"] . ", " . $order_details["date_formated"],
        "Order-ID" => $order_details["order_id"],
        "Total-Price" => $order_details["net_price"],
        "Status" => $status
    ];
}

echo json_encode($array);
