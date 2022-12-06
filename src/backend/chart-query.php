<?php

require 'config.php';

$result = mysqli_query($open, 'SELECT DAY(`date`) as `day`, MONTHNAME(`date`) as `monthname`, `total_price` FROM `cp_orders` WHERE `status` = 2 ');
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

if (mysqli_num_rows($result) < 1) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "There is no payment to show"]]));
}

$array = [];

foreach ($result as $row) {
    $array[] = [
        "Date" => $row["monthname"] . " " . $row["day"],
        "Total-Price" => $row["total_price"]
    ];
}

echo json_encode($array);
