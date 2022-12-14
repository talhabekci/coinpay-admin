<?php

require 'config.php';

$result = mysqli_query($open, 'SELECT DAY(`date`) as `day`, MONTHNAME(`date`) as `monthname`, DATE_FORMAT(`date`,"%H:%i") as `date_formated`, `order_id`, `net_price`, `status` FROM `cp_orders` WHERE `date` BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() AND (`status` = "1" OR `status` = "2") ORDER BY `date` DESC');
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}


if (mysqli_num_rows($result) < 1) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "There is no payment to show"]]));
}

$array = [];

foreach ($result as $row) {
    if ($row["status"] == 1) {
        $status = "InValid";
    }
    if ($row["status"] == 2) {
        $status = "Valid";
    }

    $array[] = [
        "Date" => $row["monthname"] . " " . $row["day"] . ", " . $row["date_formated"],
        "Order-ID" => $row["order_id"],
        "Total-Price" => $row["net_price"],
        "Status" => $status
    ];

}

echo json_encode($array);

?>
